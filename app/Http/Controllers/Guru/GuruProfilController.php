<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GuruProfilController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $guru = $user->guru;

        return view('guru.profil-guru', compact('user', 'guru'));
    }

    public function save(Request $request): JsonResponse
    {
        $request->validate([
            'nama'          => ['nullable', 'string', 'max:255'],
            'tempat_lahir'  => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status_guru'   => ['nullable', 'string', 'max:100'],
            'gender'        => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'unit_kerja'    => ['nullable', 'string', 'max:255'],
            'hp'            => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255'],
            'alamat'        => ['nullable', 'string', 'max:500'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
        ]);

        $user = Auth::user();
        $guru = $user->guru;

        $pathFoto = $user->foto; // default pakai foto lama
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru
            $pathFoto = $request->file('foto')->store('profile_photos', 'public');
        }

        // Update data user
        /** @var \App\Models\User $user */
        $user->update([
            'name'          => $request->nama ?? $user->name,
            'foto'          => $pathFoto, // Tambahkan ini
            'email'         => $request->email ?? $user->email,
            'gender'        => $request->gender ?? $user->gender,
            'no_telepon'    => $request->hp ?? $user->no_telepon,
            'tempat_lahir'  => $request->tempat_lahir ?? $user->tempat_lahir,
            'birth_date'    => $request->tanggal_lahir ?? $user->birth_date,
            'alamat'        => $request->alamat ?? $user->alamat,
            'latitude'      => $request->latitude ?? $user->latitude,
            'longitude'     => $request->longitude ?? $user->longitude,
        ]);

        // Update data guru jika ada (hanya field spesifik guru)
        if ($guru) {
            $guru->update([
                'status_pegawai' => $request->status_guru ?? $guru->status_pegawai,
                'unit_kerja'     => $request->unit_kerja ?? $guru->unit_kerja,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil guru berhasil disimpan.',
        ]);
    }
    public function deleteLocation(): JsonResponse
    {
        try {
            $user = Auth::user();

            /** @var \App\Models\User $user */
            $user->update([
                'latitude' => null,
                'longitude' => null,
                'alamat' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi guru berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lokasi.'
            ], 500);
        }
    }
}
