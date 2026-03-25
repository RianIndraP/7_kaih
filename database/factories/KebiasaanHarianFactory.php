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
        $olahragaOptions = ['lari', 'renang', 'padel', 'sepak bola', 'basket', 'voli', 'badminton', 'senam', 'bersepeda'];
        $bersamaOptions  = ['keluarga', 'teman', 'tetangga', 'publik'];

        return [
            'user_id'            => User::factory(),
            'tanggal'            => $this->faker->dateTimeBetween('-30 days', 'today')->format('Y-m-d'),

            // Bangun Pagi
            'bangun_pagi'        => $this->faker->boolean(80),
            'jam_bangun'         => $this->faker->time('H:i', '07:00'),
            'bangun_catatan'     => $this->faker->optional(0.4)->sentence(),

            // Beribadah
            'sholat_subuh'       => $this->faker->boolean(70),
            'sholat_dzuhur'      => $this->faker->boolean(75),
            'sholat_ashar'       => $this->faker->boolean(75),
            'sholat_maghrib'     => $this->faker->boolean(85),
            'sholat_isya'        => $this->faker->boolean(80),
            'jam_sholat_terakhir'=> $this->faker->time('H:i', '21:00'),
            'baca_quran'         => $this->faker->boolean(60),
            'quran_surah'        => $this->faker->optional(0.6)->numberBetween(1, 114),
            'ibadah_catatan'     => $this->faker->optional(0.3)->sentence(),

            // Berolahraga
            'berolahraga'        => $this->faker->boolean(50),
            'jenis_olahraga'     => $this->faker->randomElements($olahragaOptions, rand(1, 2)),
            'olahraga_catatan'   => $this->faker->optional(0.3)->sentence(),

            // Makan Sehat
            'makan_sehat'        => $this->faker->boolean(70),
            'makan_pagi'         => $this->faker->randomElement(['nasi goreng', 'roti', 'bubur', 'mie']),
            'makan_pagi_done'    => $this->faker->boolean(90),
            'makan_siang'        => $this->faker->randomElement(['nasi ayam', 'nasi sayur', 'sup', 'gado-gado']),
            'makan_siang_done'   => $this->faker->boolean(80),
            'makan_malam'        => $this->faker->randomElement(['nasi lauk', 'mie goreng', 'nasi ikan']),
            'makan_malam_done'   => $this->faker->boolean(70),
            'makan_catatan'      => $this->faker->optional(0.3)->sentence(),

            // Gemar Belajar
            'gemar_belajar'      => $this->faker->boolean(75),
            'materi_belajar'     => $this->faker->randomElement(['Matematika', 'Pemrograman Web', 'Bahasa Inggris', 'Fisika', 'Laravel', 'JavaScript']),
            'belajar_catatan'    => $this->faker->optional(0.4)->sentence(),

            // Bermasyarakat
            'bersama'            => $this->faker->randomElements($bersamaOptions, rand(1, 2)),
            'masyarakat_catatan' => $this->faker->optional(0.3)->sentence(),

            // Tidur Cepat
            'tidur_cepat'        => $this->faker->boolean(65),
            'jam_tidur'          => $this->faker->time('H:i', '23:00'),
            'tidur_catatan'      => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
