<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'tanggal' => 'date',
        'pertemuan' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Get the guru that owns the pesan.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Get the siswa that owns the pesan.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    /**
     * Scope untuk pesan berdasarkan periode
     */
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

    /**
     * Get formatted tanggal for display
     */
    public function getTanggalDisplayAttribute(): string
    {
        if ($this->tanggal) {
            return $this->tanggal->locale('id')->translatedFormat('d F Y');
        }
        
        return match($this->periode) {
            'mingguan' => $this->minggu ?? '',
            'pertemuan' => 'Pertemuan ' . ($this->pertemuan ?? ''),
            'bulanan' => ($this->bulan ? ucfirst(\Carbon\Carbon::createFromFormat('m', $this->bulan)->locale('id')->translatedFormat('F')) : '') . ' ' . ($this->tahun ?? ''),
            default => ''
        };
    }
}
