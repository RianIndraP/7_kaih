# Streak System by NemoProject

## Overview
Sistem Streak adalah fitur motivasi yang dirancang untuk mendorong konsistensi pengguna dalam menyelesaikan kebiasaan harian. Sistem ini menggunakan logika kompleks untuk melacak streak harian dengan mekanisme pembekuan dan pemulihan yang seimbang.

---

## 🎯 Fitur Utama

### 1. **Streak Counter**
- Menghitung jumlah hari berturut-turut pengguna menyelesaikan semua kebiasaan
- Ditampilkan di dashboard dengan visual yang menarik
- Update otomatis setiap hari

### 2. **Status Streak**
Terdapat 3 status utama:

#### 🔥 **Aktif (Active)**
- Kondisi: Streak > 0 dan semua kebiasaan hari ini sudah selesai
- Visual: Badge hijau dengan emoji 🔥
- Pesan: "Streak Aktif!"

#### ❄️ **Beku (Frozen)**
- Kondisi: Streak > 0 tapi hari ini belum mengisi kebiasaan
- Visual: Badge biru dengan emoji ❄️
- Pesan: "Streak Beku! Isi kebiasaan hari ini sebelum besok agar streak tidak putus"

#### 💔 **Putus (Broken)**
- Kondisi: Streak = 0 karena tidak mengisi 2+ hari berturut-turut
- Visual: Badge merah dengan emoji 💔
- Pesan: "Streak Putus!"

#### 💀 **Mati (Dead)**
- Kondisi: Streak = 0 dan sudah lewat 2+ hari tanpa pemulihan
- Visual: Badge abu-abu dengan emoji 💀
- Pesan: "Streak Mati! Terlambat 2+ hari"

---

## ⚙️ Cara Kerja Sistem

### **Logika Update Harian**

Sistem update streak setiap kali dashboard diakses:

```php
// Timeline per hari:
// Hari ini (2026-05-12): Check kebiasaan hari ini
// └── Jika semua selesai → Streak +1
// └── Jika belum selesai → Check kemarin
//     └── Jika kemarin selesai → Status BEKU
//     └── Jika kemarin tidak selesai → Status PUTUS
```

### **Alur Keputusan**

1. **Check Kebiasaan Hari Ini**
   - Ambil data `KebiasaanHarian` untuk tanggal hari ini
   - Hitung jumlah kebiasaan yang selesai dengan `hitungSelesai()`
   - Jika ≥ 6 dari 7 kebiasaan → Dianggap selesai

2. **Update Streak**
   ```php
   if ($todayCompleted) {
       // Semua kebiasaan selesai hari ini
       if ($last_streak_date == $yesterday) {
           $streak_count++;  // Lanjut streak
       } else {
           $streak_count = 1;  // Mulai streak baru
       }
       $last_streak_date = $today;
   } else {
       // Belum selesai hari ini
       $daysSince = $last_streak_date->diffInDays($today);
       if ($daysSince === 1) {
           // Kemarin selesai, hari ini belum → BEKU
           // Tidak ada perubahan streak
       } elseif ($daysSince >= 2) {
           // 2+ hari tidak selesai → PUTUS
           $streak_count = 0;
           $last_streak_date = null;
       }
   }
   ```

---

## 🔄 Sistem Pemulihan (Recovery)

### **Kondisi Pemulihan**
Streak hanya bisa dipulihkan jika:
- Streak sudah putus (streak_count = 0)
- Streak terakhir kemarin (1 hari lalu)
- Belum mencapai batas pemulihan mingguan

### **Batasan Pemulihan**
- **Maksimal**: 2 kali pemulihan per minggu
- **Reset**: Setiap hari Senin
- **Timeout**: Hanya 24 jam setelah streak putus

### **Proses Pemulihan**
```php
// Timeline recovery:
// Hari X: Streak putus (tidak isi kemarin)
// Hari X+1: Bisa pulihkan (window 24 jam)
// Hari X+2: Terlambat, tidak bisa pulihkan lagi
```

