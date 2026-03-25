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
        $siswa = User::where('role', 'siswa')->get();

        foreach ($siswa as $user) {
            // Generate data untuk 30 hari terakhir
            for ($i = 29; $i >= 0; $i--) {
                $tanggal = Carbon::today()->subDays($i)->toDateString();

                // Skip jika sudah ada (idempotent)
                if (KebiasaanHarian::where('user_id', $user->id)->where('tanggal', $tanggal)->exists()) {
                    continue;
                }

                KebiasaanHarian::factory()->create([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                ]);
            }
        }

        $this->command->info('Seeded kebiasaan harian for ' . $siswa->count() . ' students × 30 days.');
    }
}
