<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KebiasaanHarian;
use App\Models\PesanBantuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user  = Auth::user();
        $guru  = $user->guru ?? null;  // relasi ke tabel guru jika ada

        // Siswa wali kelas guru ini
        $siswaWaliKelas = collect();
        if ($guru) {
            $siswaWaliKelas = $guru->siswaWaliKelas;
        }

        // Total siswa asuh
        $totalSiswaAsuh = $siswaWaliKelas->count();

        // Siswa yang BELUM lengkap mengisi kebiasaan hari ini (belum 100% selesai)
        $tanggal = Carbon::today()->toDateString();
        $siswaBlumMengisi = $siswaWaliKelas->filter(function ($siswa) use ($tanggal) {
            $kebiasaan = KebiasaanHarian::where('user_id', $siswa->id)
                ->where('tanggal', $tanggal)
                ->first();
            return ! $kebiasaan || $kebiasaan->persentaseSelesai() < 100;
        });

        // Statistik pesan bantuan
        $totalPesanBantuan      = PesanBantuan::where('status', 'belum_ditangani')->count();
        $totalPesanBantuanProses = PesanBantuan::where('status', 'sedang_diproses')->count();
        $pesanBantuanTerbaru    = PesanBantuan::latest()->take(5)->get();

        $totalSiswa = User::where('nisn', '!=', null)->count();

        return view('guru.dashboard', compact(
            'user',
            'guru',
            'siswaWaliKelas',
            'siswaBlumMengisi',
            'totalSiswa',
            'totalSiswaAsuh',
            'totalPesanBantuan',
            'totalPesanBantuanProses',
            'pesanBantuanTerbaru'
        ));
    }
}