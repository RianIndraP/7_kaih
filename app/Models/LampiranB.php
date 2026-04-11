<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranB extends Model
{
    protected $table = 'lampiran_b';

    protected $fillable = [
        'guru_id',
        'murid_id',
        'bulan',
        'tahun',
        'aspek',
        'nilai',
        'deskripsi',
        'tindak_lanjut',
        'keterangan'
    ];

    // relasi
    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
