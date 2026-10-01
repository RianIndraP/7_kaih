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
        $ibadahSelesai = $this->isIbadahSelesai();
        $fields = [
            'bangun_pagi',
            'berolahraga',
            'makan_sehat',
            'gemar_belajar',
            'bersama',
            'tidur_cepat',
        ];

        // WAJIB: nilai harus bernilai true, bukan sekadar "tidak null".
        // Kolom nullable (bangun_pagi, olahraga, dll) diisi false ketika siswa
        // menjawab "tidak" — is_null() akan menghitungnya sebagai "selesai".
        $totalSelesai = collect($fields)
            ->filter(fn ($f) => $this->isFieldTerisi($f))
            ->count();

        return $totalSelesai + ($ibadahSelesai ? 1 : 0);
    }

    /**
     * Kolom boolean di-cast ke bool, sehingga null tetap null dan
     * 0/1 menjadi false/true. Kolom bersama/jenis_olahraga adalah longtext
     * berisi JSON array, jadi harus dicek isinya, bukan null-ness-nya.
     */
    private function isFieldTerisi(string $field): bool
    {
        $value = $this->$field;

        if (is_null($value)) {
            return false;
        }

        if (is_array($value)) {
            return count($value) > 0;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return count($decoded) > 0;
            }

            return trim($value) !== '';
        }

        // bool: harus true
        return (bool) $value;
    }

    private function isIbadahSelesai(): bool
    {
        if (!is_null($this->baca_quran) && $this->baca_quran) {
            return true;
        }

        return (bool) ($this->sholat_subuh || $this->sholat_dzuhur
            || $this->sholat_ashar || $this->sholat_maghrib || $this->sholat_isya);
    }

    public function persentaseSelesai(): int
    {
        return (int) round(($this->hitungSelesai() / 7) * 100);
    }

    public function statusChecklist(): array
    {
        return [
'bangun_pagi'   => $this->isFieldTerisi('bangun_pagi'),
            'beribadah'     => $this->isIbadahSelesai(),
            'berolahraga'   => $this->isFieldTerisi('berolahraga'),
            'makan_sehat'   => $this->isFieldTerisi('makan_sehat'),
            'gemar_belajar' => $this->isFieldTerisi('gemar_belajar'),
            'bermasyarakat' => $this->isFieldTerisi('bersama'),
            'tidur_cepat'   => $this->isFieldTerisi('tidur_cepat'),
        ];
    }
}
