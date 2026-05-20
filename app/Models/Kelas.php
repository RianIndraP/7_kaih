<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'color_index', 'guru_id'];

    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    public function guruWali()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
