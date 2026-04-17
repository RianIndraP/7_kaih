<?php

namespace Database\Seeders;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSiswaSeeder extends Seeder
{
    public function run()
    {
        DB::table('absensi_siswa')->truncate(); // 🔥 biar tidak duplicate

        $guruId = 1;

        // ambil murid
        $muridList = User::where('guru_wali_id', $guruId)->get();

        $pertemuanList = [
            [
                'pertemuan_ke' => 5,
                'tanggal_mulai' => '2026-02-28',
                'tanggal_selesai' => '2026-03-07',
            ],
            [
                'pertemuan_ke' => 6,
                'tanggal_mulai' => '2026-03-14',
                'tanggal_selesai' => '2026-03-21',
            ],
            [
                'pertemuan_ke' => 7,
                'tanggal_mulai' => '2026-03-28',
                'tanggal_selesai' => '2026-04-04',
            ],
        ];

        foreach ($pertemuanList as $p) {

            foreach ($muridList as $murid) {

                // 🔥 semua pasti ada status (TANPA LIBUR)
                $statusList = ['hadir', 'sakit', 'izin', 'tidak_hadir'];
                $status = $statusList[array_rand($statusList)];

                // 🔥 tanggal absen WAJIB ADA
                $tanggal = Carbon::parse($p['tanggal_mulai'])
                    ->addDays(rand(0, 2)); // dalam range pertemuan

                AbsensiSiswa::create([
                    'guru_id' => $guruId,
                    'siswa_id' => $murid->id,
                    'pertemuan_ke' => $p['pertemuan_ke'],
                    'tanggal_mulai' => $p['tanggal_mulai'],
                    'tanggal_selesai' => $p['tanggal_selesai'],
                    'tanggal_absen' => $tanggal, // ✅ tidak null
                    'status' => $status, // ✅ tidak ada libur
                ]);
            }
        }
    }
}
