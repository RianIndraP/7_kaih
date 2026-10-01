<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ManajemenSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::whereNotNull('nisn')
            ->with(['kelas', 'waliKelas.user']);

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }

        // Filter by angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Search by name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswa = $query->latest()->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $angkatanList = User::whereNotNull('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $guruWaliList = Guru::with('user')->get();

        return view('admin.manajemen-siswa', compact('siswa', 'kelasList', 'angkatanList', 'guruWaliList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:users,nisn',
            'name' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'tempat_lahir' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'guru_wali_id' => 'required|exists:guru,id',
            'gender' => 'required|in:Laki-laki,Perempuan',
        ]);

        // Create user with default password "siswa"
        User::create([
            'nisn' => $validated['nisn'],
            'name' => $validated['name'],
            'kelas_id' => $validated['kelas_id'],
            'angkatan' => $validated['angkatan'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'birth_date' => $validated['birth_date'],
            'guru_wali_id' => $validated['guru_wali_id'],
            'gender' => $validated['gender'],
            'email' => null,
            'password' => Hash::make('siswa'),
        ]);

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::whereNotNull('nisn')->findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'required|string|unique:users,nisn,' . $id,
            'name' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'tempat_lahir' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'guru_wali_id' => 'required|exists:guru,id',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'nisn' => $validated['nisn'],
            'name' => $validated['name'],
            'kelas_id' => $validated['kelas_id'],
            'angkatan' => $validated['angkatan'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'birth_date' => $validated['birth_date'],
            'guru_wali_id' => $validated['guru_wali_id'],
            'gender' => $validated['gender'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil diupdate!');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::whereNotNull('nisn')->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $ids = array_filter(explode(',', $validated['selected_ids']));

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        $deletedCount = User::whereNotNull('nisn')
            ->whereIn('id', $ids)
            ->delete();

        return redirect()->route('admin.siswa')->with('success', $deletedCount . ' siswa berhasil dihapus!');
    }

    public function addKelas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas',
        ]);

        Kelas::create($validated);

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        try {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            $rows = [];

            if ($extension === 'csv' || $extension === 'txt') {
                // CSV import
                $handle = fopen($file->getPathname(), 'r');
                if (!$handle) {
                    return redirect()->route('admin.siswa')->with('error', 'Gagal membaca file');
                }
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rows[] = $data;
                }
                fclose($handle);
            } else {
                // Excel import (XLSX, XLS)
                // Check if ZipArchive is available (required for XLSX)
                if (!class_exists('ZipArchive')) {
                    $errorMsg = 'Extension PHP "zip" belum diaktifkan untuk import XLSX/XLS. ' .
                        'Solusi: 1) Aktifkan extension zip di php.ini (cari ;extension=zip ubah jadi extension=zip) lalu restart Apache. ' .
                        '2) Atau simpan file sebagai CSV (File → Save As → CSV) dan upload CSV.';
                    return redirect()->route('admin.siswa')->with('error', $errorMsg);
                }
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
            }

            $imported = 0;
            $errors = [];

            // Skip first 2 rows (kop surat), row 3 is header
            array_shift($rows); // Skip row 1
            array_shift($rows); // Skip row 2
            $header = array_shift($rows); // Row 3 is header

            foreach ($rows as $index => $row) {
                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                // Map Excel columns
                $nisn = trim((string) ($row[0] ?? ''));
                $name = trim($row[1] ?? '');
                $kelasName = trim($row[2] ?? '');
                $angkatan = trim($row[3] ?? '');
                $birthDate = trim($row[4] ?? '');
                $birthPlace = trim($row[5] ?? '');
                $gender = trim($row[6] ?? '');
                $guruWaliName = trim($row[7] ?? '');

                // Row errors for this student
                $rowErrors = [];

                // Skip if required fields (NISN and Nama) are empty
                if (empty($nisn) || empty($name)) {
                    $errors[] = 'Baris ' . ($index + 4) . ': NISN dan Nama wajib diisi - Data diabaikan';
                    continue;
                }

                // Check if NISN already exists
                $existingUser = User::where('nisn', $nisn)->first();
                $isUpdate = $existingUser ? true : false;

                // Find kelas_id by kelas name
                $kelas = null;
                if (!empty($kelasName)) {
                    $kelas = Kelas::where('nama_kelas', $kelasName)->first();
                    if (!$kelas) {
                        $rowErrors[] = 'Kelas (' . $kelasName . ' tidak ditemukan, dikosongkan)';
                    }
                }

                // Find guru_wali_id by guru wali name
                $guruWali = null;
                if (!empty($guruWaliName)) {
                    // Clean up guru wali name (remove common prefixes)
                    $cleanGuruName = str_replace(['Mr.', 'Mrs.', 'Ms.', 'Pak', 'Bu'], '', $guruWaliName);
                    $cleanGuruName = trim($cleanGuruName);

                    $guruWali = Guru::whereHas('user', function ($q) use ($cleanGuruName) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($cleanGuruName) . '%']);
                    })->first();

                    if (!$guruWali) {
                        $rowErrors[] = 'Guru Wali (' . $guruWaliName . ' tidak ditemukan, dikosongkan)';
                    }
                }

                // Parse birth_date
                $parsedBirthDate = null;
                if (!empty($birthDate)) {
                    // Excel toArray() returns date as DateTime object or string
                    if ($birthDate instanceof \DateTime) {
                        // Already converted by PhpSpreadsheet
                        $parsedBirthDate = $birthDate->format('Y-m-d');
                    }
                    // Excel serial number (integer or float)
                    elseif (is_numeric($birthDate)) {
                        $parsedBirthDate = Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
                    }
                    // Already Y-m-d format
                    elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {
                        $parsedBirthDate = $birthDate;
                    }
                    // String format m/d/Y (US format) - Excel often returns this
                    elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $birthDate)) {
                        // Try US format first (m/d/Y) - day > 12 indicates US format
                        $parts = explode('/', $birthDate);
                        if ((int) $parts[1] > 12) {
                            // Day > 12, must be US format (month/day/year)
                            try {
                                $parsedBirthDate = \Carbon\Carbon::createFromFormat('m/d/Y', $birthDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                $parsedBirthDate = null;
                            }
                        } else {
                            // Could be either, try US first then Indonesian
                            try {
                                $parsedBirthDate = \Carbon\Carbon::createFromFormat('m/d/Y', $birthDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                try {
                                    $parsedBirthDate = \Carbon\Carbon::createFromFormat('d/m/Y', $birthDate)->format('Y-m-d');
                                } catch (\Exception $e2) {
                                    $parsedBirthDate = null;
                                }
                            }
                        }
                    }
                    // String format d-m-Y or m-d-Y (dash separator)
                    elseif (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $birthDate)) {
                        $parts = explode('-', $birthDate);
                        if ((int) $parts[1] > 12) {
                            // Day > 12, must be m-d-Y
                            try {
                                $parsedBirthDate = \Carbon\Carbon::createFromFormat('m-d-Y', $birthDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                $parsedBirthDate = null;
                            }
                        } else {
                            try {
                                $parsedBirthDate = \Carbon\Carbon::createFromFormat('d-m-Y', $birthDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                try {
                                    $parsedBirthDate = \Carbon\Carbon::createFromFormat('m-d-Y', $birthDate)->format('Y-m-d');
                                } catch (\Exception $e2) {
                                    $parsedBirthDate = null;
                                }
                            }
                        }
                    }
                    // Try other formats
                    else {
                        $formats = ['d.m.Y', 'Y/m/d'];
                        foreach ($formats as $format) {
                            try {
                                $parsedBirthDate = \Carbon\Carbon::createFromFormat($format, $birthDate)->format('Y-m-d');
                                break;
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                    }

                    if (empty($parsedBirthDate)) {
                        $rowErrors[] = 'Tanggal Lahir tidak valid: ' . (is_string($birthDate) ? $birthDate : gettype($birthDate));
                    }
                }

                // Normalize gender
                $normalizedGender = null;
                if (!empty($gender)) {
                    $genderLower = strtolower(str_replace(' ', '-', $gender));
                    if (in_array($genderLower, ['laki-laki', 'l', 'male', 'laki'])) {
                        $normalizedGender = 'Laki-laki';
                    } elseif (in_array($genderLower, ['perempuan', 'p', 'female', 'perempuan'])) {
                        $normalizedGender = 'Perempuan';
                    } else {
                        $rowErrors[] = 'Jenis Kelamin (format tidak valid: ' . $gender . ', dikosongkan)';
                    }
                }

                // Parse angkatan
                $parsedAngkatan = null;
                if (!empty($angkatan)) {
                    if (is_numeric($angkatan)) {
                        $parsedAngkatan = (int) $angkatan;
                    } else {
                        $rowErrors[] = 'Tahun Masuk (format tidak valid: ' . $angkatan . ', dikosongkan)';
                    }
                }

                // Create or update user
                try {
                    User::updateOrCreate(
                        ['nisn' => $nisn], // Search criteria
                        [
                            'name' => $name,
                            'kelas_id' => $kelas?->id,
                            'angkatan' => $parsedAngkatan,
                            'birth_date' => $parsedBirthDate,
                            'tempat_lahir' => $birthPlace ?: null,
                            'gender' => $normalizedGender,
                            'guru_wali_id' => $guruWali?->id,
                            'email' => null,
                            // Only set password for new users
                            'password' => $isUpdate ? $existingUser->password : Hash::make('siswa'),
                        ]
                    );

                    if ($isUpdate) {
                        $imported++;
                    } else {
                        $imported++;
                    }

                    // Add error info for this student if any fields failed
                    if (!empty($rowErrors)) {
                        $errors[] = $name . ' (NISN: ' . $nisn . ') - ' . implode(', ', $rowErrors);
                    }
                } catch (\Exception $e) {
                    $errors[] = $name . ' (NISN: ' . $nisn . ') - Gagal simpan: ' . $e->getMessage() . ' - Data diabaikan';
                }
            }

            // Build summary message
            $message = 'Import berhasil! ' . $imported . ' data siswa diproses.';
            if (!empty($errors)) {
                $message .= '<br><br><strong>Rincian data yang bermasalah:</strong><br>' . implode('<br>', $errors);
            }

            return redirect()->route('admin.siswa')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa')->with('error', 'Import gagal: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
        }
    }

    public function getData($id)
    {
        $user = User::whereNotNull('nisn')->findOrFail($id);
        return response()->json([
            'id' => $user->id,
            'nisn' => $user->nisn,
            'name' => $user->name,
            'kelas_id' => $user->kelas_id,
            'angkatan' => $user->angkatan,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date ? $user->birth_date->format('Y-m-d') : null,
            'tempat_lahir' => $user->tempat_lahir,
            'guru_wali_id' => $user->guru_wali_id,
            'no_telepon' => $user->no_telepon,
            'email' => $user->email,
        ]);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_siswa.xlsx');

        // Jika file template tidak ada, generate otomatis
        if (!file_exists($filePath)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['No', 'Nama Siswa', 'NISN', 'Kelas', 'Jenis Kelamin (L/P)', 'Tanggal Lahir (YYYY-MM-DD)', 'Tempat Lahir', 'No Telepon', 'Email'];
            $sheet->fromArray($headers, null, 'A1');

            $sampleData = ['1', 'Contoh Nama Siswa', '1234567890', 'X RPL 1', 'L', '2008-05-15', 'Jakarta', '08123456789', 'siswa@email.com'];
            $sheet->fromArray($sampleData, null, 'A2');

            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ];
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return response()->download($filePath, 'Template_Import_Siswa.xlsx');
    }

    public function export(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $format = $request->get('format', 'tampilan');
        $kelasId = $request->get('kelas_id');
        $angkatan = $request->get('angkatan');

        $query = User::whereNotNull('nisn')
            ->with(['kelas', 'waliKelas.user'])
            ->orderBy('kelas_id')
            ->orderBy('name');

        switch ($filter) {
            case 'tidak_lengkap':
                $query->where(function ($q) {
                    $q->whereNull('nisn')->orWhere('nisn', '')
                        ->orWhereNull('name')->orWhere('name', '')
                        ->orWhereNull('kelas_id')
                        ->orWhereNull('gender')->orWhere('gender', '')
                        ->orWhereNull('birth_date')
                        ->orWhereNull('tempat_lahir')->orWhere('tempat_lahir', '')
                        ->orWhereNull('no_telepon')->orWhere('no_telepon', '');
                });
                $filterLabel = 'Data Tidak Lengkap';
                break;

            case 'kelas':
                $query->where('kelas_id', $kelasId);
                $kelas = \App\Models\Kelas::find($kelasId);
                $filterLabel = 'Kelas ' . ($kelas->nama_kelas ?? $kelasId);
                break;

            case 'angkatan':
                $query->where('angkatan', $angkatan);
                $filterLabel = 'Angkatan ' . $angkatan;
                break;

            default:
                $filterLabel = 'Semua Siswa';
                break;
        }

        $siswaList = $query->get();

        return $format === 'import'
            ? $this->exportSiswaForImport($siswaList, $filterLabel)
            : $this->exportSiswaTampilan($siswaList, $filterLabel);
    }

    private function exportSiswaTampilan($siswaList, string $filterLabel)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // ── Kop ──────────────────────────────────────────────────────────────
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'SMK NEGERI 5 TELKOM BANDA ACEH');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'DATA SISWA — ' . strtoupper($filterLabel) . ' | TAHUN AJARAN ' . date('Y') . '/' . (date('Y') + 1));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '374151']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Dicetak pada: ' . now()->translatedFormat('d F Y, H:i') . ' WIB  |  Total: ' . $siswaList->count() . ' siswa');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => 'center'],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(14);
        $sheet->getRowDimension(4)->setRowHeight(6);

        // ── Header ────────────────────────────────────────────────────────────
        $headers = ['No', 'NISN', 'Nama Lengkap', 'Jenis Kelamin', 'Kelas', 'Angkatan', 'Tanggal Lahir', 'Guru Wali', 'Kelengkapan'];
        foreach ($headers as $i => $label) {
            $sheet->setCellValue(chr(65 + $i) . '5', $label);
        }

        $sheet->getStyle('A5:I5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'BFDBFE']]],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(22);

        // ── Data ──────────────────────────────────────────────────────────────
        $row = 6;
        foreach ($siswaList as $i => $s) {
            $bgColor = $i % 2 === 0 ? 'EFF6FF' : 'FFFFFF';

            $missing = [];
            if (empty($s->nisn))
                $missing[] = 'NISN';
            if (empty($s->gender))
                $missing[] = 'JK';
            if (empty($s->kelas_id))
                $missing[] = 'Kelas';
            if (empty($s->birth_date))
                $missing[] = 'Tgl Lahir';
            if (empty($s->tempat_lahir))
                $missing[] = 'Tempat Lahir';
            if (empty($s->no_telepon))
                $missing[] = 'Telepon';
            $kelengkapan = empty($missing) ? '✓ Lengkap' : '✗ Kurang: ' . implode(', ', $missing);
            $kelColor = empty($missing) ? '15803D' : 'DC2626';

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $s->nisn ?? '-');
            $sheet->setCellValue("C{$row}", $s->name ?? '-');
            $sheet->setCellValue("D{$row}", $s->gender ?? '-');
            $sheet->setCellValue("E{$row}", $s->kelas?->nama_kelas ?? '-');
            $sheet->setCellValue("F{$row}", $s->angkatan ?? '-');
            $sheet->setCellValue("G{$row}", $s->birth_date ? \Carbon\Carbon::parse($s->birth_date)->format('d/m/Y') : '-');
            $sheet->setCellValue("H{$row}", $s->waliKelas?->user?->name ?? '-');
            $sheet->setCellValue("I{$row}", $kelengkapan);

            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['vertical' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DBEAFE']]],
            ]);

            foreach (['A', 'D', 'E', 'F', 'G'] as $col) {
                $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal('center');
            }
            $sheet->getStyle("I{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($kelColor));
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
        }

        // ── Total ─────────────────────────────────────────────────────────────
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->setCellValue("A{$row}", 'Total');
        $sheet->setCellValue("C{$row}", $siswaList->count() . ' siswa');
        $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '1E3A5F']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DBEAFE']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '93C5FD']]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(20);

        // ── Lebar kolom ───────────────────────────────────────────────────────
        $widths = ['A' => 5, 'B' => 14, 'C' => 30, 'D' => 14, 'E' => 16, 'F' => 10, 'G' => 14, 'H' => 26, 'I' => 28];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->freezePane('A6');
        $sheet->setAutoFilter('A5:I' . ($row - 1));
        $sheet->getStyle("A5:I{$row}")->applyFromArray([
            'borders' => ['outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '1D4ED8']]],
        ]);

        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)->setFitToHeight(0);

        $filename = 'Data_Siswa_' . str_replace(' ', '_', $filterLabel) . '_' . now()->format('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    private function exportSiswaForImport($siswaList, string $filterLabel)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Siswa');

        // ── Kop 2 baris (sesuai format import) ───────────────────────────────
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'SMK NEGERI 5 TELKOM BANDA ACEH — DATA SISWA (Format Re-Import) — ' . strtoupper($filterLabel));
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(18);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue(
            'A2',
            'Dicetak: ' . now()->translatedFormat('d F Y') .
            '  |  Total: ' . $siswaList->count() . ' siswa' .
            '  |  Edit data lalu import kembali menggunakan tombol "Import Excel"'
        );
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => 'center'],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(14);

        // ── Header baris 3 (sesuai urutan kolom import) ───────────────────────
        // A=NISN, B=Nama, C=Kelas, D=Angkatan, E=Tgl Lahir, F=Tempat Lahir, G=JK, H=Guru Wali
        $headers = [
            'NISN *',
            'Nama Lengkap *',
            'Kelas',
            'Tahun Masuk (Angkatan)',
            'Tanggal Lahir (dd/mm/yyyy)',
            'Tempat Lahir',
            'Jenis Kelamin (Laki-laki/Perempuan)',
            'Guru Wali',
        ];

        foreach ($headers as $i => $label) {
            $sheet->setCellValue(chr(65 + $i) . '3', $label);
        }

        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // ── Data mulai baris 4 ────────────────────────────────────────────────
        $row = 4;
        foreach ($siswaList as $i => $s) {
            $bgColor = $i % 2 === 0 ? 'F0F9FF' : 'FFFFFF';

            $sheet->setCellValue("A{$row}", $s->nisn ?? '');
            $sheet->setCellValue("B{$row}", $s->name ?? '');
            $sheet->setCellValue("C{$row}", $s->kelas?->nama_kelas ?? '');
            $sheet->setCellValue("D{$row}", $s->angkatan ?? '');
            $sheet->setCellValue(
                "E{$row}",
                $s->birth_date
                ? \Carbon\Carbon::parse($s->birth_date)->format('d/m/Y')
                : ''
            );
            $sheet->setCellValue("F{$row}", $s->tempat_lahir ?? '');
            $sheet->setCellValue("G{$row}", $s->gender ?? '');
            $sheet->setCellValue("H{$row}", $s->waliKelas?->user?->name ?? '');

            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['vertical' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(17);
            $row++;
        }

        // ── Lebar kolom ───────────────────────────────────────────────────────
        $widths = ['A' => 14, 'B' => 28, 'C' => 16, 'D' => 12, 'E' => 22, 'F' => 18, 'G' => 28, 'H' => 26];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->freezePane('A4');
        $sheet->setAutoFilter('A3:H' . ($row - 1));

        // ── Sheet panduan ─────────────────────────────────────────────────────
        $guide = $spreadsheet->createSheet();
        $guide->setTitle('Panduan');

        $panduan = [
            ['PANDUAN RE-IMPORT DATA SISWA', ''],
            ['', ''],
            ['Kolom', 'Keterangan'],
            ['A - NISN', 'WAJIB — Nomor Induk Siswa Nasional (10 digit)'],
            ['B - Nama Lengkap', 'WAJIB — nama lengkap siswa'],
            ['C - Kelas', 'Nama kelas harus sama persis dengan yang ada di sistem (contoh: X PPLG 1)'],
            ['D - Tahun Masuk', 'Tahun angkatan masuk (contoh: 2024)'],
            ['E - Tanggal Lahir', 'Format: dd/mm/yyyy  contoh: 15/08/2008'],
            ['F - Tempat Lahir', 'Nama kota/kabupaten tempat lahir'],
            ['G - Jenis Kelamin', 'Isi dengan: Laki-laki atau Perempuan'],
            ['H - Guru Wali', 'Nama guru wali harus mirip dengan yang ada di sistem'],
            ['', ''],
            ['CATATAN PENTING', ''],
            ['1.', 'Jangan ubah baris 1 dan 2 (kop surat)'],
            ['2.', 'Jangan ubah baris 3 (header kolom)'],
            ['3.', 'Data dimulai dari baris 4'],
            ['4.', 'Jika NISN sudah ada di sistem, data akan diperbarui (UPDATE)'],
            ['5.', 'Jika NISN baru, siswa baru akan ditambahkan dengan password default: siswa'],
            ['6.', 'Kelas dan Guru Wali yang tidak ditemukan akan dikosongkan'],
        ];

        foreach ($panduan as $i => $rowData) {
            $guide->setCellValue('A' . ($i + 1), $rowData[0]);
            $guide->setCellValue('B' . ($i + 1), $rowData[1]);
        }

        $guide->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E3A5F']]]);
        $guide->getStyle('A3:B3')->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DBEAFE']]]);
        $guide->getStyle('A13')->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']]]);
        $guide->getColumnDimension('A')->setWidth(22);
        $guide->getColumnDimension('B')->setWidth(60);

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'ReImport_Siswa_' . str_replace(' ', '_', $filterLabel) . '_' . now()->format('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
