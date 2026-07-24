<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ManajemenGuruController extends Controller
{
    public function index(Request $request): View
    {
        $query = Guru::with('user');

        // Search by name, NIP, or NIK from users table
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $guru = $query->latest()->paginate(10)->withQueryString();

        return view('admin.manajemen-guru', compact('guru'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|unique:users,nip',
            'nik' => 'nullable|string|unique:users,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            // Guru specific fields
            'status_pegawai' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
        ]);

        // Create user account for guru
        $user = User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'gender' => $validated['gender'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'no_telepon' => null,
            'email' => null,
            'password' => Hash::make('guru'),
        ]);

        // Create guru record with specific fields only
        Guru::create([
            'user_id' => $user->id,
            'status_pegawai' => $validated['status_pegawai'] ?? null,
            'unit_kerja' => $validated['unit_kerja'] ?? null,
        ]);

        return redirect()->route('admin.guru')->with('success', 'Guru berhasil ditambahkan!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $guru = Guru::findOrFail($id);
        $user = User::findOrFail($guru->user_id);

        $validated = $request->validate([
            'nip' => 'nullable|string|unique:users,nip,' . $user->id,
            'nik' => 'nullable|string|unique:users,nik,' . $user->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'no_telepon' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            // Guru specific fields
            'status_pegawai' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->nip = $validated['nip'] ?? null;
        $user->nik = $validated['nik'] ?? null;
        $user->gender = $validated['gender'];
        $user->tempat_lahir = $validated['tempat_lahir'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;
        $user->no_telepon = $validated['no_telepon'] ?? null;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update guru specific fields only
        $guru->update([
            'status_pegawai' => $validated['status_pegawai'] ?? null,
            'unit_kerja' => $validated['unit_kerja'] ?? null,
        ]);

        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil diupdate!');
    }

    public function destroy($id): RedirectResponse
    {
        $guru = Guru::findOrFail($id);
        $user = User::findOrFail($guru->user_id);

        // Delete guru first (due to foreign key)
        $guru->delete();
        // Then delete user
        $user->delete();

        return redirect()->route('admin.guru')->with('success', 'Guru berhasil dihapus!');
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
                    return redirect()->route('admin.guru')->with('error', 'Gagal membaca file');
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
                    return redirect()->route('admin.guru')->with('error', $errorMsg);
                }
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
            }

            $imported = 0;
            $errors = [];

            // Log raw data for debugging
            Log::debug('Import guru - raw rows count: ' . count($rows));
            if (!empty($rows)) {
                Log::debug('Import guru - first 5 rows:', array_slice($rows, 0, 5));
            }

            // Skip first 2 rows (kop surat), row 3 is header
            if (count($rows) < 3) {
                return redirect()->route('admin.guru')->with('error', 'Format file tidak sesuai. File minimal harus memiliki 3 baris (2 baris kop + 1 baris header + data)');
            }

            $row1 = array_shift($rows); // Skip row 1 (kop)
            $row2 = array_shift($rows); // Skip row 2 (kop)
            $header = array_shift($rows); // Row 3 is header

            Log::debug('Import guru - header row:', $header ?? []);
            Log::debug('Import guru - data rows remaining: ' . count($rows));

            foreach ($rows as $index => $row) {
                Log::debug('Processing row ' . ($index + 4), $row);

                // Skip empty rows (check all columns up to 7)
                $hasData = false;
                for ($i = 0; $i < 7; $i++) {
                    if (!empty($row[$i] ?? '')) {
                        $hasData = true;
                        break;
                    }
                }
                if (!$hasData) {
                    Log::debug('Skipping empty row ' . ($index + 4));
                    continue;
                }

                // Map Excel columns
                $nip = trim((string) ($row[0] ?? ''));
                $nik = trim((string) ($row[1] ?? ''));
                $name = trim($row[2] ?? '');
                $gender = trim($row[3] ?? '');
                $birthDateRaw = $row[4] ?? '';
                $statusPegawai = trim($row[5] ?? '');
                $unitKerja = trim($row[6] ?? '');

                // Debug raw birth date value
                Log::debug('Birth date raw value', [
                    'row' => $index + 4,
                    'value' => $birthDateRaw,
                    'type' => gettype($birthDateRaw),
                    'is_datetime' => $birthDateRaw instanceof \DateTimeInterface,
                    'is_numeric' => is_numeric($birthDateRaw),
                ]);

                // Convert birthDate to string if it's DateTime
                if ($birthDateRaw instanceof \DateTimeInterface) {
                    $birthDate = $birthDateRaw->format('d/m/Y'); // Convert back to d/m/Y for consistent parsing
                } else {
                    $birthDate = is_string($birthDateRaw) ? trim($birthDateRaw) : (string) $birthDateRaw;
                }

                // Row errors for this guru
                $rowErrors = [];

                // Skip if required field (Nama) is empty
                if (empty($name)) {
                    $errors[] = 'Baris ' . ($index + 4) . ': Nama wajib diisi - Data diabaikan';
                    Log::debug('Skipping row - empty name');
                    continue;
                }

                // Check if NIP already exists
                if (!empty($nip)) {
                    $existingNip = User::where('nip', $nip)->first();
                    if ($existingNip) {
                        $errors[] = $name . ' (NIP: ' . $nip . ') - NIP sudah ada di database - Data diabaikan';
                        continue;
                    }
                }

                // Check if NIK already exists
                if (!empty($nik)) {
                    $existingNik = User::where('nik', $nik)->first();
                    if ($existingNik) {
                        $errors[] = $name . ' (NIK: ' . $nik . ') - NIK sudah ada di database - Data diabaikan';
                        continue;
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

                Log::debug('Birth date parsing result', [
                    'row' => $index + 4,
                    'raw' => $birthDate,
                    'parsed' => $parsedBirthDate,
                ]);

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

                // Email is not auto-generated - leave empty for new accounts
                $email = null;

                // Create user and guru
                try {
                    // Create user account
                    $user = User::create([
                        'name' => $name,
                        'nip' => !empty($nip) ? $nip : null,
                        'nik' => !empty($nik) ? $nik : null,
                        'gender' => $normalizedGender,
                        'birth_date' => $parsedBirthDate,
                        'email' => $email,
                        'password' => Hash::make('guru'),
                    ]);

                    // Create guru record
                    Guru::create([
                        'user_id' => $user->id,
                        'status_pegawai' => !empty($statusPegawai) ? $statusPegawai : null,
                        'unit_kerja' => !empty($unitKerja) ? $unitKerja : null,
                    ]);

                    $imported++;

                    // Add error info for this guru if any fields failed
                    if (!empty($rowErrors)) {
                        $errors[] = $name . ' - ' . implode(', ', $rowErrors);
                    }
                } catch (\Exception $e) {
                    $errors[] = $name . ' - Gagal simpan: ' . $e->getMessage() . ' - Data diabaikan';
                }
            }

            // Build summary message
            if ($imported === 0 && empty($errors)) {
                $message = 'Tidak ada data yang diimport. Pastikan file memiliki data guru di kolom C (Nama) mulai baris ke-4.';
            } else {
                $message = 'Import berhasil! ' . $imported . ' guru ditambahkan.';
                if (!empty($errors)) {
                    $message .= '<br><br><strong>Rincian data yang bermasalah:</strong><br>' . implode('<br>', $errors);
                }
            }

            Log::debug('Import guru final result:', ['imported' => $imported, 'errors_count' => count($errors)]);
            return redirect()->route('admin.guru')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.guru')->with('error', 'Import gagal: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
        }
    }

    public function getData($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return response()->json([
            'id' => $guru->id,
            'nip' => $guru->user->nip,
            'nik' => $guru->user->nik,
            'name' => $guru->user->name,
            'gender' => $guru->user->gender,
            'tempat_lahir' => $guru->user->tempat_lahir,
            'birth_date' => $guru->user->birth_date ? $guru->user->birth_date->format('Y-m-d') : null,
            'no_telepon' => $guru->user->no_telepon,
            'status_pegawai' => $guru->status_pegawai,
            'unit_kerja' => $guru->unit_kerja,
        ]);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_guru.xlsx');

        // Jika file template tidak ada, generate otomatis
        if (!file_exists($filePath)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['No', 'Nama Guru', 'NIP', 'NIK', 'Jenis Kelamin (L/P)', 'Tanggal Lahir (YYYY-MM-DD)', 'No Telepon', 'Email', 'Status Pegawai', 'Unit Kerja'];
            $sheet->fromArray($headers, null, 'A1');

            $sampleData = ['1', 'Contoh Nama Guru', '198012122008121002', '3204123456789012', 'L', '1980-12-12', '08123456789', 'guru@email.com', 'PNS', 'SMK Negeri 5 Telkom Banda Aceh'];
            $sheet->fromArray($sampleData, null, 'A2');

            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ];
            $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return response()->download($filePath, 'Template_Import_Guru.xlsx');
    }

    public function export(Request $request)
{
    $filter = $request->get('filter', 'semua');
    $format = $request->get('format', 'tampilan'); // 'tampilan' atau 'import'

    // ── Query berdasarkan filter ──────────────────────────────────────────
    $query = Guru::with('user')->orderBy('id');

    switch ($filter) {
        case 'tanpa_nip':
            $query->whereHas('user', fn($q) => $q->whereNull('nip')->orWhere('nip', ''));
            $filterLabel = 'Tanpa NIP';
            break;

        case 'tanpa_nik':
            $query->whereHas('user', fn($q) => $q->whereNull('nik')->orWhere('nik', ''));
            $filterLabel = 'Tanpa NIK';
            break;

        case 'tidak_lengkap':
            $query->whereHas('user', function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNull('nip')->orWhere('nip', '');
                })->orWhere(function ($sub) {
                    $sub->whereNull('nik')->orWhere('nik', '');
                })->orWhere(function ($sub) {
                    $sub->whereNull('gender')->orWhere('gender', '');
                })->orWhere(function ($sub) {
                    $sub->whereNull('birth_date');
                })->orWhere(function ($sub) {
                    $sub->whereNull('no_telepon')->orWhere('no_telepon', '');
                });
            });
            $filterLabel = 'Data Tidak Lengkap';
            break;

        default:
            $filterLabel = 'Semua Guru';
            break;
    }

    $guruList = $query->get();

    // ── Format: siap re-import ────────────────────────────────────────────
    if ($format === 'import') {
        return $this->exportForImport($guruList, $filterLabel);
    }

    // ── Format: tampilan rapi ─────────────────────────────────────────────
    return $this->exportTampilan($guruList, $filterLabel, $filter);
}

