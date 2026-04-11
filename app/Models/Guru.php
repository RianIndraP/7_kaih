<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nippkp',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'alamat',
        'pendidikan_terakhir',
        'jurusan',
        'tahun_lulus',
        'status_pegawai',
        'tmt_kerja',
        'jabatan',
        'unit_kerja',
        'kelas_wali',
        'is_wali_kelas',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_kerja' => 'date',
        'is_wali_kelas' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pesanGuru(): HasMany
    {
        return $this->hasMany(PesanGuru::class);
    }

    public function siswaWaliKelas(): HasMany
    {
        return $this->hasMany(User::class, 'guru_wali_id');
    }

    public function getNamaLengkapAttribute(): string
    {
        $nama = $this->user->name;

        if ($this->gelar_depan) {
            $nama = $this->gelar_depan . ' ' . $nama;
        }

        if ($this->gelar_belakang) {
            $nama = $nama . ', ' . $this->gelar_belakang;
        }

        return $nama;
    }

    public function scopeWaliKelas($query)
    {
        return $query->where('is_wali_kelas', true);
    }

    public function lampiranA()
    {
        return $this->hasMany(LampiranA::class);
    }
}