### **API Endpoint**
- **URL**: `POST /student/api/streak/recover`
- **Response**: 
  - Success: `"Streak berhasil dipulihkan! 🎉"`
  - Error: `"Streak sudah terlalu lama putus (2+ hari). Tidak bisa dipulihkan lagi."`

---

## 📊 Database Schema

### **Tabel Users**
```sql
streak_count           INT DEFAULT 0
last_streak_date       DATE NULL
streak_recovery_count  INT DEFAULT 0
streak_recovery_reset_date DATE NULL
```

### **Tabel KebiasaanHarian**
```sql
user_id        INT
tanggal       DATE
bangun_pagi   BOOLEAN
beribadah     BOOLEAN
berolahraga   BOOLEAN
makan_sehat   BOOLEAN
gemar_belajar BOOLEAN
bermasyarakat BOOLEAN
tidur_cepat   BOOLEAN
```

---

## 🎨 UI/UX Implementation

### **Streak Card Design**
- **Layout**: Compact dengan glassmorphism effect
- **Color Scheme**: Gradient orange untuk aktif, biru untuk beku, merah untuk putus
- **Animation**: Fire pulse untuk streak aktif, smooth transitions
- **Responsive**: Mobile-friendly dengan touch support

### **Status Indicators**
```html
<!-- Active Streak -->
<div class="badge-green">🔥 Aktif</div>

<!-- Frozen Streak -->
<div class="badge-blue">❄️ Beku</div>

<!-- Broken Streak (recoverable) -->
<div class="badge-red">💔 Putus</div>
<button onclick="recoverStreak()">PULIHKAN</button>

<!-- Dead Streak (non-recoverable) -->
<div class="badge-gray">💀 Mati</div>
```

### **Progress Bar**
- **Target**: 7 hari per minggu
- **Visual**: White progress bar dengan glow effect
- **Formula**: `(streak_count % 20) / 20 * 100`

---

## 🔧 Technical Implementation

### **Classes & Methods**

#### **User Model**
```php
public function updateStreak(): void
public function canRecoverStreak(): bool
public function recoverStreak(): bool
public function getStreakDisplay(): array
```

#### **DashboardController**
```php
public function index(): View
// - Update streak
// - Calculate stats
// - Prepare UI data
```

#### **StreakController (API)**
```php
public function recover(Request $request)
// - Handle recovery requests
// - Validate conditions
// - Return JSON responses
```

### **Frontend JavaScript**
```javascript
// Recovery function
async function recoverStreak() {
    const response = await fetch('/student/api/streak/recover', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        }
    });
    // Handle success/error
}

// Download streak card
async function downloadStreak(event) {
    // Screenshot dengan html2canvas
}
```

---

## 📈 Debug & Monitoring

### **Debug Info**
```php
$debugInfo = [
    'today' => $today->toDateString(),
    'last_streak_date' => $lastStreakDate ? $lastStreakDate->toDateString() : 'null',
    'days_since_last_streak' => $daysSince,
    'last_kebiasaan_tanggal' => $lastKebiasaan ? $lastKebiasaan->tanggal : null,
    'streak_count' => $streakCount,
];
```

### **Logging**
```php
\Log::debug('Streak Debug', [
    'user_id' => $this->id,
    'action' => 'update',
    'streak_count' => $this->streak_count,
    'last_streak_date' => $this->last_streak_date,
]);
```

---

## 🐾 Virtual Pet Integration

### **Overview**
Virtual Pet adalah fitur companion yang terintegrasi dengan sistem streak untuk memberikan feedback visual dan emosional kepada pengguna. Pet bereaksi terhadap performa streak dan memberikan motivasi tambahan.

### **Pet States & Emotions**

#### 😊 **Happy State**
- **Trigger**: Streak aktif (≥ 3 hari)
- **Visual**: Emoji 😊 dengan animasi bounce
- **Behavior**: Energetic, responsive, playful
- **Message**: "Aku senang kamu konsisten!"

#### 😔 **Sad State**
- **Trigger**: Streak beku atau putus
- **Visual**: Emoji 😔 dengan animasi slow
- **Behavior**: Lethargic, less responsive
- **Message**: "Aku sedih, tolong isi kebiasaan hari ini..."

