<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        // Validate the request data
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'nisn' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'hobi' => 'nullable|string|max:255',
            'cita' => 'nullable|string|max:255',
            'teman' => 'nullable|string|max:255',
            'makan' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:50',
            'gender' => 'required|in:Laki-laki,Perempuan',
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

            // Update user profile data
            $user->update([
                'name' => $validated['nama'],
                'foto' => $validated['foto'] ?? $user->foto,
                'nisn' => $validated['nisn'],
                'kelas_id' => $validated['kelas'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'birth_date' => $validated['tanggal_lahir'],
                'ttl' => $validated['tempat_lahir'] . ', ' . date('d F Y', strtotime($validated['tanggal_lahir'])),
                'jenis_kelamin' => $validated['jk'],
                'hobi' => $validated['hobi'],
                'cita_cita' => $validated['cita'],
                'teman_terbaik' => $validated['teman'],
                'makanan_kesukaan' => $validated['makan'],
                'warna_kesukaan' => $validated['warna'],
                'gender' => $validated['gender'],
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
}
