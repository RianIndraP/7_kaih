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
        'streak_count',
        'streak_recovery_count',
        'streak_recovery_reset_date',
        'last_streak_date',
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
            'streak_recovery_reset_date' => 'date',
            'last_streak_date' => 'date',
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

    /**
     * Update streak based on daily habit completion
     * Streak freezes if not completed today (beku), breaks after 2 days
     */
    public function updateStreak(): void
    {
        // Use now() with timezone and start of day for consistency
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $yesterday = $today->copy()->subDay();

        // Get today's habits
        $kebiasaanHariIni = KebiasaanHarian::where('user_id', $this->id)
            ->where('tanggal', $today->toDateString())
            ->first();

        // Check if all habits are completed today
        $todayCompleted = false;
        if ($kebiasaanHariIni) {
            $status = $kebiasaanHariIni->statusChecklist();
            $todayCompleted = collect($status)->every(fn($completed) => $completed === true);
        }

        if ($todayCompleted) {
            // All habits completed today - streak continues
            if ($this->last_streak_date && $this->last_streak_date->eq($yesterday)) {
                // Yesterday was completed, increment streak
                $this->streak_count++;
            } elseif (!$this->last_streak_date || $this->last_streak_date->lt($yesterday)) {
                // Streak was broken or first time, reset to 1
                $this->streak_count = 1;
            }
            $this->last_streak_date = $today;
        } else {
            // Not completed today - check if streak should freeze or break
            $referenceDate = $this->last_streak_date;
            
            // If last_streak_date is null but streak_count > 0, 
            // use the last kebiasaan date as reference
            if (!$referenceDate && $this->streak_count > 0) {
                $lastKebiasaan = KebiasaanHarian::where('user_id', $this->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                if ($lastKebiasaan) {
                    $referenceDate = Carbon::parse($lastKebiasaan->tanggal);
                }
            }
            
            if ($referenceDate) {
                // Calculate days since last streak
                $daysSinceLastStreak = (int) $referenceDate->diffInDays($today, false);
                
                // DEBUG: Log the calculation
                \Log::debug('Streak Debug', [
                    'user_id' => $this->id,
                    'today' => $today->toDateString(),
                    'last_streak_date' => $this->last_streak_date ? $this->last_streak_date->toDateString() : 'null',
                    'reference_date' => $referenceDate->toDateString(),
                    'daysSinceLastStreak' => $daysSinceLastStreak,
                    'current_streak_count' => $this->streak_count,
                ]);
                
                if ($daysSinceLastStreak === 1) {
                    // Yesterday completed, today not - STREAK BEKU (freeze)
                    // Set last_streak_date to yesterday to track the freeze
                    if (!$this->last_streak_date) {
                        $this->last_streak_date = $referenceDate;
                    }
                } elseif ($daysSinceLastStreak >= 2) {
                    // 2+ days ago - STREAK BREAKS
                    $this->streak_count = 0;
                    $this->last_streak_date = null;
                    \Log::debug('Streak BREAK triggered', ['daysSinceLastStreak' => $daysSinceLastStreak]);
                }
                // If daysSinceLastStreak <= 0 (today), nothing changes
            }
        }

        $this->save();

        // Update virtual pet (pet will be sad if not completed today)
        $this->updateVirtualPet();
    }

    /**
     * Check if user can recover streak (only if last streak was yesterday)
     */
    public function canRecoverStreak(): bool
    {
        if ($this->streak_count > 0) {
            return false; // Streak is active, no need to recover
        }

        $today = Carbon::today('Asia/Jakarta');
        $yesterday = $today->copy()->subDay();
        $resetDate = $this->streak_recovery_reset_date;

        // Check if we need to reset the recovery count (new week)
        if ($resetDate && $resetDate->lt($today->copy()->startOfWeek())) {
            $this->streak_recovery_count = 0;
            $this->streak_recovery_reset_date = $today->copy()->startOfWeek();
            $this->save();
        }

        // Get last streak date or last kebiasaan date
        $lastStreakDate = $this->last_streak_date;
        if (!$lastStreakDate) {
            // Try to get last kebiasaan date
            $lastKebiasaan = KebiasaanHarian::where('user_id', $this->id)
                ->orderBy('tanggal', 'desc')
                ->first();
            if ($lastKebiasaan) {
                $lastStreakDate = Carbon::parse($lastKebiasaan->tanggal);
            }
        }

        // Can only recover if last streak was yesterday (1 day ago)
        if ($lastStreakDate) {
            $daysSince = $lastStreakDate->diffInDays($today, false);
            
            // Only allow recovery if exactly 1 day ago (yesterday)
            if ($daysSince === 1) {
                // Also check weekly limit
                return $this->streak_recovery_count < 2;
            }
        }

        return false; // Too late to recover (2+ days ago)
    }

    /**
     * Recover streak (use one of the 2 weekly recovery chances)
     */
    public function recoverStreak(): bool
    {
        if (!$this->canRecoverStreak()) {
            return false;
        }

        $today = Carbon::today();

        // Set recovery reset date if not set
        if (!$this->streak_recovery_reset_date) {
            $this->streak_recovery_reset_date = $today->copy()->startOfWeek();
        }

        // Increment recovery count
        $this->streak_recovery_count++;
        $this->streak_count = 1;
        $this->last_streak_date = $today;
        $this->save();

        // Revive virtual pet
        $this->updateVirtualPet();

        return true;
    }

    /**
     * Get streak display with fire emoji
     */
    public function getStreakDisplay(): string
    {
        if ($this->streak_count > 0) {
            return "🔥 {$this->streak_count}";
        }
        return "🔥 0";
    }

    /**
     * Get the virtual pet for this user
     */
    public function virtualPet(): HasOne
    {
        return $this->hasOne(VirtualPet::class);
    }

    /**
     * Get or create virtual pet
     */
    public function getOrCreateVirtualPet(): VirtualPet
    {
        $pet = $this->virtualPet;

        if (!$pet) {
            $pet = VirtualPet::create([
                'user_id' => $this->id,
                'name' => 'Kaih',
                'level' => min(11, collect(VirtualPet::FORMS)->filter(fn($f) => $this->streak_count >= $f['min_streak'])->count() + 1),
                'form' => 'egg',
                'happiness' => 50,
                'health' => 50,
                'is_alive' => true,
                'unlocked_forms' => ['egg']
            ]);
        } else {
            // Sync level and form with current streak
            $pet->syncLevel();
            $pet->syncForm();
        }

        return $pet;
    }

    /**
     * Update virtual pet based on current streak and habits
     */
    public function updateVirtualPet(): void
    {
        $pet = $this->getOrCreateVirtualPet();
        $streakActive = $this->streak_count > 0;

        // Get today's habits
        $today = Carbon::today();
        $kebiasaanToday = KebiasaanHarian::where('user_id', $this->id)
            ->where('tanggal', $today->toDateString())
            ->first();

        // Get previous day's habits for comparison
        $kebiasaanPrevious = KebiasaanHarian::where('user_id', $this->id)
            ->where('tanggal', $today->copy()->subDay()->toDateString())
            ->first();

        $pet->updateFromHabits($kebiasaanToday, $kebiasaanPrevious, $this->streak_count, $streakActive);
        $pet->syncLevel();
    }
}
