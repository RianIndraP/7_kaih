<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesanGuruSiswa extends Model
{
    use HasFactory;

    protected $table = 'pesan_guru_siswa';

    protected $fillable = [
        'guru_id',
        'siswa_id',
        'judul',
        'isi',
        'periode',
        'tanggal',
        'minggu',
        'pertemuan',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'pertemuan'  => 'integer',
        'tahun'      => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(PesanGuruRead::class, 'pesan_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function waktuRelatif(): string
    {
        $diff = (int) now()->diffInDays($this->created_at);

        if ($diff === 0) return 'Hari ini';
        if ($diff === 1) return '1 Hari yang lalu';
        if ($diff < 7)  return $diff . ' Hari yang lalu';
        if ($diff < 14) return '1 Minggu yang lalu';
        if ($diff < 30) return floor($diff / 7) . ' Minggu yang lalu';
        if ($diff < 60) return '1 Bulan yang lalu';

        return floor($diff / 30) . ' Bulan yang lalu';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePeriode($query, $periode, $params = [])
    {
        $query->where('periode', $periode);

        switch ($periode) {
            case 'harian':
                if (isset($params['tanggal'])) {
                    $query->where('tanggal', $params['tanggal']);
                }
                break;
            case 'mingguan':
                if (isset($params['minggu'])) {
                    $query->where('minggu', $params['minggu']);
                }
                break;
            case 'pertemuan':
                if (isset($params['pertemuan'])) {
                    $query->where('pertemuan', $params['pertemuan']);
                }
                break;
            case 'bulanan':
                if (isset($params['bulan'])) {
                    $query->where('bulan', $params['bulan']);
                }
                if (isset($params['tahun'])) {
                    $query->where('tahun', $params['tahun']);
                }
                break;
        }

        return $query;
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getTanggalDisplayAttribute(): string
    {
        if ($this->tanggal) {
            return $this->tanggal->locale('id')->translatedFormat('d F Y');
        }

        return match ($this->periode) {
            'mingguan'  => $this->minggu ?? '',
            'pertemuan' => 'Pertemuan ' . ($this->pertemuan ?? ''),
            'bulanan'   => ($this->bulan
                ? ucfirst(\Carbon\Carbon::createFromFormat('m', $this->bulan)->locale('id')->translatedFormat('F'))
                : '') . ' ' . ($this->tahun ?? ''),
            default     => ''
        };
    }
}