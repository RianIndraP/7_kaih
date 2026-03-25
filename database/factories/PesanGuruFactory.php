<?php

namespace Database\Factories;

use App\Models\PesanGuru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesanGuruFactory extends Factory
{
    protected $model = PesanGuru::class;

    private array $judul = [
        'Kesalahan pengisian data kebiasaan',
        'Tidak ada pengisian data hari ini',
        'Pengingat: isi data kebiasaan',
        'Peringatan absensi',
        'Motivasi dari guru wali',
        'Pemberitahuan jadwal konseling',
        'Catatan perkembangan belajar',
        'Perhatian khusus dari wali kelas',
    ];

    private array $isi = [
        'Halo, kami memperhatikan bahwa ada kesalahan dalam pengisian data kebiasaan kamu. Mohon periksa kembali data yang kamu masukkan dan pastikan semuanya sudah benar. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.',
        'Kami mencatat bahwa kamu belum mengisi data kebiasaan hari ini. Pengisian data kebiasaan sangat penting untuk memantau perkembanganmu. Mohon segera isi data tersebut sebelum akhir hari.',
        'Halo! Ini adalah pengingat untuk selalu mengisi data 7 kebiasaan setiap hari. Kebiasaan baik dimulai dari konsistensi. Semangat dan tetap jaga kebiasaan positifmu!',
        'Perhatian! Kehadiranmu dalam beberapa hari terakhir perlu mendapat perhatian. Mohon hadir secara rutin dan segera hubungi kami jika ada kendala yang menghalangimu untuk hadir.',
        'Tetap semangat dalam belajar! Perkembanganmu sangat menggembirakan. Pertahankan prestasi baikmu dan teruslah tingkatkan diri. Kami bangga dengan usahamu selama ini.',
    ];

    public function definition(): array
    {
        return [
            'guru_id'    => User::where('role', 'guru')->inRandomOrder()->first()?->id
                         ?? User::factory()->create(['role' => 'guru'])->id,
            'siswa_id'   => User::where('role', 'siswa')->inRandomOrder()->first()?->id
                         ?? User::factory()->create(['role' => 'siswa'])->id,
            'judul'      => $this->faker->randomElement($this->judul),
            'isi'        => $this->faker->randomElement($this->isi),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
