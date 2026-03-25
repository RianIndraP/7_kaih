<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Update data user
        $user->update([
            'name'          => $request->nama ?? $user->name,
            'email'         => $request->email ?? $user->email,
            'tempat_lahir'  => $request->tempat_lahir ?? $user->tempat_lahir,
            'birth_date'    => $request->tanggal_lahir ?? $user->birth_date,
            'alamat'        => $request->alamat ?? $user->alamat,
            'latitude'      => $request->latitude ?? $user->latitude,
            'longitude'     => $request->longitude ?? $user->longitude,
        ]);

        // Update data guru jika ada
        if ($guru) {
            $guru->update([
                'status_pegawai' => $request->status_guru ?? $guru->status_pegawai,
                'jenis_kelamin'  => $request->gender ?? $guru->jenis_kelamin,
                'unit_kerja'     => $request->unit_kerja ?? $guru->unit_kerja,
                'no_telepon'     => $request->hp ?? $guru->no_telepon,
                'email_pribadi'  => $request->email ?? $guru->email_pribadi,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil guru berhasil disimpan.',
        ]);
    }
}