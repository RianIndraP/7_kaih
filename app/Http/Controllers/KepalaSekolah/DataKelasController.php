<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class DataKelasController extends Controller
{
    public function index()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        // Get all kelas with student count
        $kelasList = Kelas::withCount('siswa')->get();

        return view('kepala-sekolah.data-kelas', compact('kelasList'));
    }

    public function show($id)
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        // Get kelas with students
        $kelas = Kelas::findOrFail($id);
        $siswaList = $kelas->siswa()->with('waliKelas.user')->get();

        return view('kepala-sekolah.data-kelas-detail', compact('kelas', 'siswaList'));
    }
}
