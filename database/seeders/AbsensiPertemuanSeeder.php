<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbsensiSiswa;
use App\Models\AbsensiPertemuan;

class AbsensiPertemuanSeeder extends Seeder
{
    public function run(): void
    {
        // ambil pertemuan unik dari absensi_siswa
        $pertemuanList = AbsensiSiswa::select(
            'guru_id',
            'pertemuan_ke',
            'tanggal_mulai',
            'tanggal_selesai'
        )
            ->distinct()
            ->get();

        foreach ($pertemuanList as $p) {

            AbsensiPertemuan::updateOrCreate(
                [
                    'guru_id' => $p->guru_id,
                    'pertemuan_ke' => $p->pertemuan_ke,
                    'tanggal_mulai' => $p->tanggal_mulai,
                ],
                [
                    'tanggal_selesai' => $p->tanggal_selesai,
                    'foto_dokumentasi' => 'dokumentasi/sample.jpg',
                ]
            );
        }

        $this->command->info('Seeder AbsensiPertemuan selesai!');
    }
}
