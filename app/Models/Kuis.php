<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kuis extends Model
{
    protected $table = 'kuis';

    protected $fillable = [
        'judul',
        'kategori',
        'tema',
        'soal',
        'file_pdf',
        'jumlah_halaman_pdf',
        'waktu_mulai',
        'durasi_menit',
        'created_by',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
    ];

    public function jawaban(): HasMany
    {
        return $this->hasMany(JawabanKuis::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getWaktuSelesaiAttribute()
    {
        return $this->waktu_mulai->copy()->addMinutes($this->durasi_menit);
    }

    public function isSudahDibuka(): bool
    {
        return now()->greaterThanOrEqualTo($this->waktu_mulai);
    }
}