private function exportTampilan($guruList, string $filterLabel, string $filter)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Guru');

    // ── Kop ──────────────────────────────────────────────────────────────
    $sheet->mergeCells('A1:H1');
    $sheet->setCellValue('A1', 'SMK NEGERI 5 TELKOM BANDA ACEH');
    $sheet->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(24);

    $sheet->mergeCells('A2:H2');
    $sheet->setCellValue('A2', 'DATA GURU — ' . strtoupper($filterLabel) . ' | TAHUN AJARAN ' . date('Y') . '/' . (date('Y') + 1));
    $sheet->getStyle('A2')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '374151']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);
    $sheet->getRowDimension(2)->setRowHeight(18);

    $sheet->mergeCells('A3:H3');
    $sheet->setCellValue('A3', 'Dicetak pada: ' . now()->translatedFormat('d F Y, H:i') . ' WIB  |  Total: ' . $guruList->count() . ' guru');
    $sheet->getStyle('A3')->applyFromArray([
        'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
        'alignment' => ['horizontal' => 'center'],
    ]);
    $sheet->getRowDimension(3)->setRowHeight(14);
    $sheet->getRowDimension(4)->setRowHeight(6);

    // ── Header tabel ──────────────────────────────────────────────────────
    $headers = ['No', 'NIP', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tanggal Lahir', 'Status Pegawai', 'Kelengkapan'];
    $cols    = ['A','B','C','D','E','F','G','H'];

    foreach ($headers as $i => $label) {
        $sheet->setCellValue($cols[$i] . '5', $label);
    }

    $sheet->getStyle('A5:H5')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'BFDBFE']]],
    ]);
    $sheet->getRowDimension(5)->setRowHeight(22);

    // ── Data ──────────────────────────────────────────────────────────────
    $row = 6;
    foreach ($guruList as $i => $g) {
        $bgColor = $i % 2 === 0 ? 'EFF6FF' : 'FFFFFF';

        // Hitung kelengkapan
        $missingFields = [];
        if (empty($g->user->nip))        $missingFields[] = 'NIP';
        if (empty($g->user->nik))        $missingFields[] = 'NIK';
        if (empty($g->user->gender))     $missingFields[] = 'JK';
        if (empty($g->user->birth_date)) $missingFields[] = 'Tgl Lahir';
        if (empty($g->user->no_telepon)) $missingFields[] = 'Telepon';
        $kelengkapan = empty($missingFields) ? '✓ Lengkap' : '✗ Kurang: ' . implode(', ', $missingFields);
        $kelengkapanColor = empty($missingFields) ? '15803D' : 'DC2626';

        $sheet->setCellValue("A{$row}", $i + 1);
        $sheet->setCellValue("B{$row}", $g->user->nip  ?? '-');
        $sheet->setCellValue("C{$row}", $g->user->nik  ?? '-');
        $sheet->setCellValue("D{$row}", $g->user->name ?? '-');
        $sheet->setCellValue("E{$row}", $g->user->gender ?? '-');
        $sheet->setCellValue("F{$row}",
            $g->user->birth_date
                ? \Carbon\Carbon::parse($g->user->birth_date)->format('d/m/Y')
                : '-'
        );
        $sheet->setCellValue("G{$row}", $g->status_pegawai ?? '-');
        $sheet->setCellValue("H{$row}", $kelengkapan);

        $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
            'fill'    => ['fillType' => 'solid', 'startColor' => ['rgb' => $bgColor]],
            'alignment' => ['vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DBEAFE']]],
        ]);

        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("H{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($kelengkapanColor));

        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;
    }

    // ── Baris total ───────────────────────────────────────────────────────
    $sheet->mergeCells("A{$row}:C{$row}");
    $sheet->setCellValue("A{$row}", 'Total');
    $sheet->setCellValue("D{$row}", $guruList->count() . ' guru');
    $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
        'font'    => ['bold' => true, 'color' => ['rgb' => '1E3A5F']],
        'fill'    => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DBEAFE']],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '93C5FD']]],
    ]);
    $sheet->getRowDimension($row)->setRowHeight(20);

    // ── Lebar kolom ───────────────────────────────────────────────────────
    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(22);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(32);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(28);

    $sheet->freezePane('A6');
    $sheet->setAutoFilter("A5:H" . ($row - 1));

    $sheet->getStyle("A5:H{$row}")->applyFromArray([
        'borders' => ['outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '1D4ED8']]],
    ]);

    // Print settings
    $sheet->getPageSetup()
          ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
          ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
          ->setFitToWidth(1)->setFitToHeight(0);

    $filename = 'Data_Guru_' . str_replace(' ', '_', $filterLabel) . '_' . now()->format('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
    exit;
}

private function exportForImport($guruList, string $filterLabel)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Import Guru');

    // ── Kop 2 baris (sesuai format import yang sudah ada) ────────────────
    $sheet->mergeCells('A1:G1');
    $sheet->setCellValue('A1', 'SMK NEGERI 5 TELKOM BANDA ACEH — DATA GURU (Format Re-Import)');
    $sheet->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E3A5F']],
        'alignment' => ['horizontal' => 'center'],
    ]);

    $sheet->mergeCells('A2:G2');
    $sheet->setCellValue('A2',
        'Dicetak: ' . now()->translatedFormat('d F Y') .
        ' | Edit data lalu import kembali menggunakan tombol "Import Excel"'
    );
    $sheet->getStyle('A2')->applyFromArray([
        'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
        'alignment' => ['horizontal' => 'center'],
    ]);

    // ── Baris 3: header (sesuai urutan kolom di controller import) ────────
    $headers = ['NIP', 'NIK', 'Nama Lengkap *', 'Jenis Kelamin', 'Tanggal Lahir (dd/mm/yyyy)', 'Status Pegawai', 'Unit Kerja'];
    foreach ($headers as $i => $label) {
        $col = chr(65 + $i); // A, B, C, ...
        $sheet->setCellValue($col . '3', $label);
    }

    $sheet->getStyle('A3:G3')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    ]);
    $sheet->getRowDimension(3)->setRowHeight(20);

    // ── Data mulai baris 4 ────────────────────────────────────────────────
    $row = 4;
    foreach ($guruList as $i => $g) {
        $bgColor = $i % 2 === 0 ? 'F0F9FF' : 'FFFFFF';

        $sheet->setCellValue("A{$row}", $g->user->nip  ?? '');
        $sheet->setCellValue("B{$row}", $g->user->nik  ?? '');
        $sheet->setCellValue("C{$row}", $g->user->name ?? '');
        $sheet->setCellValue("D{$row}", $g->user->gender ?? '');
        $sheet->setCellValue("E{$row}",
            $g->user->birth_date
                ? \Carbon\Carbon::parse($g->user->birth_date)->format('d/m/Y')
                : ''
        );
        $sheet->setCellValue("F{$row}", $g->status_pegawai ?? '');
        $sheet->setCellValue("G{$row}", $g->unit_kerja    ?? '');

        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
            'fill'    => ['fillType' => 'solid', 'startColor' => ['rgb' => $bgColor]],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            'alignment' => ['vertical' => 'center'],
        ]);

        $sheet->getRowDimension($row)->setRowHeight(17);
        $row++;
    }

    // ── Lebar kolom ───────────────────────────────────────────────────────
    $sheet->getColumnDimension('A')->setWidth(22);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(22);
    $sheet->getColumnDimension('F')->setWidth(18);
    $sheet->getColumnDimension('G')->setWidth(24);

    $sheet->freezePane('A4');
    $sheet->setAutoFilter('A3:G' . ($row - 1));

    // ── Sheet kedua: panduan ──────────────────────────────────────────────
    $guide = $spreadsheet->createSheet();
    $guide->setTitle('Panduan');

    $panduan = [
        ['PANDUAN RE-IMPORT DATA GURU', ''],
        ['', ''],
        ['Kolom', 'Keterangan'],
        ['A - NIP', 'Nomor Induk Pegawai (boleh kosong jika tidak ada)'],
        ['B - NIK', 'Nomor Induk Kependudukan (boleh kosong jika tidak ada)'],
        ['C - Nama Lengkap', 'WAJIB DIISI — nama lengkap guru'],
        ['D - Jenis Kelamin', 'Isi dengan: Laki-laki atau Perempuan'],
        ['E - Tanggal Lahir', 'Format: dd/mm/yyyy  contoh: 15/08/1985'],
        ['F - Status Pegawai', 'PNS, PPPK, Honorer, atau Kepala Sekolah'],
        ['G - Unit Kerja', 'Mata pelajaran atau bidang kerja'],
        ['', ''],
        ['CATATAN PENTING', ''],
        ['1.', 'Jangan ubah atau hapus baris 1 dan 2 (kop surat)'],
        ['2.', 'Jangan ubah baris 3 (header kolom)'],
        ['3.', 'Data dimulai dari baris 4'],
        ['4.', 'Guru dengan NIP/NIK yang sudah ada di sistem akan dilewati'],
        ['5.', 'Password default guru yang baru: guru'],
    ];

    foreach ($panduan as $i => $rowData) {
        $guide->setCellValue('A' . ($i + 1), $rowData[0]);
        $guide->setCellValue('B' . ($i + 1), $rowData[1]);
    }

    $guide->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E3A5F']]]);
    $guide->getStyle('A3:B3')->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DBEAFE']]]);
    $guide->getStyle('A12')->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']]]);
    $guide->getColumnDimension('A')->setWidth(22);
    $guide->getColumnDimension('B')->setWidth(55);

    // ── Kembali ke sheet pertama ──────────────────────────────────────────
    $spreadsheet->setActiveSheetIndex(0);

    $filename = 'ReImport_Data_Guru_' . now()->format('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
    exit;
}
}
