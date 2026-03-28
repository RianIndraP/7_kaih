<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nisn',
        'nip',
        'nik',
        'username',
        'email',
        'foto',
        'password',
        'birth_date',
        'tempat_lahir',
        'kelas',
        'ttl',
        'hobi',
        'cita_cita',
        'teman_terbaik',
        'makanan_kesukaan',
        'warna_kesukaan',
        'gender',
        'no_telepon',
        'no_ortu',
        'latitude',
        'longitude',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the guru associated with the user.
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Get the wali kelas guru for this student.
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_wali_id');
    }

    /**
     * Check if user is a guru (has nip, nippkp, or nik)
     */
    public function isGuru(): bool
    {
        return !empty($this->nip) || !empty($this->nik);
    }

    /**
     * Check if user is a student (has nisn)
     */
    public function isSiswa(): bool
    {
        return !empty($this->nisn);
    }

    /**
     * Get user type
     */
    public function getUserType(): string
    {
        if ($this->isGuru()) {
            return 'guru';
        } elseif ($this->isSiswa()) {
            return 'siswa';
        }
        return 'admin';
    }
}
