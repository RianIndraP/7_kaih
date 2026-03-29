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
            'birth_date' => $validated['birth_date'],
            'guru_wali_id' => $validated['guru_wali_id'],
            'gender' => $validated['gender'],
            'email' => $validated['nisn'] . '@student.7kaih.sch.id',
            'password' => Hash::make('siswa'),
        ]);

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'required|string|unique:users,nisn,' . $id,
            'name' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'angkatan' => 'required|integer|min:2000|max:2100',
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
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil dihapus!');
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
                $nisn = trim((string)($row[0] ?? ''));
                $name = trim($row[1] ?? '');
                $kelasName = trim($row[2] ?? '');
                $angkatan = trim($row[3] ?? '');
                $birthDate = trim($row[4] ?? '');
                $gender = trim($row[5] ?? '');
                $guruWaliName = trim($row[6] ?? '');
                
                // Row errors for this student
                $rowErrors = [];
                
                // Skip if required fields (NISN and Nama) are empty
                if (empty($nisn) || empty($name)) {
                    $errors[] = 'Baris ' . ($index + 4) . ': NISN dan Nama wajib diisi - Data diabaikan';
                    continue;
                }

                // Check if NISN already exists
                $existingUser = User::where('nisn', $nisn)->first();
                if ($existingUser) {
                    $errors[] = $name . ' (NISN: ' . $nisn . ') - NISN sudah ada di database - Data diabaikan';
                    continue;
                }

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
                    try {
                        // Check if it's an Excel date number
                        if (is_numeric($birthDate)) {
                            $parsedBirthDate = Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
                        } else {
                            // Try Indonesian format first (d/m/Y), then standard format
                            $parsedBirthDate = \Carbon\Carbon::createFromFormat('d/m/Y', $birthDate)->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        try {
                            // Fallback to standard parse
                            $parsedBirthDate = \Carbon\Carbon::parse($birthDate)->format('Y-m-d');
                        } catch (\Exception $e2) {
                            $rowErrors[] = 'Tanggal Lahir (format tidak valid: ' . $birthDate . ', dikosongkan)';
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

                // Parse angkatan
                $parsedAngkatan = null;
                if (!empty($angkatan)) {
                    if (is_numeric($angkatan)) {
                        $parsedAngkatan = (int) $angkatan;
                    } else {
                        $rowErrors[] = 'Tahun Masuk (format tidak valid: ' . $angkatan . ', dikosongkan)';
                    }
                }

                // Create user
                try {
                    User::create([
                        'nisn' => $nisn,
                        'name' => $name,
                        'kelas_id' => $kelas?->id,
                        'angkatan' => $parsedAngkatan,
                        'birth_date' => $parsedBirthDate,
                        'gender' => $normalizedGender,
                        'guru_wali_id' => $guruWali?->id,
                        'email' => $nisn . '@student.7kaih.sch.id',
                        'password' => Hash::make('siswa'),
                    ]);
                    $imported++;
                    
                    // Add error info for this student if any fields failed
                    if (!empty($rowErrors)) {
                        $errors[] = $name . ' (NISN: ' . $nisn . ') - ' . implode(', ', $rowErrors);
                    }
                } catch (\Exception $e) {
                    $errors[] = $name . ' (NISN: ' . $nisn . ') - Gagal simpan: ' . $e->getMessage() . ' - Data diabaikan';
                }
            }

            // Build summary message
            $message = 'Import berhasil! ' . $imported . ' siswa ditambahkan.';
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
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
