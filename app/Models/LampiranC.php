<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranC extends Model
{
    protected $table = 'lampiran_c';

    protected $fillable = [
        'guru_id',
        'murid_id',
        'pertemuan',
        'tanggal',
        'topik',
        'tindak_lanjut',
        'keterangan',
    ];
}
