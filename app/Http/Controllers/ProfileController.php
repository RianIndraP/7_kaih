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
        // Decode teman_terbaik_json from string to array
        if ($request->has('teman_terbaik_json') && is_string($request->teman_terbaik_json)) {
            $request->merge([
                'teman_terbaik_json' => json_decode($request->teman_terbaik_json, true) ?? []
            ]);
        }

        // Validate the request data
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
        ], [
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 5MB.',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();

            // 2. Logika Update atau Insert Foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama dari storage jika user sudah punya foto sebelumnya
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                // Simpan foto baru
                $path = $request->file('foto')->store('profile_photos', 'public');

                // Masukkan path ke array data yang akan diupdate
                $validated['foto'] = $path;
            }

            // Find kelas_id based on nama_kelas
            $kelas = Kelas::where('nama_kelas', $validated['kelas'])->first();
            $kelasId = $kelas ? $kelas->id : null;

            // Update user profile data
            /** @var \App\Models\User $user */
            $user->update([
                'foto'      => $validated['foto'] ?? $user->foto,
                'hobi' => $validated['hobi'] ?? null,
                'cita_cita' => $validated['cita'] ?? null,
                'teman_terbaik' => $validated['teman'] ?? null,
                'teman_terbaik_json' => $validated['teman_terbaik_json'] ?? null,
                'makanan_kesukaan' => $validated['makan'] ?? null,
                'warna_kesukaan' => $validated['warna'] ?? null,
                'no_telepon' => $validated['hp'],
                'no_ortu' => $validated['ortu'],
                'email' => $validated['email'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'alamat' => $validated['alamat'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil disimpan!',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan profil: ' . $e->getMessage()
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