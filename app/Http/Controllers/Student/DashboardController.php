<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\KebiasaanHarian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use function Illuminate\Support\now;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user    = Auth::user();
        $tanggal = now('Asia/Jakarta')->toDateString();

        // Cek apakah ada record kebiasaan hari ini
        $kebiasaan = KebiasaanHarian::where('user_id', $user->id)
            ->where('tanggal', $tanggal)
            ->first();

        // Sudah mengisi kebiasaan hari ini jika record ada
        $kebiasaanHariIni = $kebiasaan !== null && $kebiasaan->hitungSelesai() > 6;

        // Data checklist 7 kebiasaan
        $kebiasaanData = $kebiasaan
            ? $kebiasaan->statusChecklist()
            : [
                'bangun_pagi'   => false,
                'beribadah'     => false,
                'berolahraga'   => false,
                'makan_sehat'   => false,
                'gemar_belajar' => false,
                'bermasyarakat' => false,
                'tidur_cepat'   => false,
            ];

        // Persentase kelengkapan
        $persen = $kebiasaan ? $kebiasaan->persentaseSelesai() : 0;

        return view('dashboard.student', compact(
            'user',
            'kebiasaan',
            'kebiasaanHariIni',
            'kebiasaanData',
            'persen',
            'tanggal' // Tambahkan tanggal ke view
        ));
    }
}
