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
        'topik',
        'tindak_lanjut',
    ];

    // ── Relasi ke Murid (User) ─────────────────────
    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    // ── Relasi ke Guru ─────────────────────────────
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}