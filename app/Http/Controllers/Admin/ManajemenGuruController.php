<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email|unique:users,email',
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
            'no_telepon' => $validated['no_telepon'] ?? null,
            'email' => $validated['email'],
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
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // TODO: Implement Excel import logic
        return redirect()->route('admin.guru')->with('success', 'Import data guru berhasil!');
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
