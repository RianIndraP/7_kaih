<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

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
        'kelas_id',
        'is_alumni',
        'tanggal_masuk',
        'angkatan',
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
        'guru_wali_id',
        'foto',
        'tempat_lahir',
        'no_hp',
        'fcm_token',
        'last_login_at',
        'teman_terbaik_json',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            // Convert empty strings to NULL for nisn, nip, nik, and username
            $user->nisn = empty($user->nisn) ? null : $user->nisn;
            $user->nip = empty($user->nip) ? null : $user->nip;
            $user->nik = empty($user->nik) ? null : $user->nik;
            $user->username = empty($user->username) ? null : $user->username;

            // If user is an admin (has username), ensure they cannot have nisn, nip, or nik
            if ($user->isAdmin()) {
                $user->nisn = null;
                $user->nip = null;
                $user->nik = null;
            }
        });
    }

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
            'tanggal_masuk' => 'datetime',
            'is_alumni' => 'boolean',
            'password' => 'hashed',
            'teman_terbaik_json' => 'array',
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
     * Get the kelas for this student.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Check if user should be marked as alumni (3 years from July entry to May)
     */
    public function shouldBeAlumni(): bool
    {
        if (empty($this->tanggal_masuk)) {
            return false;
        }

        $entryDate = Carbon::parse($this->tanggal_masuk);

        // Calculate the alumni date: July year 1 to May year 3
        // Entry is July of year 1, alumni becomes May of year 3 (after ~2 years 10 months)
        $alumniDate = $entryDate->copy()->addYears(2)->setMonth(5)->setDay(31);

        return now()->greaterThanOrEqualTo($alumniDate);
    }

    /**
     * Check if user is an alumni
     */
    public function isAlumni(): bool
    {
        return $this->is_alumni;
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
     * Check if user is an admin (has username)
     */
    public function isAdmin(): bool
    {
        return !empty($this->username);
    }

    /**
     * Check if user is a Kepala Sekolah (Principal)
     */
    public function isKepalaSekolah(): bool
    {
        if (!$this->isGuru()) {
            return false;
        }
        
        return $this->guru && $this->guru->status_pegawai === 'Kepala Sekolah';
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

    public function lampiranA()
    {
        return $this->hasMany(LampiranA::class, 'murid_id');
    }

    /**
     * Check if student profile is complete
     */
    public function isProfileComplete(): bool
    {
        $requiredFields = [
            'name',
            'tempat_lahir',
            'birth_date',
            'kelas_id',
            'gender',
            'nisn',
            'no_telepon',
            'no_ortu',
            'email',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        // Check for at least one best friend with phone number
        $temanTerbaikJson = $this->teman_terbaik_json ?? [];
        if (!empty($this->teman_terbaik) && empty($temanTerbaikJson)) {
            // Backward compatibility: if old teman_terbaik field has data but json is empty
            return !empty($this->teman_terbaik);
        }

        if (empty($temanTerbaikJson)) {
            return false;
        }

        // Check if at least one entry has both nama and nomor
        $hasValidEntry = false;
        foreach ($temanTerbaikJson as $teman) {
            if (!empty($teman['nama']) && !empty($teman['nomor'])) {
                $hasValidEntry = true;
                break;
            }
        }

        return $hasValidEntry;
    }
}
