<?php

namespace App\Models;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LampiranA extends Model
{
    protected $table = 'lampiran_a';

    protected $fillable = [
        'guru_id',
        'murid_id',
        'catatan',
        'tahun_ajaran',
    ];

    // ── Relasi ke Guru ─────────────────────────────
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // ── Relasi ke Murid (User) ─────────────────────
    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }
}