#### 😴 **Sleeping State**
- **Trigger**: Streak mati (2+ hari tidak aktif)
- **Visual**: Emoji 😴 dengan Z bubbles
- **Behavior**: Inactive, minimal interaction
- **Message**: "Aku tidur, bangunin aku ya..."

#### 🎉 **Excited State**
- **Trigger**: Milestone streak (7, 14, 30 hari)
- **Visual**: Emoji 🎉 dengan confetti animation
- **Behavior**: Super energetic, special effects
- **Message**: "WOW! Kamu hebat! 🎊"

### **Pet-Streak Connection Logic**

```php
// Pet emotion based on streak status
public function updateVirtualPet(): void
{
    if ($this->streak_count >= 7) {
        $this->pet_emotion = 'excited';
        $this->pet_form = 'star';  // Special form
    } elseif ($this->streak_count >= 3) {
        $this->pet_emotion = 'happy';
        $this->pet_form = 'normal';
    } elseif ($this->streak_count > 0) {
        $this->pet_emotion = 'neutral';
        $this->pet_form = 'normal';
    } else {
        $this->pet_emotion = 'sad';
        $this->pet_form = 'normal';
    }
    
    $this->save();
}
```

### **Pet Forms Evolution**

#### **Base Form** (Default)
- **Emoji**: 🐱 / 🐶 / 🐰 (pilihan user)
- **Unlock**: Level 1 (0 streak)
- **Features**: Basic animations

#### **Star Form** (Milestone)
- **Emoji**: ⭐ (special)
- **Unlock**: Streak ≥ 7 hari
- **Features**: Glow effect, special animations

#### **Fire Form** (Master)
- **Emoji**: 🔥 (legendary)
- **Unlock**: Streak ≥ 30 hari
- **Features**: Fire particles, crown

#### **Ice Form** (Frozen)
- **Emoji**: 🧊 (temporary)
- **Trigger**: Streak beku
- **Features**: Ice crystals, slow movement

### **Pet Interactions**

#### **Click Animations**
```javascript
function animatePet() {
    const pet = document.getElementById('petAvatar');
    
    // Different animations based on emotion
    switch(petEmotion) {
        case 'happy':
            // Bounce animation
            pet.style.animation = 'bounce 0.5s ease';
            break;
        case 'sad':
            // Wiggle animation
            pet.style.animation = 'wiggle 0.3s ease';
            break;
        case 'excited':
            // Spin + bounce
            pet.style.animation = 'spinBounce 0.8s ease';
            break;
    }
}
```

#### **Drag & Drop**
- **Default Position**: Bottom-right corner
- **Drag Handle**: Header area only
- **Smooth Movement**: translate3d with hardware acceleration
- **Position Persistence**: Saved in localStorage
- **Boundary Detection**: Prevents dragging outside viewport

#### **Form Change**
```javascript
async function changePetForm(form) {
    const response = await fetch('/api/pet/change-form', {
        method: 'POST',
        body: JSON.stringify({ form })
    });
    
    if (response.success) {
        // Update pet visual immediately
        updatePetVisual(form);
        // Show success message
        showNotification('Pet berubah bentuk!');
    }
}
```

### **Pet Dashboard Integration**

#### **Pet Card Design**
```html
<div id="virtualPetCard" class="pet-card">
    <!-- Header with drag handle -->
    <div id="petDragHandle">
        <span>Virtual Pet {{ $pet->name }}</span>
        <span onclick="closePet()">✕</span>
    </div>
    
    <!-- Pet Avatar -->
    <div id="petAvatar" onclick="animatePet()">
        {{ $pet->getEmoji() }}
    </div>
    
    <!-- Status Indicators -->
    <div class="pet-status">
        <span>Mood: {{ $pet->emotion }}</span>
        <span>Form: {{ $pet->form }}</span>
    </div>
    
    <!-- Action Buttons -->
    <div class="pet-actions">
        <button onclick="changePetForm('star')">⭐ Form</button>
        <button onclick="feedPet()">🍖 Feed</button>
    </div>
</div>
```

