<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;

class SendDailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi harian ke user';

    /**
     * Execute the console command.
     */
    public function handle(Messaging $messaging)
    {
        Log::info('Scheduler notif jalan');

        $now = now()->format('H:i');

        $field = null;
        $namaSholat = null;

        if ($now >= '05:30' && $now < '06:30') {
            $field = 'sholat_subuh';
            $namaSholat = 'Subuh';
        } elseif (date('l', strtotime($now)) === 'Friday' && $now >= '13:00' && $now < '15:00') {
            $field = 'sholat_jumat';
            $namaSholat = 'Jumat';
        } elseif ($now >= '13:00' && $now < '14:00') {
            $field = 'sholat_dzuhur';
            $namaSholat = 'Dzuhur';
        } elseif ($now >= '16:00' && $now < '17:00') {
            $field = 'sholat_ashar';
            $namaSholat = 'Ashar';
        } elseif ($now >= '19:00' && $now < '20:00') {
            $field = 'sholat_maghrib';
            $namaSholat = 'Maghrib';
        } elseif ($now >= '20:00' && $now < '21:00') {
            $field = 'sholat_isya';
            $namaSholat = 'Isya';
        }

        // kalau bukan jam notif → stop
        if (!$field) {
            Log::info('Bukan waktu notifikasi');
            return;
        }

        $tokens = DB::table('users')
            ->join('fcm_tokens', 'users.id', '=', 'fcm_tokens.user_id')
            ->leftJoin('kebiasaan_harian', function ($join) {
                $join->on('users.id', '=', 'kebiasaan_harian.user_id')
                    ->whereDate('kebiasaan_harian.tanggal', now()->toDateString());
            })
            ->whereNotNull('users.nisn') // hanya murid
            ->where('users.is_alumni', 0)
            ->where(function ($query) use ($field) {
                $query->whereNull('kebiasaan_harian.id')
                    ->orWhere("kebiasaan_harian.$field", 0);
            })
            ->distinct()
            ->pluck('fcm_tokens.token');

        if ($tokens->isEmpty()) {
            Log::warning('Tidak ada token FCM');
            return;
        }

        foreach ($tokens as $token) {
            try {
                $messaging->send([
                    'token' => $token,
                    'data' => [
                        'title' => '⏰ Waktu ' . $namaSholat,
                        'body'  => 'Jangan lupa isi sholat ' . $namaSholat,
                        'url'   => '/student/dashboard'
                    ],
                ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        Log::info("Notif $namaSholat dikirim");
    }
}
