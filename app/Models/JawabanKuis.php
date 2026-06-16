<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanKuis extends Model
{
    protected $table = 'jawaban_kuis';

    protected $fillable = [
        'kuis_id',
        'siswa_id',
        'jawaban',
        'mulai_dikerjakan',
        'waktu_kirim',
    ];

    protected $casts = [
        'mulai_dikerjakan' => 'datetime',
        'waktu_kirim' => 'datetime',
    ];

    public function kuis(): BelongsTo
    {
        return $this->belongsTo(Kuis::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function getDeadlineAttribute()
    {
        if (!$this->mulai_dikerjakan)
            return null;
        return $this->mulai_dikerjakan->copy()->addMinutes($this->kuis->durasi_menit);
    }

    public function isKadaluarsa(): bool
    {
        if ($this->waktu_kirim)
            return false;
        if (!$this->mulai_dikerjakan)
            return false;
        return now()->greaterThan($this->deadline);
    }

    // status: belum_dikerjakan | sedang_berlangsung | sudah_dikerjakan | kadaluarsa
    public function getStatusAttribute(): string
    {
        if ($this->waktu_kirim)
            return 'sudah_dikerjakan';
        if ($this->mulai_dikerjakan) {
            return $this->isKadaluarsa() ? 'kadaluarsa' : 'sedang_berlangsung';
        }
        return 'belum_dikerjakan';
    }
}