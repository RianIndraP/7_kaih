<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function show($id)
    {
        $kelas = \App\Models\Kelas::with(['siswa'])->findOrFail($id);
        
        // Coba cari guru wali untuk kelas ini jika relasi belum tersedia
        // Berdasarkan penamaan, mungkin menggunakan where 'kelas_wali' atau 'unit_kerja' dsb
        // Tapi setidaknya kita kembalikan $kelas untuk saat ini
        
        return view('admin.kelas.detail', compact('kelas'));
    }
}