#### **Streak Status in Pet**
```php
// Pet card shows current streak status
@if ($streakCount > 0 && !$kebiasaanHariIni)
    <span class="pet-frozen-badge">❄️ Beku</span>
@endif

@if ($streakCount >= 7)
    <span class="pet-milestone-badge">🎉 {{ $streakCount }} hari!</span>
@endif
```

### **Pet Database Schema**

#### **Tabel Virtual Pets**
```sql
user_id          INT (foreign key)
name             VARCHAR(50)
form             VARCHAR(20) DEFAULT 'normal'
emotion          VARCHAR(20) DEFAULT 'neutral'
happiness        INT DEFAULT 50
last_fed         DATE NULL
special_unlocks  JSON
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

#### **Pet Form Unlocks**
```sql
user_id          INT
form_type        VARCHAR(20)
unlocked_at      TIMESTAMP
streak_required  INT
```

### **Pet-Streak Synchronization**

#### **Real-time Updates**
```php
// When streak updates, pet also updates
public function updateStreak(): void
{
    // ... streak logic ...
    
    // Update pet emotion based on new streak
    $this->updateVirtualPet();
}

// When pet is fed, streak gets bonus
public function feedPet(): bool
{
    $this->pet_happiness = min(100, $this->pet_happiness + 10);
    
    // Bonus: +1 streak if pet is very happy
    if ($this->pet_happiness >= 90 && $this->streak_count > 0) {
        $this->streak_count++;
    }
    
    $this->save();
    return true;
}
```

#### **Emotion Decay System**
```php
// Pet emotion slowly decays if streak is inactive
public function decayPetEmotion(): void
{
    $daysInactive = $this->last_streak_date 
        ? $this->last_streak_date->diffInDays(now())
        : 999;
    
    switch($daysInactive) {
        case 0: $this->pet_emotion = 'happy'; break;
        case 1: $this->pet_emotion = 'neutral'; break;
        case 2: $this->pet_emotion = 'sad'; break;
        default: $this->pet_emotion = 'sleeping'; break;
    }
}
```

### **Pet Notifications**

#### **Motivational Messages**
```javascript
const petMessages = {
    happy: [
        "Kamu hebat! Teruskan! 🌟",
        "Aku bangga padamu! 💪",
        "Konsistensi adalah kunci! 🔑"
    ],
    sad: [
        "Aku sedih... tolong isi kebiasaan 😢",
        "Jangan menyerah, aku di sini untukmu 🤗",
        "Besok pasti lebih baik! 💫"
    ],
    excited: [
        "WOW! Kamu luar biasa! 🎊",
        "Milestone achieved! 🏆",
        "Kamu juara! 👑"
    ]
};
```

#### **Visual Feedback**
```css
.pet-avatar.happy {
    animation: happyBounce 2s infinite;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
}

.pet-avatar.sad {
    filter: grayscale(50%);
    animation: sadWiggle 3s infinite;
}

