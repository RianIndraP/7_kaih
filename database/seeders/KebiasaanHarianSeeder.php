<?php

namespace Database\Seeders;

use App\Models\KebiasaanHarian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KebiasaanHarianSeeder extends Seeder
{
    /**
     * Seed data kebiasaan untuk semua siswa selama 30 hari terakhir.
     */
    public function run(): void
    {
        $users = User::whereNotNull('nisn')->get(); // hanya murid

        foreach ($users as $user) {

            // ambil 90 hari ke belakang
            for ($i = 0; $i < 90; $i++) {

                // random: tidak semua hari diisi
                if (rand(1, 100) <= 40) continue; // 40% kosong
                $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');

                KebiasaanHarian::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'bangun_pagi' => rand(1, 100) <= 70,
                        'jam_bangun' => rand(1, 100) <= 70 ? '05:' . rand(10, 59) : '06:' . rand(0, 59),
                        'sholat_subuh' => rand(0, 1),
                        'sholat_dzuhur' => rand(0, 1),
                        'sholat_ashar' => rand(0, 1),
                        'sholat_maghrib' => rand(0, 1),
                        'sholat_isya' => rand(0, 1),

                        'baca_quran' => rand(0, 1),

                        'berolahraga' => rand(0, 1),
                        'jenis_olahraga' => json_encode([
                            'lari',
                            'sepak bola',
                            'badminton'
                        ]),

                        'makan_sehat' => rand(0, 1),
                        'makan_pagi_done' => rand(0, 1),
                        'makan_siang_done' => rand(0, 1),
                        'makan_malam_done' => rand(0, 1),

                        'gemar_belajar' => rand(1, 100) <= 60,

                        'bersama' => json_encode([
                            'keluarga',
                            'teman'
                        ]),

                        'tidur_cepat' => rand(1, 100) <= 50,
                        'jam_tidur' => rand(0, 1) ? '21:' . rand(10, 59) : null,
                    ]
                );
            }
        }
    }
}
