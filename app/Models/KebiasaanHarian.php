<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KebiasaanHarian extends Model
{
    use HasFactory;

    protected $table = 'kebiasaan_harian';

    protected $fillable = [
        'user_id',
        'tanggal',
        // Bangun Pagi
        'bangun_pagi',
        'jam_bangun',
        'bangun_catatan',
        // Beribadah
        'sholat_subuh',
        'jam_sholat_subuh',
        'sholat_dzuhur',
        'jam_sholat_dzuhur',
        'sholat_ashar',
        'jam_sholat_ashar',
        'sholat_maghrib',
        'jam_sholat_maghrib',
        'sholat_isya',
        'jam_sholat_isya',
        'baca_quran',
        'quran_surah',
        'ibadah_catatan',
        // Berolahraga
        'berolahraga',
        'jenis_olahraga',
        'olahraga_catatan',
        // Makan Sehat
        'makan_sehat',
        'makan_pagi',
        'makan_pagi_done',
        'makan_siang',
        'makan_siang_done',
        'makan_malam',
        'makan_malam_done',
        'makan_catatan',
        // Gemar Belajar
        'gemar_belajar',
        'materi_belajar',
        'belajar_catatan',
        // Bermasyarakat
        'bersama',
        'masyarakat_catatan',
        // Tidur Cepat
        'tidur_cepat',
        'jam_tidur',
        'tidur_catatan',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'bangun_pagi'       => 'boolean',
        'sholat_subuh'      => 'boolean',
        'sholat_dzuhur'     => 'boolean',
        'sholat_ashar'      => 'boolean',
        'sholat_maghrib'    => 'boolean',
        'sholat_isya'       => 'boolean',
        'baca_quran'        => 'boolean',
        'berolahraga'       => 'boolean',
        'jenis_olahraga'    => 'array',
        'makan_sehat'       => 'boolean',
        'makan_pagi_done'   => 'boolean',
        'makan_siang_done'  => 'boolean',
        'makan_malam_done'  => 'boolean',
        'gemar_belajar'     => 'boolean',
        'bersama'           => 'array',
        'tidur_cepat'       => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hitungSelesai(): int
    {
        $ibadahSelesai = !is_null($this->baca_quran) ||
            $this->sholat_subuh ||
            $this->sholat_dzuhur ||
            $this->sholat_ashar ||
            $this->sholat_maghrib ||
            $this->sholat_isya;
        $fields = [
            'bangun_pagi',
            'berolahraga',
            'makan_sehat',
            'gemar_belajar',
            'bersama',
            'tidur_cepat',
        ];

        $totalSelesai = collect($fields)->filter(fn($f) => !is_null($this->$f))->count();

        return $totalSelesai + ($ibadahSelesai ? 1 : 0);
    }

    public function persentaseSelesai(): int
    {
        return (int) round(($this->hitungSelesai() / 7) * 100);
    }

    public function statusChecklist(): array
    {
        return [
            'bangun_pagi'   => !is_null($this->bangun_pagi),
            'beribadah'     => !is_null($this->baca_quran) || $this->sholat_subuh || $this->sholat_dzuhur || $this->sholat_ashar || $this->sholat_maghrib || $this->sholat_isya,
            'berolahraga'   => !is_null($this->berolahraga),
            'makan_sehat'   => !is_null($this->makan_sehat),
            'gemar_belajar' => !is_null($this->gemar_belajar),
            'bermasyarakat' => !is_null($this->bersama),
            'tidur_cepat'   => !is_null($this->tidur_cepat),
        ];
    }
}
