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
                $nip = trim((string)($row[0] ?? ''));
                $nik = trim((string)($row[1] ?? ''));
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
                    $birthDate = is_string($birthDateRaw) ? trim($birthDateRaw) : (string)$birthDateRaw;
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
                        if ((int)$parts[1] > 12) {
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
                        if ((int)$parts[1] > 12) {
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
}
