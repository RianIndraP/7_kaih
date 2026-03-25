<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanGuruRead extends Model
{
    public $timestamps = false;

    protected $table = 'pesan_guru_reads';

    protected $fillable = [
        'pesan_id',
        'siswa_id',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function pesan(): BelongsTo
    {
        return $this->belongsTo(PesanGuru::class, 'pesan_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
