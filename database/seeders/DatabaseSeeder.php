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
        // 1. Ambil HANYA user yang merupakan siswa (memiliki NISN) yang sudah ada di database
        $students = User::whereNotNull('nisn')->get(); 

        if ($students->isEmpty()) {
            $this->command->error('Gagal: Tidak ditemukan data user dengan NISN (Siswa) di database Anda!');
            return;
        }

        // 2. Ambil daftar tanggal dari 30 hari ke belakang sampai hari ini
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }

        $this->command->info('Memulai pengisian data riwayat 1 bulan untuk ' . $students->count() . ' siswa...');

        // 3. Lakukan looping untuk setiap siswa yang ditemukan
        $students->each(function ($student) use ($dates) {
            
            $dataSiswa = [];

            foreach ($dates as $tanggal) {
                
                // Membuat struktur template data acak dari objek Factory
                $templateData = KebiasaanHarian::factory()->make([
                    'user_id' => $student->id,
                    'tanggal' => $tanggal, 
                ])->toArray();

                // Memastikan format tanggal aman dalam bentuk string Y-m-d (bukan ISO)
                $templateData['tanggal'] = $tanggal;

                // Mengubah format array PHP menjadi string JSON agar siap masuk via Bulk Insert
                $templateData['jenis_olahraga'] = isset($templateData['jenis_olahraga']) ? json_encode($templateData['jenis_olahraga']) : null;
                $templateData['bersama'] = isset($templateData['bersama']) ? json_encode($templateData['bersama']) : null;

                $dataSiswa[] = $templateData;
            }

            // 4. Lakukan Bulk Insert per siswa agar eksekusi memori server ringan dan instan
            KebiasaanHarian::insert($dataSiswa);
            
        });

        $this->command->info('Sukses! Seluruh data kebiasaan siswa selama 1 bulan berhasil dimasukkan.');
    }
}