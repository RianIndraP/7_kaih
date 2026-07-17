<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show student profile page
     */
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Pass user data to the view
        return view('profil.siswa', compact('user'));
    }

    /**
     * Save student profile data
     */
    public function save(Request $request)
    {
        if ($request->has('teman_terbaik_json') && is_string($request->teman_terbaik_json)) {
            $request->merge([
                'teman_terbaik_json' => json_decode($request->teman_terbaik_json, true) ?? []
            ]);
        }

        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'hobi' => 'nullable|string|max:255',
            'cita' => 'nullable|string|max:255',
            'teman' => 'nullable|string|max:255',
            'teman_terbaik_json' => 'nullable|array',
            'teman_terbaik_json.*.nama' => 'nullable|string|max:255',
            'teman_terbaik_json.*.nomor' => 'nullable|string|max:20',
            'makan' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:50',
            'hp' => 'nullable|string|max:20',
            'ortu' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'alamat' => 'nullable|string|max:500',
            // field restricted — validasi tapi controller yang memutuskan apakah disimpan
            'd_name' => 'nullable|string|max:255',
            'd_tempat_lahir' => 'nullable|string|max:255',
            'd_birth_date' => 'nullable|date',
            'd_gender' => 'nullable|in:Laki-laki,Perempuan',
            'd_kelas_id' => 'nullable|exists:kelas,id',
            'd_nisn' => 'nullable|string|max:20',
        ]);

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // ── Cek mode pengisian data siswa ────────────────────────────────
            $modeAktif = \App\Models\PengaturanSistem::getValue('mode_isi_data_siswa') == '1';
            $deadline = \App\Models\PengaturanSistem::getValue('mode_isi_data_siswa_deadline');
            $modeValid = $modeAktif && (!$deadline || now()->lte(\Carbon\Carbon::parse($deadline)->endOfDay()));
            $fieldsIzin = json_decode(
                \App\Models\PengaturanSistem::getValue('mode_isi_data_siswa_fields', '[]'),
                true
            ) ?? [];
            $boleh = fn(string $f) => $modeValid && in_array($f, $fieldsIzin);

            // ── Foto ─────────────────────────────────────────────────────────
            $fotoPath = $user->foto;
            if ($request->hasFile('foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                $fotoPath = $request->file('foto')->store('profile_photos', 'public');
            }

            // ── Field bebas ──────────────────────────────────────────────────
            $data = [
                'foto' => $fotoPath,
                'hobi' => $validated['hobi'] ?? $user->hobi,
                'cita_cita' => $validated['cita'] ?? $user->cita_cita,
                'teman_terbaik' => $validated['teman'] ?? $user->teman_terbaik,
                'teman_terbaik_json' => $validated['teman_terbaik_json'] ?? $user->teman_terbaik_json,
                'makanan_kesukaan' => $validated['makan'] ?? $user->makanan_kesukaan,
                'warna_kesukaan' => $validated['warna'] ?? $user->warna_kesukaan,
                'no_telepon' => $validated['hp'],
                'no_ortu' => $validated['ortu'],
                'email' => $validated['email'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'alamat' => $validated['alamat'],
            ];

            // ── Field restricted — hanya simpan jika mode aktif & diizinkan ──
            if ($boleh('name') && filled($request->d_name))
                $data['name'] = $request->d_name;

            if ($boleh('tempat_lahir') && filled($request->d_tempat_lahir))
                $data['tempat_lahir'] = $request->d_tempat_lahir;

            if ($boleh('birth_date') && filled($request->d_birth_date))
                $data['birth_date'] = $request->d_birth_date;

            if ($boleh('gender') && filled($request->d_gender))
                $data['gender'] = $request->d_gender;

            if ($boleh('kelas_id') && filled($request->d_kelas_id))
                $data['kelas_id'] = $request->d_kelas_id;

            if ($boleh('nisn') && filled($request->d_nisn))
                $data['nisn'] = $request->d_nisn;

            $user->update($data);

            return response()->json(['success' => true, 'message' => 'Profil berhasil disimpan!']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deleteLocation()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update([
                'latitude' => null,
                'longitude' => null,
                'alamat' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus dari sistem!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lokasi: ' . $e->getMessage()
            ], 500);
        }
    }
}