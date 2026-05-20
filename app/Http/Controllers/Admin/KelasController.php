<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function show($id)
    {
        $kelas = \App\Models\Kelas::with(['siswa', 'guruWali.user'])->findOrFail($id);
        $guruList = \App\Models\Guru::with('user')->get();
        
        return view('admin.kelas.detail', compact('kelas', 'guruList'));
    }

    public function updateWali(Request $request, $id)
    {
        $request->validate([
            'guru_wali_id' => 'required|exists:guru,id',
        ]);

        $kelas = \App\Models\Kelas::findOrFail($id);
        $kelas->update([
            'guru_id' => $request->guru_wali_id,
        ]);

        return redirect()->back()->with('success', 'Wali kelas berhasil diperbarui.');
    }
}
