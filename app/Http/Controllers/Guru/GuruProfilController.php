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
            'nama' => ['nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'nip' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:50'],
            'status_guru' => ['nullable', 'string', 'max:100'],
            'status_pegawai' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = Auth::user();
        $guru = $user->guru;

        // ── Cek mode pengisian data ──────────────────────────────────────────
        $modeAktif = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru') == '1';
        $deadline = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru_deadline');
        $modeValid = $modeAktif && (!$deadline || now()->lte(\Carbon\Carbon::parse($deadline)->endOfDay()));
        $fieldsIzin = json_decode(
            \App\Models\PengaturanSistem::getValue('mode_isi_data_guru_fields', '[]'),
            true
        ) ?? [];
        $boleh = fn(string $f) => $modeValid && in_array($f, $fieldsIzin);

        // ── Foto (selalu bebas) ──────────────────────────────────────────────
        $pathFoto = $user->foto;
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $pathFoto = $request->file('foto')->store('profile_photos', 'public');
        }

        // ── Data user: field bebas ───────────────────────────────────────────
        $userData = [
            'foto' => $pathFoto,
            'email' => $request->email ?? $user->email,
            'gender' => $request->gender ?? $user->gender,
            'no_telepon' => $request->hp ?? $user->no_telepon,
            'alamat' => $request->alamat ?? $user->alamat,
            'latitude' => $request->latitude ?? $user->latitude,
            'longitude' => $request->longitude ?? $user->longitude,
        ];

        // ── Data user: field restricted ──────────────────────────────────────
        if ($boleh('nama_lengkap'))
            $userData['name'] = $request->nama ?? $user->name;

        if ($boleh('tempat_lahir'))
            $userData['tempat_lahir'] = $request->tempat_lahir ?? $user->tempat_lahir;

        if ($boleh('tanggal_lahir'))
            $userData['birth_date'] = $request->tanggal_lahir ?? $user->birth_date;

        if ($boleh('nip'))
            $userData['nip'] = $request->nip ?? $user->nip;

        if ($boleh('nik'))
            $userData['nik'] = $request->nik ?? $user->nik;

        /** @var \App\Models\User $user */
        $user->update($userData);

        // ── Data guru: field bebas ───────────────────────────────────────────
        if ($guru) {
            $guruData = [
                'unit_kerja' => $request->unit_kerja ?? $guru->unit_kerja,
            ];

            // Field restricted guru
            if ($boleh('status_pegawai'))
                $guruData['status_pegawai'] = $request->status_pegawai ?? $guru->status_pegawai;

            $guru->update($guruData);
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