.pet-avatar.excited {
    animation: excitedSpin 1s infinite;
    box-shadow: 0 0 30px rgba(255, 105, 180, 0.8);
}
```

### **Pet Analytics**

#### **Engagement Metrics**
- **Pet Click Rate**: How often users interact with pet
- **Form Change Frequency**: Usage of different pet forms
- **Emotion Impact**: Effect of pet emotions on streak completion
- **Retention Correlation**: Users with active pets vs streak retention

#### **Performance Tracking**
```php
// Track pet-streak correlation
public function getPetStreakStats(): array
{
    return [
        'avg_streak_with_pet' => 15.2,
        'avg_streak_without_pet' => 7.8,
        'pet_engagement_rate' => 0.85,
        'form_change_frequency' => 3.2,
        'emotion_impact_score' => 0.92
    ];
}
```

### **Advanced Pet Features (Future)**

#### **Pet Personalities**
- **Energetic**: Bounce animations, fast responses
- **Calm**: Smooth animations, gentle movements  
- **Playful**: Random animations, interactive elements
- **Lazy**: Slow animations, sleep frequently

#### **Pet Accessories**
- **Hats**: Unlock dengan streak milestones
- **Toys**: Interaktif elements
- **Homes**: Background customization
- **Food**: Special items untuk boost happiness

#### **Social Features**
- **Pet Visits**: See friends' pets
- **Pet Racing**: Compete based on streak performance
- **Pet Photos**: Share pet screenshots
- **Pet Leaderboard**: Most engaged pets

---

## 🎯 Use Cases & Scenarios

### **Scenario 1: Perfect Streak with Happy Pet**
```
Hari 1: ✅ Selesai semua → Streak: 1 → Pet: 😊 Happy
Hari 2: ✅ Selesai semua → Streak: 2 → Pet: 😊 Happy  
Hari 3: ✅ Selesai semua → Streak: 3 → Pet: 😊 Happy (unlock bounce animation)
Hari 7: ✅ Selesai semua → Streak: 7 → Pet: 🎉 Excited (unlock Star Form)
```

### **Scenario 2: Frozen Streak with Sad Pet**
```
Hari 5: ✅ Selesai semua → Streak: 5 → Pet: 😊 Happy
Hari 6: ❌ Belum isi → Status: ❄️ Beku → Pet: 😔 Sad
Hari 7: ✅ Selesai semua → Streak: 6 → Pet: 😊 Happy (recovered)
```

### **Scenario 3: Broken & Recovered with Pet Evolution**
```
Hari 10: ✅ Selesai semua → Streak: 10 → Pet: 😊 Happy
Hari 11: ❌ Tidak isi → Status: ❄️ Beku → Pet: 😔 Sad
Hari 12: ❌ Tidak isi → Status: 💔 Putus → Pet: 😔 Sad
Hari 13: 🔄 Pulihkan → Streak: 1 → Pet: 😊 Happy (recovery used: 1/2)
```

### **Scenario 4: Dead Streak with Sleeping Pet**
```
Hari 20: ✅ Selesai semua → Streak: 20 → Pet: 🎉 Excited (Fire Form)
Hari 21: ❌ Tidak isi → Status: ❄️ Beku → Pet: 😔 Sad
Hari 22: ❌ Tidak isi → Status: 💔 Putus → Pet: 😔 Sad
Hari 23: ❌ Tidak pulihkan → Status: 💀 Mati → Pet: 😴 Sleeping
Hari 24: ❌ Terlambat → Tidak bisa pulihkan → Pet: 😴 Sleeping
```

### **Scenario 5: Pet Interaction & Streak Bonus**
```
Hari 15: ✅ Selesai semua → Streak: 15 → Pet: 😊 Happy
Hari 15: 🍖 Feed Pet → Happiness: 90% → Bonus: Streak: 16
Hari 16: ⭐ Change Form → Pet: ⭐ Star → Motivation boost
Hari 17: ✅ Selesai semua → Streak: 17 → Pet: 🎉 Excited
```

### **Scenario 6: Complete Pet-Streak Journey**
```
Day 0:  New user → Streak: 0 → Pet: 🐱 Base Form (neutral)
Day 1:  ✅ Complete → Streak: 1 → Pet: 😊 Neutral
Day 3:  ✅ Complete → Streak: 3 → Pet: 😊 Happy (unlock animations)
Day 7:  ✅ Complete → Streak: 7 → Pet: 🎉 Excited (unlock Star Form)
Day 14: ❌ Skip → Status: ❄️ Beku → Pet: 😔 Sad
Day 15: ✅ Resume → Streak: 8 → Pet: 😊 Happy
Day 30: ✅ Complete → Streak: 30 → Pet: 🔥 Fire Form (legendary)
```

---

## 🚀 Future Enhancements

### **Planned Features**
1. **Streak Multipliers**: Bonus untuk streak panjang
2. **Achievement Badges**: Unlock untuk milestone tertentu
3. **Streak Freeze Items**: Power-up untuk pause streak
4. **Social Features**: Leaderboard dan competition
5. **Analytics**: Detailed streak insights

### **Virtual Pet Enhancements**
1. **Pet Evolution System**: Multiple evolution paths based on streak patterns
2. **Pet Skills & Abilities**: Special powers yang unlock dengan streak milestones
3. **Pet Housing**: Customizable pet environments dan backgrounds
4. **Pet Socialization**: Interaksi antar pets user lain
5. **Pet Quest System**: Daily challenges untuk pet dengan streak rewards
6. **Pet Genetics**: Breeding system untuk rare pet combinations
7. **Pet Marketplace**: Trade pets dengan user lain
8. **Pet AR Mode**: Augmented reality interaction dengan pet
9. **Pet Voice**: Voice commands dan pet responses
10. **Pet Health System**: Complex needs beyond happiness (hunger, energy, social)

### **Performance Optimizations**
1. **Caching**: Cache streak calculations
2. **Batch Updates**: Process multiple users at once
3. **Queue System**: Async streak updates
4. **Database Indexes**: Optimize queries

---

## 📝 Configuration

### **Environment Variables**
```env
STREAK_RECOVERY_LIMIT=2
STREAK_TIMEZONE=Asia/Jakarta
STREAK_MIN_HABITS=6
STREAK_RESET_DAY=monday
```

### **Constants**
```php
const MAX_RECOVERY_PER_WEEK = 2;
const MIN_HABITS_REQUIRED = 6;
const STREAK_TIMEZONE = 'Asia/Jakarta';
```

---

## 🐛 Troubleshooting

### **Common Issues**
1. **Streak tidak update**: Check `last_streak_date` null
2. **Recovery tidak muncul**: Verify `canRecoverStreak()` logic
3. **Timezone issues**: Ensure Asia/Jakarta timezone
4. **Database sync**: Check `streak_recovery_count` values

### **Debug Steps**
1. Enable debug info in dashboard
2. Check Laravel logs
3. Verify database values
4. Test API endpoints manually

---

## 📚 API Documentation

### **Recover Streak**
```http
POST /student/api/streak/recover
Content-Type: application/json
X-CSRF-TOKEN: [token]

