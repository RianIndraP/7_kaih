<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualPet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'level',
        'form',
        'happiness',
        'health',
        'is_alive',
        'last_fed',
        'unlocked_forms',
        'health_baseline_date',
        'health_baseline_habits'
    ];

    protected $casts = [
        'unlocked_forms' => 'array',
        'is_alive' => 'boolean',
        'last_fed' => 'datetime',
        'health_baseline_date' => 'date',
        'health_baseline_habits' => 'array'
    ];

    // Form progression based on streak
    const FORMS = [
        'egg' => ['min_streak' => 3, 'emoji' => '🥚', 'name' => 'Telur', 'color' => '#FFE4B5'],
        'baby' => ['min_streak' => 20, 'emoji' => '🐣', 'name' => 'Bayi', 'color' => '#FFD700'],
        'child' => ['min_streak' => 40, 'emoji' => '🐥', 'name' => 'Anak', 'color' => '#FFA500'],
        'teen' => ['min_streak' => 60, 'emoji' => '🐤', 'name' => 'Remaja', 'color' => '#FF8C00'],
        'adult' => ['min_streak' => 80, 'emoji' => '🦅', 'name' => 'Dewasa', 'color' => '#4169E1'],
        'warrior' => ['min_streak' => 100, 'emoji' => '⚔️', 'name' => 'Patarung Junior', 'color' => '#C0C0C0'],
        'fire_guardian' => ['min_streak' => 120, 'emoji' => '🔥', 'name' => 'Penjaga Api', 'color' => '#FF4500'],
        'fire_soldier' => ['min_streak' => 140, 'emoji' => '⚡', 'name' => 'Pasukan Api', 'color' => '#DC143C'],
        'fire_prince' => ['min_streak' => 160, 'emoji' => '👑', 'name' => 'Pangeran Api', 'color' => '#B8860B'],
        'fire_king' => ['min_streak' => 180, 'emoji' => '👑', 'name' => 'Raja Api', 'color' => '#8B0000'],
        'fire_legendary' => ['min_streak' => 200, 'emoji' => '🔥', 'name' => 'Api Legendaris', 'color' => '#9400D3'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get streak count from user relation
    public function getStreakCount(): int
    {
        return $this->user?->streak_count ?? 0;
    }

    // Get available forms based on streak
    public function getAvailableForms(): array
    {
        $available = [];
        $streakCount = $this->getStreakCount();
        foreach (self::FORMS as $key => $form) {
            if ($streakCount >= $form['min_streak']) {
                $available[$key] = $form;
            }
        }
        return $available;
    }

    // Get current form details
    public function getFormDetails(): array
    {
        return self::FORMS[$this->form] ?? self::FORMS['egg'];
    }

    // Get emoji based on health and happiness
    public function getCurrentEmoji(): string
    {
        if (!$this->is_alive) {
            return '💀';
        }

        $formDetails = $this->getFormDetails();
        $emoji = $formDetails['emoji'];

        // Add mood indicators based on happiness/health
        if ($this->happiness >= 80 && $this->health >= 80) {
            return $emoji . '✨'; // Super happy
        } elseif ($this->happiness >= 60) {
            return $emoji . '😊'; // Happy
        } elseif ($this->happiness >= 40) {
            return $emoji; // Neutral
        } elseif ($this->happiness >= 20) {
            return $emoji . '😢'; // Sad
        } else {
            return $emoji . '💔'; // Very sad
        }
    }

    // Calculate happiness based on completed habits today
    public function calculateHappinessFromHabits(?KebiasaanHarian $kebiasaanToday): int
    {
        if (!$kebiasaanToday) {
            // No habits filled today = 0% happiness (sad)
            return 0;
        }

        // Count completed habits (0-7)
        $completedCount = $kebiasaanToday->hitungSelesai();

        // Calculate happiness: 0=0%, 1=14%, 2=28%, 3=42%, 4=56%, 5=70%, 6=84%, 7=100%
        $happinessMap = [
            0 => 0,
            1 => 14,
            2 => 28,
            3 => 42,
            4 => 56,
            5 => 70,
            6 => 84,
            7 => 100
        ];

        return $happinessMap[$completedCount] ?? 0;
    }

    // Calculate health based on comparison with streak baseline (when streak started)
    public function calculateHealthFromBaseline(?KebiasaanHarian $today): int
    {
        $health = $this->health ?? 50;

        if (!$today) {
            return min(100, $health); // No data
        }

        // Get baseline habits (when streak started)
        $baseline = $this->health_baseline_habits ?? [];
        
        if (empty($baseline)) {
            // No baseline set yet, set current as baseline and return current health
            return min(100, $health);
        }

        $healthChange = 0;

        // Compare with baseline (when streak first started)
        
        // Check prayer improvement compared to baseline
        $prayerFields = ['sholat_subuh', 'sholat_dzuhur', 'sholat_ashar', 'sholat_maghrib', 'sholat_isya'];
        $baselinePrayerCount = collect($prayerFields)->filter(fn($f) => ($baseline[$f] ?? false))->count();
        $todayPrayerCount = collect($prayerFields)->filter(fn($f) => $today->$f)->count();

        if ($todayPrayerCount > $baselinePrayerCount) {
            // Praying more than when streak started = health up
            $healthChange += 10;
        } elseif ($todayPrayerCount < $baselinePrayerCount) {
            // Praying less than baseline = health down
            $healthChange -= 5;
        }

        // Check sleep improvement vs baseline
        $baselineTidur = $baseline['tidur_cepat'] ?? false;
        if ($today->tidur_cepat && !$baselineTidur) {
            // Started sleeping early compared to baseline = health up
            $healthChange += 8;
        } elseif (!$today->tidur_cepat && $baselineTidur) {
            // Stopped sleeping early = health down
            $healthChange -= 4;
        }

        // Check wake up improvement vs baseline
        $baselineBangun = $baseline['bangun_pagi'] ?? false;
        if ($today->bangun_pagi && !$baselineBangun) {
            // Started waking up early vs baseline = health up
            $healthChange += 5;
        } elseif (!$today->bangun_pagi && $baselineBangun) {
            // Stopped waking up early = health down
            $healthChange -= 3;
        }

        // Check exercise improvement vs baseline
        $baselineOlahraga = $baseline['berolahraga'] ?? false;
        if ($today->berolahraga && !$baselineOlahraga) {
            // Started exercising vs baseline = health up
            $healthChange += 7;
        } elseif (!$today->berolahraga && $baselineOlahraga) {
            // Stopped exercising = health down
            $healthChange -= 4;
        }

        // Check healthy eating improvement vs baseline
        $baselineMakan = $baseline['makan_sehat'] ?? false;
        if ($today->makan_sehat && !$baselineMakan) {
            // Started eating healthy vs baseline = health up
            $healthChange += 6;
        } elseif (!$today->makan_sehat && $baselineMakan) {
            // Stopped eating healthy = health down
            $healthChange -= 3;
        }

        // Check learning improvement vs baseline
        if ($today->gemar_belajar && !($baseline['gemar_belajar'] ?? false)) {
            // Started studying vs baseline = health up
            $healthChange += 5;
        }

        // Check social improvement vs baseline
        if ($today->bersama && !($baseline['bersama'] ?? false)) {
            // Started socializing vs baseline = health up
            $healthChange += 4;
        }

        return min(100, max(0, $health + $healthChange));
    }

    // Set health baseline when streak starts or resets
    public function setHealthBaseline(?KebiasaanHarian $today): void
    {
        if (!$today) {
            // No habits today, set empty baseline
            $this->health_baseline_habits = [];
            $this->health_baseline_date = now();
            return;
        }

        // Save current habits as baseline
        $this->health_baseline_habits = [
            'sholat_subuh' => $today->sholat_subuh,
            'sholat_dzuhur' => $today->sholat_dzuhur,
            'sholat_ashar' => $today->sholat_ashar,
            'sholat_maghrib' => $today->sholat_maghrib,
            'sholat_isya' => $today->sholat_isya,
            'tidur_cepat' => $today->tidur_cepat,
            'bangun_pagi' => $today->bangun_pagi,
            'berolahraga' => $today->berolahraga,
            'makan_sehat' => $today->makan_sehat,
            'gemar_belajar' => $today->gemar_belajar,
            'bersama' => $today->bersama ? true : false,
        ];
        $this->health_baseline_date = now();
    }

    // Update pet based on daily habits and streak
    public function updateFromHabits(?KebiasaanHarian $today, ?KebiasaanHarian $previous, int $streakCount, bool $streakActive): void
    {
        if (!$streakActive) {
            // Streak lost - pet dies but can be revived
            if ($this->is_alive && $streakCount === 0) {
                $this->is_alive = false;
                $this->happiness = 0;
                $this->health = 10;
                // Don't reset baseline here - keep it for when streak is recovered
            }
        } else {
            // Streak active - pet is alive
            
            // Check if this is a new streak (reset to 0 then started again)
            $isNewStreak = $this->health_baseline_date === null || 
                          ($this->health_baseline_date !== null && $streakCount === 1 && $this->getStreakCount() === 0);
            
            // Also check if streak was recovered from 0
            $wasDead = !$this->is_alive;
            
            if (!$this->is_alive) {
                // Revive pet
                $this->is_alive = true;
            }

            // Set baseline if this is a new streak start (streak = 1 and no baseline or streak was 0)
            if ($streakCount === 1 && (empty($this->health_baseline_habits) || $wasDead)) {
                $this->setHealthBaseline($today);
                $this->health = 50; // Reset health to 50 for new streak
            }

            // Calculate happiness based on today's habits
            $this->happiness = $this->calculateHappinessFromHabits($today);

            // Calculate health based on baseline (since streak started)
            $this->health = $this->calculateHealthFromBaseline($today);

            // Determine new form based on streak
            $newForm = 'egg';
            foreach (self::FORMS as $formKey => $formData) {
                if ($streakCount >= $formData['min_streak']) {
                    $newForm = $formKey;
                }
            }

            // Check if form changed (level up!)
            if ($newForm !== $this->form) {
                $this->form = $newForm;
                // Unlock new form
                $unlocked = $this->unlocked_forms ?? [];
                if (!in_array($newForm, $unlocked)) {
                    $unlocked[] = $newForm;
                    $this->unlocked_forms = $unlocked;
                }
                // Big happiness boost on level up!
                $this->happiness = min(100, $this->happiness + 30);
            }
        }

        $this->save();
    }

    // Legacy method for backward compatibility
    public function updateFromStreak(int $streakCount, bool $streakActive): void
    {
        $this->updateFromHabits(null, null, $streakCount, $streakActive);
    }

    // Get level based on current form (1-11 levels)
    public function calculateLevel(): int
    {
        $streakCount = $this->getStreakCount();
        $level = 1;
        foreach (self::FORMS as $formKey => $formData) {
            if ($streakCount >= $formData['min_streak']) {
                $level++;
            }
        }
        return min(11, $level);
    }

    // Sync level with streak
    public function syncLevel(): void
    {
        $newLevel = $this->calculateLevel();
        if ($newLevel !== $this->level) {
            $this->level = $newLevel;
            $this->save();
        }
    }

    // Sync form based on current streak
    public function syncForm(): void
    {
        $streakCount = $this->getStreakCount();
        $newForm = 'egg';
        
        foreach (self::FORMS as $formKey => $formData) {
            if ($streakCount >= $formData['min_streak']) {
                $newForm = $formKey;
            }
        }
        
        if ($newForm !== $this->form) {
            $this->form = $newForm;
            // Unlock new form
            $unlocked = $this->unlocked_forms ?? [];
            if (!in_array($newForm, $unlocked)) {
                $unlocked[] = $newForm;
                $this->unlocked_forms = $unlocked;
            }
            $this->save();
        }
    }

    // Force recalculate and save level (for existing pets)
    public function recalculateLevel(): void
    {
        $this->level = $this->calculateLevel();
        $this->save();
    }
}
