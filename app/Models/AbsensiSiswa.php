<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiSiswa extends Model
{
    protected $table = 'absensi_siswa';

    protected $fillable = [
        'guru_id',
        'siswa_id',
        'pertemuan_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_absen',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_mulai'       => 'date',
        'tanggal_selesai'     => 'date',
        'tanggal_absen'       => 'date',
    ];

    protected $attributes = [
        'status' => 'tidak_hadir',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