Response:
{
    "success": true,
    "message": "Streak berhasil dipulihkan! 🎉"
}

Error:
{
    "success": false,
    "message": "Streak sudah terlalu lama putus (2+ hari). Tidak bisa dipulihkan lagi."
}
```

---

## 🎨 Design Guidelines

### **Color Palette**
- **Active**: Linear-gradient(#ff6b35, #f7931e, #ffcd3c)
- **Frozen**: Linear-gradient(#60a5fa, #3b82f6)
- **Broken**: Linear-gradient(#f87171, #ef4444)
- **Dead**: Linear-gradient(#9ca3af, #6b7280)

### **Typography**
- **Headers**: 800 weight, uppercase
- **Body**: 500 weight, normal case
- **Numbers**: 700 weight, large size
- **Labels**: 600 weight, small size

---

## 🔐 Security Considerations

### **Authentication**
- All API endpoints require auth middleware
- CSRF token validation
- User ownership verification

### **Data Integrity**
- Atomic transactions for streak updates
- Concurrent request handling
- Input validation and sanitization

---

## 📊 Analytics & Metrics

### **Key Metrics**
- **Streak Length Distribution**: Histogram of streak durations
- **Recovery Rate**: % of broken streaks that get recovered
- **Daily Active Users**: Users with active streaks
- **Freeze vs Break Ratio**: Comparison of freeze vs break events

### **Monitoring**
- Real-time streak updates
- Recovery usage tracking
- Performance metrics
- Error rate monitoring

---

## 🎯 Conclusion

Sistem Streak by NemoProject dirancang dengan balance antara motivasi dan realisme. Dengan mekanisme pembekuan yang memberikan grace period dan sistem recovery yang terbatas, pengguna didorong untuk konsisten tanpa merasa terlalu ketat.

**Key Principles:**
1. **Motivasi > Punishment**: Focus on positive reinforcement
2. **Grace Period**: Allow human error with freeze mechanism
3. **Limited Recovery**: Prevent abuse while maintaining hope
4. **Visual Feedback**: Clear status indicators and progress
5. **Mobile First**: Responsive design for all devices

---

*Created by NemoProject - 2026*
