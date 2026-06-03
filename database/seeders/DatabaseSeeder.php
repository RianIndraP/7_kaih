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
        // 1. Ambil semua tanggal dari 30 hari ke belakang sampai hari ini
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }

        // 2. Buat 400 User Siswa sekaligus menggunakan Factory bawaan
        User::factory()->count(400)->create()->each(function ($user) use ($dates) {

            $dataSiswa = [];

            // 3. Masukkan data kebiasaan untuk setiap tanggal yang telah dijadwalkan
            foreach ($dates as $tanggal) {

                // Membuat struktur template data acak dari objek Factory
                $templateData = KebiasaanHarian::factory()->make([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal, // Override tanggal murni berformat Y-m-d
                ])->toArray();

                // PERBAIKAN FORMAT TANGGAL: Paksa string agar tidak mengirim data ISO timestamp
                $templateData['tanggal'] = $tanggal;

                // Memproses serialisasi data Array/JSON secara manual untuk query insert massal
                $templateData['jenis_olahraga'] = isset($templateData['jenis_olahraga']) ? json_encode($templateData['jenis_olahraga']) : null;
                $templateData['bersama'] = isset($templateData['bersama']) ? json_encode($templateData['bersama']) : null;

                $dataSiswa[] = $templateData;
            }

            // 4. Lakukan Bulk Insert per siswa agar eksekusi di server berjalan instan
            KebiasaanHarian::insert($dataSiswa);

        });
    }
}
