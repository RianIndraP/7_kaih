<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class ManajemenKelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::withCount('siswa')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('admin.kelas', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $messages = [
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar, silakan gunakan nama lain.',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
        ];
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas'
        ], $messages);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'color_index' => rand(0, 11)
        ]);

        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $id,
            'color_index' => 'required|integer'
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'color_index' => $request->color_index
        ]);

        return back()->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Langkah 1: Cari semua siswa di kelas ini dan set kelas_id mereka jadi null
        // Pastikan relasi di model Kelas bernama 'siswa'
        $kelas->siswa()->update(['kelas_id' => null]);

        // Langkah 2: Baru hapus kelasnya
        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus. Data siswa tetap aman (tanpa kelas).');
    }
}
