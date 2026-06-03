<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KebiasaanHarian;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat rentang tanggal dari 30 hari lalu sampai hari ini
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }

        // 2. Buat 400 User Siswa sekaligus
        User::factory()->count(400)->create()->each(function ($user) use ($dates) {

            // Siapkan penampung data massal untuk satu siswa
            $dataSiswa = [];

            // 3. Looping setiap tanggal untuk siswa tersebut
            foreach ($dates as $tanggal) {
                // Mengambil template data acak dari Factory
                $templateData = KebiasaanHarian::factory()->make([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                ])->toArray();

                // Encode field array/JSON secara manual karena menggunakan insert massal
                $templateData['jenis_olahraga'] = isset($templateData['jenis_olahraga']) ? json_encode($templateData['jenis_olahraga']) : null;
                $templateData['bersama'] = isset($templateData['bersama']) ? json_encode($templateData['bersama']) : null;

                $dataSiswa[] = $templateData;
            }

            // 4. Masukkan 31 data sekaligus per siswa agar query jauh lebih cepat (Bulk Insert)
            KebiasaanHarian::insert($dataSiswa);

        });
    }
}
