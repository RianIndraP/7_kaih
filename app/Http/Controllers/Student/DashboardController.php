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

        // Update streak status (check if streak should freeze or break)
        $user->updateStreak();

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

        // Streak display
        $streakDisplay = $user->getStreakDisplay();
        $streakCount = $user->streak_count ?? 0;
        $canRecoverStreak = $user->canRecoverStreak();
        $recoveryCount = $user->streak_recovery_count ?? 0;
        $maxRecoveryPerWeek = 2;

        // Debug info for streak
        $today = now('Asia/Jakarta');
        $lastStreakDate = $user->last_streak_date;
        $daysSinceLastStreak = $lastStreakDate ? $lastStreakDate->diffInDays($today, false) : null;
        $lastKebiasaan = KebiasaanHarian::where('user_id', $user->id)->orderBy('tanggal', 'desc')->first();
        $debugInfo = [
            'today' => $today->toDateString(),
            'last_streak_date' => $lastStreakDate ? $lastStreakDate->toDateString() : null,
            'days_since_last_streak' => $daysSinceLastStreak,
            'last_kebiasaan_tanggal' => $lastKebiasaan ? $lastKebiasaan->tanggal : null,
            'streak_count' => $streakCount,
        ];

        return view('dashboard.student', compact(
            'user',
            'kebiasaan',
            'kebiasaanHariIni',
            'kebiasaanData',
            'persen',
            'tanggal',
            'streakDisplay',
            'streakCount',
            'canRecoverStreak',
            'recoveryCount',
            'maxRecoveryPerWeek',
            'debugInfo'
        ));
    }
}
