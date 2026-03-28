<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // TODO: Implement Excel import logic
        // For now, return success message

        return redirect()->route('admin.siswa')->with('success', 'Import data siswa berhasil!');
    }

    public function getData($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
