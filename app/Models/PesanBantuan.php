<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PesanBantuan extends Model
{
    use HasFactory;
 
    protected $table = 'pesan_bantuan';
 
    protected $fillable = [
        'user_id',
        'nama_pengirim',
        'kategori',
        'judul',
        'isi',
        'status',       // belum_ditangani | sedang_diproses | selesai
        'balasan',
    ];
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}