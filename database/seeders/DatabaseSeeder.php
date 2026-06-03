<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KebiasaanHarian;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 400 User Siswa tiruan sekaligus
        User::factory()->count(400)->create()->each(function ($user) {
            
            // Setiap siswa otomatis dibuatkan 1 data kebiasaan harian untuk hari ini
            KebiasaanHarian::factory()->create([
                'user_id' => $user->id,
                'tanggal' => now()->format('Y-m-d'), // Diatur khusus tanggal hari ini
            ]);
            
        });
    }
}
