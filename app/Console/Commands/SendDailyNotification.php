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

        $tokens = DB::table('fcm_tokens')->pluck('token');

        if ($tokens->isEmpty()) {
            Log::warning('Tidak ada token FCM');
            return;
        }

        foreach ($tokens as $token) {
            try {
                $messaging->send([
                    'token' => $token,
                    'data' => [
                        'title' => 'Reminder Harian 📅',
                        'body'  => 'Jangan lupa isi kebiasaan harian kamu!',
                        'url'   => '/student/dashboard'
                    ],
                ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        $this->info('Notifikasi berhasil dikirim!');
    }
}
