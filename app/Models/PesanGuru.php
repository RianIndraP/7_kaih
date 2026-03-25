<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PesanGuru extends Model
{
    use HasFactory;

    protected $table = 'pesan_guru';

    protected $fillable = [
        'guru_id',
        'siswa_id',
        'judul',
        'isi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(PesanGuruRead::class, 'pesan_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Cek apakah pesan sudah dibaca oleh siswa tertentu.
     */
    public function sudahDibaca(int $siswaId): bool
    {
        return $this->reads()->where('siswa_id', $siswaId)->exists();
    }

    /**
     * Label waktu relatif (1 Hari yang lalu, dst).
     */
    public function waktuRelatif(): string
    {
        $diff = now()->diffInDays($this->created_at);

        if ($diff === 0) return 'Hari ini';
        if ($diff === 1) return '1 Hari yang lalu';
        if ($diff < 7)  return $diff . ' Hari yang lalu';
        if ($diff < 14) return '1 Minggu yang lalu';
        if ($diff < 30) return floor($diff / 7) . ' Minggu yang lalu';
        if ($diff < 60) return '1 Bulan yang lalu';

        return floor($diff / 30) . ' Bulan yang lalu';
    }
}
