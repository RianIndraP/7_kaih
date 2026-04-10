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
            'no_telepon' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            // Guru specific fields
            'status_pegawai' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
        ]);

        // Check if email already exists (only if email is provided)
        $email = $validated['email'] ?? null;
        if (!empty($email)) {
            $emailExists = User::where('email', $email)->first();
            if ($emailExists) {
                return redirect()->route('admin.guru')->with('error', 'Email ' . $email . ' sudah digunakan');
            }
        }

        // Create user account for guru
        $user = User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'no_telepon' => $validated['no_telepon'] ?? null,
            'email' => $email,
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
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    continue;
                }

                // Map Excel columns
                $nip = trim((string)($row[0] ?? ''));
                $nik = trim((string)($row[1] ?? ''));
                $name = trim($row[2] ?? '');
                $gender = trim($row[3] ?? '');
                $birthDate = trim($row[4] ?? '');
                $statusPegawai = trim($row[5] ?? '');
                $unitKerja = trim($row[6] ?? '');

                // Row errors for this guru
                $rowErrors = [];

                // Skip if required field (Nama) is empty
                if (empty($name)) {
                    $errors[] = 'Baris ' . ($index + 4) . ': Nama wajib diisi - Data diabaikan';
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
                    // Debug: log raw value from Excel
                    Log::debug('Guru import date debug', [
                        'name' => $name,
                        'raw_value' => $birthDate,
                        'raw_type' => gettype($birthDate),
                        'is_numeric' => is_numeric($birthDate)
                    ]);

                    $dateParsed = false;

                    // Try multiple formats
                    $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y', 'd.m.Y'];

                    foreach ($formats as $format) {
                        try {
                            $parsedBirthDate = \Carbon\Carbon::createFromFormat($format, $birthDate)->format('Y-m-d');
                            Log::debug('Date parsed successfully', [
                                'name' => $name,
                                'format' => $format,
                                'raw' => $birthDate,
                                'result' => $parsedBirthDate
                            ]);
                            $dateParsed = true;
                            break;
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    // If string parsing fails, check if it's Excel serial number
                    if (!$dateParsed && is_numeric($birthDate)) {
                        try {
                            $excelDate = Date::excelToDateTimeObject($birthDate);
                            $parsedBirthDate = $excelDate->format('Y-m-d');
                            Log::debug('Excel serial date converted', [
                                'name' => $name,
                                'serial' => $birthDate,
                                'result' => $parsedBirthDate
                            ]);
                            $dateParsed = true;
                        } catch (\Exception $e) {
                            Log::debug('Excel date conversion failed', ['name' => $name, 'serial' => $birthDate]);
                        }
                    }

                    // Last fallback to standard parse
                    if (!$dateParsed) {
                        try {
                            $parsedBirthDate = \Carbon\Carbon::parse($birthDate)->format('Y-m-d');
                            Log::debug('Date parsed with fallback', [
                                'name' => $name,
                                'raw' => $birthDate,
                                'result' => $parsedBirthDate
                            ]);
                        } catch (\Exception $e) {
                            $rowErrors[] = 'Tanggal Lahir tidak valid: ' . $birthDate;
                            Log::debug('Date parsing failed completely', ['name' => $name, 'raw' => $birthDate]);
                        }
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

                // Generate email from NIP or NIK or random
                $email = null;
                if (!empty($nip)) {
                    $email = $nip . '@guru.7kaih.sch.id';
                } elseif (!empty($nik)) {
                    $email = $nik . '@guru.7kaih.sch.id';
                } else {
                    // Generate random email based on name
                    $emailSlug = strtolower(str_replace(' ', '.', preg_replace('/[^a-zA-Z0-9\s]/', '', $name)));
                    $email = $emailSlug . rand(100, 999) . '@guru.7kaih.sch.id';
                }

                // Check if email already exists
                $emailExists = User::where('email', $email)->first();
                if ($emailExists) {
                    $email = 'guru.' . time() . rand(1000, 9999) . '@guru.7kaih.sch.id';
                }

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
            $message = 'Import berhasil! ' . $imported . ' guru ditambahkan.';
            if (!empty($errors)) {
                $message .= '<br><br><strong>Rincian data yang bermasalah:</strong><br>' . implode('<br>', $errors);
            }

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
            'birth_date' => $guru->user->birth_date,
            'no_telepon' => $guru->user->no_telepon,
            'status_pegawai' => $guru->status_pegawai,
            'unit_kerja' => $guru->unit_kerja,
        ]);
    }
}
