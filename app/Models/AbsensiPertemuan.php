<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPertemuan extends Model
{
    protected $table = 'absensi_pertemuan';
    protected $fillable = [
        'guru_id',
        'pertemuan_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'foto_dokumentasi',
    ];
}