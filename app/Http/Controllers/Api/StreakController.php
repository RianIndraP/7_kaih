<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreakController extends Controller
{
    /**
     * Recover user's streak
     */
    public function recover(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        if (!$user->canRecoverStreak()) {
            // Get last streak date for specific error message
            $lastStreakDate = $user->last_streak_date;
            if (!$lastStreakDate) {
                $lastKebiasaan = \App\Models\KebiasaanHarian::where('user_id', $user->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                if ($lastKebiasaan) {
                    $lastStreakDate = \Carbon\Carbon::parse($lastKebiasaan->tanggal);
                }
            }
            
            if ($lastStreakDate) {
                $daysSince = $lastStreakDate->diffInDays(\Carbon\Carbon::today('Asia/Jakarta'), false);
                if ($daysSince >= 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Streak sudah terlalu lama putus (2+ hari). Tidak bisa dipulihkan lagi.'
                    ], 400);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa memulihkan streak. Maksimal 2 kali per minggu.'
            ], 400);
        }

        if ($user->recoverStreak()) {
            return response()->json([
                'success' => true,
                'message' => 'Streak berhasil dipulihkan! 🎉'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan streak'
            ], 500);
        }
    }
}
