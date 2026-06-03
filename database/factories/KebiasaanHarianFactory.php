<?php

namespace Database\Factories;

use App\Models\KebiasaanHarian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KebiasaanHarianFactory extends Factory
{
    protected $model = KebiasaanHarian::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Akan otomatis terhubung ke ID User
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            
            // Bangun Pagi
            'bangun_pagi' => $this->faker->boolean(85), // 85% kemungkinan true
            'jam_bangun' => $this->faker->randomElement(['04:15:00', '04:30:00', '04:45:00']),
            'bangun_catatan' => $this->faker->randomElement(['Alhamdulillah segar', 'Agak ngantuk', null]),
            
            // Beribadah
            'sholat_subuh' => $this->faker->boolean(95),
            'jam_sholat_subuh' => '04:50:00',
            'sholat_dzuhur' => $this->faker->boolean(90),
            'jam_sholat_dzuhur' => '12:15:00',
            'sholat_ashar' => $this->faker->boolean(85),
            'jam_sholat_ashar' => '15:35:00',
            'sholat_maghrib' => $this->faker->boolean(95),
            'jam_sholat_maghrib' => '18:25:00',
            'sholat_isya' => $this->faker->boolean(85),
            'jam_sholat_isya' => '19:30:00',
            'baca_quran' => $this->faker->boolean(70),
            'quran_surah' => $this->faker->randomElement(['Al-Mulk', 'Al-Kahfi', 'Al-Waqiah', 'Juz 30']),
            'ibadah_catatan' => null,
            
            // Berolahraga
            'berolahraga' => $this->faker->boolean(40),
            'jenis_olahraga' => $this->faker->randomElement([['Lari Pagi', 'Push Up'], ['Main Bola'], ['Bersepeda'], null]), // Mengisi array JSON
            'olahraga_catatan' => null,
            
            // Makan Sehat
            'makan_sehat' => $this->faker->boolean(75),
            'makan_pagi' => 'Nasi Uduk / Sayur',
            'makan_pagi_done' => $this->faker->boolean(90),
            'makan_siang' => 'Nasi Ayam + Sayur Sop',
            'makan_siang_done' => $this->faker->boolean(95),
            'makan_malam' => 'Nasi Goreng / Buah',
            'makan_malam_done' => $this->faker->boolean(80),
            'makan_catatan' => 'Minum air putih 2 liter hari ini',
            
            // Gemar Belajar
            'gemar_belajar' => $this->faker->boolean(80),
            'materi_belajar' => $this->faker->randomElement(['Matematika', 'Bahasa Inggris', 'Informatika', 'Sejarah']),
            'belajar_catatan' => 'Mengerjakan PR sekolah',
            
            // Bermasyarakat
            'bersama' => $this->faker->randomElement([['Keluarga', 'Teman Sekelas'], ['Orang Tua'], ['Tetangga'], null]), // Mengisi array JSON
            'masyarakat_catatan' => null,
            
            // Tidur Cepat
            'tidur_cepat' => $this->faker->boolean(70),
            'jam_tidur' => $this->faker->randomElement(['21:00:00', '21:30:00', '22:00:00']),
            'tidur_catatan' => null,
        ];
    }
}
