<?php

namespace Database\Seeders;

use App\Models\PesanGuru;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesanGuruSeeder extends Seeder
{
    public function run(): void
    {
        $siswaList = User::where('role', 'siswa')->get();
        $guru      = User::where('role', 'guru')->first();

        if (! $guru || $siswaList->isEmpty()) {
            $this->command->warn('Tidak ada data guru/siswa. Buat user terlebih dahulu.');
            return;
        }

        foreach ($siswaList as $siswa) {
            // Buat 5–8 pesan per siswa
            PesanGuru::factory()
                ->count(rand(5, 8))
                ->create([
                    'guru_id'  => $guru->id,
                    'siswa_id' => $siswa->id,
                ]);
        }

        $this->command->info('Seeded pesan guru for ' . $siswaList->count() . ' students.');
    }
}
