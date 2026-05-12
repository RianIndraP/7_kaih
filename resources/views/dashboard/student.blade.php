@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard')

@section('content')

    <style>
        /* Streak card share version styles - needed for screenshot */
    </style>

    <div
        class="max-w-[1100px] lg:max-w-[1600px] xl:max-w-[1800px] 2xl:max-w-[1900px] mx-auto space-y-4 px-4 sm:px-6 lg:px-8 xl:px-10">

        {{-- ═══════════════════════════════════════════
        GREETING CARD
        ════════════════════════════════════════════ --}}
        <div class="greeting-gradient relative overflow-hidden rounded-2xl px-6 py-5
                    flex items-center justify-between anim-fade-up">
            {{-- Decorative circles --}}
            <span class="absolute -top-14 -right-10 w-44 h-44 rounded-full bg-white/7 pointer-events-none"></span>
            <span class="absolute -bottom-8 right-20 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></span>

            <div class="relative z-10">
                <h1 class="text-[18px] font-extrabold text-white mb-1 leading-tight">
                    Selamat Datang, {{ $user->name ?? 'Siswa' }} 👋
                </h1>
                <p class="text-[13px] text-white/72">
                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    · {{ $user->kelas?->nama_kelas ?? '-' }}
                </p>
            </div>

            <div class="relative z-10 w-[58px] h-[58px] rounded-2xl bg-white/18 border-2 border-white/30
                        flex items-center justify-center shrink-0 overflow-hidden">
                @if (!empty($user->foto))
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->name }}"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
        VIRTUAL PET - KARAKTER KAIH
        ════════════════════════════════════════════ --}}
        @php
            $pet = $user->getOrCreateVirtualPet();
            $petDetails = $pet->getFormDetails();
            $availableForms = $pet->getAvailableForms();
            $updateMessage = \App\Models\WebsiteManagement::getUpdateMessage('siswa');
        @endphp

        {{-- Update Message Notification --}}
        @if($updateMessage)
            <div id="updateMessageBox"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg anim-fade-up relative">
                <button onclick="toggleUpdateMessage()"
                    class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition text-white text-sm"
                    title="Sembunyikan/Tampilkan">
                    <svg id="updateToggleIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="updateMessageContent" class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 pr-6">
                        <h3 class="font-semibold text-sm mb-2">📢 Informasi Update</h3>
                        <div class="update-message-text text-sm text-white/95 leading-relaxed">{!! $updateMessage !!}</div>
                    </div>
                </div>
                <button id="updateShowBtn" onclick="toggleUpdateMessage()"
                    class="hidden w-full items-center justify-center gap-2 py-2 text-white/80 hover:text-white text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 13l-7 7-7-7M5 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                    <span>Tampilkan informasi update</span>
                </button>
            </div>

            <style>
                /* Update Message Styling */
                .update-message-text h1 {
                    font-size: 1.25rem;
                    font-weight: 700;
                    margin: 0.75rem 0 0.5rem;
                }

                .update-message-text h2 {
                    font-size: 1.1rem;
                    font-weight: 600;
                    margin: 0.6rem 0 0.4rem;
                }

                .update-message-text h3 {
                    font-size: 1rem;
                    font-weight: 600;
                    margin: 0.5rem 0 0.3rem;
                }

                .update-message-text h4 {
                    font-size: 0.95rem;
                    font-weight: 600;
                    margin: 0.4rem 0 0.2rem;
                }

                .update-message-text p {
                    margin: 0.4rem 0;
                }

                .update-message-text ul,
                .update-message-text ol {
                    margin: 0.5rem 0;
                    padding-left: 1.5rem;
                }

                .update-message-text ul {
                    list-style-type: disc !important;
                }

                .update-message-text ol {
                    list-style-type: decimal !important;
                }

                .update-message-text li {
                    margin: 0.25rem 0;
                }

                .update-message-text ul ul {
                    list-style-type: circle !important;
                }

                .update-message-text ol ol {
                    list-style-type: lower-alpha !important;
                }

                .update-message-text strong {
                    font-weight: 600;
                }

                .update-message-text em {
                    font-style: italic;
                }

                .update-message-text a {
                    color: #bfdbfe;
                    text-decoration: underline;
                }

                .update-message-text a:hover {
                    color: #ffffff;
                }
            </style>

            <script>
                function toggleUpdateMessage() {
                    const content = document.getElementById('updateMessageContent');
                    const showBtn = document.getElementById('updateShowBtn');
                    const icon = document.getElementById('updateToggleIcon');
                    const isHidden = content.classList.contains('hidden');

                    if (isHidden) {
                        content.classList.remove('hidden');
                        showBtn.classList.add('hidden');
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
                        localStorage.setItem('updateMessageHidden', 'false');
                    } else {
                        content.classList.add('hidden');
                        showBtn.classList.remove('hidden');
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12a9 9 0 1118 0 9 9 0 01-18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/>';
                        localStorage.setItem('updateMessageHidden', 'true');
                    }
                }

                // Restore state on page load
                document.addEventListener('DOMContentLoaded', function () {
                    const isHidden = localStorage.getItem('updateMessageHidden') === 'true';
                    if (isHidden) {
                        const content = document.getElementById('updateMessageContent');
                        const showBtn = document.getElementById('updateShowBtn');
                        const icon = document.getElementById('updateToggleIcon');
                        if (content && showBtn && icon) {
                            content.classList.add('hidden');
                            showBtn.classList.remove('hidden');
                            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12a9 9 0 1118 0 9 9 0 01-18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8"/>';
                        }
                    }
                });
            </script>
        @endif

        {{-- Draggable Pet Popup - Compact Bottom Right --}}
        <div id="virtualPetCard"
            style="position: fixed; bottom: 80px; left: auto; right: 10px; width: 140px; max-width: calc(100vw - 20px); z-index: 100; overflow: hidden; border-radius: 12px; background: linear-gradient(135deg, {{ $petDetails['color'] }} 0%, #ffffff 100%); border: 2px solid rgba(255,255,255,0.9); box-shadow: 0 4px 15px rgba(0,0,0,0.12); transition: all 0.3s ease; transform-origin: bottom right;">
            {{-- Header with Drag Grip --}}
            <div id="petDragHandle"
                style="background: rgba(0,0,0,0.08); padding: 6px 10px; cursor: grab; touch-action: none; user-select: none; -webkit-user-select: none;">
                {{-- Drag Grip Indicator --}}
                <div style="display: flex; justify-content: center; margin-bottom: 4px; pointer-events: none;">
                    <div style="width: 36px; height: 4px; background: rgba(0,0,0,0.2); border-radius: 2px;"></div>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0;">
                        <span
                            style="font-size: 11px; font-weight: 700; color: rgba(0,0,0,0.7); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Virtual
                            Pet {{ $pet->name }}</span>
                        @if($user->streak_count > 0 && !($kebiasaanHariIni ?? false))
                            <span
                                style="font-size: 8px; background: #3b82f6; color: white; padding: 1px 4px; border-radius: 6px; font-weight: 600; flex-shrink: 0;">❄️
                                Beku</span>
                        @endif
                    </div>
                    <span style="font-size: 16px; color: rgba(0,0,0,0.5); cursor: pointer; padding: 4px; flex-shrink: 0; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
                        onclick="closePet(event)"
                        onmouseover="this.style.backgroundColor='rgba(0,0,0,0.1)'; this.style.color='rgba(0,0,0,0.7)'; this.style.transform='scale(1.1)'"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgba(0,0,0,0.5)'; this.style.transform='scale(1)'">✕</span>
                </div>
            </div>

            <div id="petContent" style="padding: 10px;">
                {{-- Pet Avatar --}}
                <div style="text-align: center; margin-bottom: 8px;">
                    <div id="petAvatar"
                        style="width: 50px; height: 50px; margin: 0 auto; border-radius: 50%; background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.7) 100%); border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; position: relative; cursor: pointer; transition: transform 0.2s;"
                        onclick="animatePet()">
                        {{-- Main Pet Emoji --}}
                        <span style="font-size: 28px; line-height: 1; display: flex; align-items: center; justify-content: center;">{{ $petDetails['emoji'] }}</span>
                        {{-- Mood Badge --}}
                        @php
                            $moodEmoji = '';
                            if (!$pet->is_alive) $moodEmoji = '💀';
                            elseif ($pet->happiness >= 80 && $pet->health >= 80) $moodEmoji = '✨';
                            elseif ($pet->happiness >= 60) $moodEmoji = '😊';
                            elseif ($pet->happiness >= 40) $moodEmoji = '';
                            elseif ($pet->happiness >= 20) $moodEmoji = '😢';
                            else $moodEmoji = '💔';
                        @endphp
                        @if($moodEmoji)
                            <span style="position: absolute; bottom: -2px; right: -2px; font-size: 12px; background: white; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.08);">{{ $moodEmoji }}</span>
                        @endif
                    </div>
                </div>

                {{-- Pet Name & Level --}}
                <div style="text-align: center; margin-bottom: 8px;">
                    <span style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #333; padding: 2px 8px; border-radius: 8px; font-size: 9px; font-weight: 700;">Lv.{{ $pet->level }}</span>
                    <span style="font-size: 11px; color: #444; font-weight: 600; display: block; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 110px; margin-left: auto; margin-right: auto;">{{ $petDetails['name'] }}</span>
                </div>

                {{-- Compact Stats --}}
                <div style="margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <span style="font-size: 10px;">❤️</span>
                        <div style="flex: 1; height: 5px; background: rgba(0,0,0,0.08); border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ $pet->health }}%; height: 100%; background: linear-gradient(90deg, #ff6b6b, #ff8e8e); border-radius: 3px;"></div>
                        </div>
                        <span style="font-size: 8px; color: #555; font-weight: 600; min-width: 18px;">{{ $pet->health }}%</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="font-size: 10px;">😊</span>
                        <div style="flex: 1; height: 5px; background: rgba(0,0,0,0.08); border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ $pet->happiness }}%; height: 100%; background: linear-gradient(90deg, #4ecdc4, #44a3aa); border-radius: 3px;"></div>
                        </div>
                        <span style="font-size: 8px; color: #555; font-weight: 600; min-width: 18px;">{{ $pet->happiness }}%</span>
                    </div>
                </div>

                {{-- Level Progress --}}
                <div style="background: rgba(255,255,255,0.5); padding: 4px 8px; border-radius: 8px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                        <span style="font-size: 8px; color: #444; font-weight: 600;">Lv{{ $pet->level + 1 }}</span>
                        <span style="font-size: 8px; color: #777;">{{ $user->streak_count % 20 }}/20</span>
                    </div>
                    <div style="width: 100%; height: 4px; background: rgba(0,0,0,0.08); border-radius: 2px; overflow: hidden;">
                        <div style="width: {{ min(100, ($user->streak_count % 20) / 20 * 100) }}%; height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
        </div>{{-- Close virtualPetCard --}}

        {{-- ═══════════════════════════════════════════
        STREAK CARD - SHARE VERSION (Hidden, for Screenshot)
        ════════════════════════════════════════════ --}}
        <div id="streakCardShare"
            style="visibility: hidden; position: fixed; top: 0; left: 0; width: 400px; height: 500px; z-index: -1000;">
            <div
                style="width: 400px; height: 500px; background: linear-gradient(180deg, #ff6b35 0%, #f7931e 40%, #ffcd3c 100%); border-radius: 20px; border: 4px solid #fff; box-sizing: border-box; padding: 20px; position: relative;">

                {{-- Corner decorations --}}
                <div style="position: absolute; top: 15px; left: 20px; font-size: 26px; z-index: 5;">⭐</div>
                <div style="position: absolute; top: 15px; right: 20px; font-size: 26px; z-index: 5;">🔥</div>
                <div style="position: absolute; bottom: 15px; left: 20px; font-size: 26px; z-index: 5;">✨</div>
                <div style="position: absolute; bottom: 15px; right: 20px; font-size: 26px; z-index: 5;">⚡</div>

                {{-- Header --}}
                <div style="text-align: center; margin-top: 5px; margin-bottom: 20px;">
                    <p
                        style="margin: 0; font-size: 20px; font-weight: 800; color: white; text-transform: uppercase; letter-spacing: 3px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        7 KEBIASAAN</p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.95);">Anak
                        Indonesia Hebat</p>
                </div>

                {{-- Photo Container --}}
                <div style="width: 140px; height: 140px; margin: 0 auto 20px auto; position: relative;">
                    {{-- Outer ring --}}
                    <div
                        style="position: absolute; top: -8px; left: -8px; right: -8px; bottom: -8px; border: 3px dashed rgba(255,255,255,0.6); border-radius: 50%;">
                    </div>

                    {{-- Photo container with white border --}}
                    <div
                        style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid white; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative; z-index: 2;">
                        @if (!empty($user->foto))
                            @php
                                $fotoPath = $user->foto;
                                $photoFullUrl = URL::to('storage/' . $fotoPath);
                            @endphp
                            {{-- HD Image - fokus ke wajah (atas), center horizontal --}}
                            <div style="width: 140px; height: 140px; overflow: hidden; position: relative;">
                                <img id="sharePhotoImg" src="{{ $photoFullUrl }}" crossorigin="anonymous"
                                    style="width: auto; height: 140px; max-width: none; position: absolute; top: 0; left: 50%; transform: translateX(-50%);" />
                            </div>
                        @else
                            <div
                                style="width: 140px; height: 140px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <span style="font-size: 55px;">👤</span>
                            </div>
                        @endif
                    </div>

                    {{-- Fire decorations --}}
                    <span style="position: absolute; top: -8px; right: -12px; font-size: 26px; z-index: 10;">🔥</span>
                    <span style="position: absolute; bottom: 5px; left: -18px; font-size: 22px; z-index: 10;">🔥</span>
                </div>

                {{-- Name and Class --}}
                <div style="text-align: center; margin-bottom: 8px;">
                    <p
                        style="margin: 0 0 6px 0; font-size: 24px; font-weight: 800; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        {{ $user->name }}</p>
                    <table
                        style="margin: 0 auto; background: rgba(0,0,0,0.3); border-radius: 10px; border-collapse: collapse;">
                        <tr>
                            <td style="margin-top: 5px; padding: 10px 15px; text-align: center;">
                                <span
                                    style="color: white; font-size: 13px; font-weight: 800; display: block; line-height: 1; margin-top: -12px;">{{ $user->kelas?->nama_kelas ?? 'Kelas -' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Streak Box --}}
                <table
                    style="background: rgba(255,255,255,0.25); border: 2px solid rgba(255,255,255,0.6); border-radius: 10px; margin-bottom: 8px; width: 220px; margin-left: auto; margin-right: auto; border-collapse: collapse;">
                    <tr>
                        <!-- Padding dikurangi semua -->
                        <td style="padding: 4px 20px 0px 20px; text-align: center;">
                            <span
                                style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.95); text-transform: uppercase; letter-spacing: 1px;">Total
                                Streak</span>
                        </td>
                    </tr>
                    <tr>
                        <!-- Negative margin untuk naikkan angka lebih tinggi -->
                        <td style="padding: 0px 20px 0px 20px; text-align: center;">
                            <span
                                style="font-size: 42px; font-weight: 900; color: white; line-height: 1; margin-top: -20px; display: inline-block; text-shadow: 3px 3px 6px rgba(0,0,0,0.3);">{{ $streakCount }}</span>
                        </td>
                    </tr>
                    <tr>
                        <!-- Padding atas dikurangi -->
                        <td style="padding: 5px 20px 4px 20px; text-align: center;">
                            <span style="font-size: 12px; font-weight: 600; color: white; line-height: 1;">HARI</span>
                        </td>
                    </tr>
                </table>


                {{-- Quote --}}
                <div style="text-align: center;">
                    @if ($streakCount >= 7)
                        <p
                            style="margin: 0; font-size: 16px; font-weight: 700; color: white; font-style: italic; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                            Luar Biasa! Teruskan!</p>
                    @elseif ($streakCount >= 5)
                        <p
                            style="margin: 0; font-size: 16px; font-weight: 700; color: white; font-style: italic; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                            Keren Banget! Jangan Berhenti!</p>
                    @elseif ($streakCount >= 3)
                        <p
                            style="margin: 0; font-size: 16px; font-weight: 700; color: white; font-style: italic; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                            Mantap! Konsisten!</p>
                    @elseif ($streakCount >= 1)
                        <p
                            style="margin: 0; font-size: 16px; font-weight: 700; color: white; font-style: italic; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                            Semangat! Mulai Streak!</p>
                    @else
                        <p
                            style="margin: 0; font-size: 16px; font-weight: 700; color: white; font-style: italic; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                            Ayo Mulai Hari Ini!</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
        ALERT: PROFIL BELUM LENGKAP
        ════════════════════════════════════════════ --}}
        @if (!$user->isProfileComplete())
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-red-600 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Profil Belum Lengkap</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Silakan lengkapi profil untuk mengakses semua fitur.</p>
                </div>
                <a href="{{ route('student.profile') }}" class="text-[12px] font-bold text-red-600 no-underline whitespace-nowrap
                              hover:text-red-800 hover:underline transition-colors shrink-0">
                    Lengkapi Sekarang →
                </a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
        ALERT: PESAN BELUM DIBACA
        ════════════════════════════════════════════ --}}
        @if (auth()->user()->isProfileComplete())
            @php
                $unreadPesan = \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
                    ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                    ->count();
            @endphp
            @if ($unreadPesan > 0)
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                    <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-blue-600 shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-gray-900">
                            {{ $unreadPesan }} Pesan Baru dari Guru Wali
                        </p>
                        <p class="text-[12px] text-gray-500 mt-0.5">Anda memiliki pesan yang belum dibaca.</p>
                    </div>
                    <a href="{{ route('student.pesan') }}" class="text-[12px] font-bold text-blue-600 no-underline whitespace-nowrap
                                      hover:text-blue-800 hover:underline transition-colors shrink-0">
                        Lihat Pesan →
                    </a>
                </div>
            @endif
        @endif

        {{-- ═══════════════════════════════════════════
        DATE ROW
        ════════════════════════════════════════════ --}}
        <div class="flex items-center justify-between bg-white border border-gray-200 rounded-2xl
                    px-5 py-3.5 anim-fade-up-2">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-[10px] icon-gradient shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Data Hari Ini</p>
                    <p class="text-[15px] font-extrabold text-gray-900">
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            @if ($kebiasaanHariIni)
                <div class="flex items-center gap-1.5 bg-green-100 text-green-700 font-bold
                                text-[12px] px-3.5 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Sudah Lengkap
                </div>
            @else
                <div class="flex items-center gap-1.5 bg-orange-100 text-orange-700 font-bold
                                text-[12px] px-3.5 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Belum Lengkap
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════
        MAIN GRID: Profil + 7 Kebiasaan
        ════════════════════════════════════════════ --}}
        @php
            $kebiasaanList = [
                'bangun_pagi' => 'Bangun pagi',
                'beribadah' => 'Beribadah',
                'berolahraga' => 'Berolahraga',
                'makan_sehat' => 'Makan sehat',
                'gemar_belajar' => 'Gemar belajar',
                'bermasyarakat' => 'Bermasyarakat',
                'tidur_cepat' => 'Tidur cepat',
            ];
            $kebiasaanData = $kebiasaanData ?? [];
            $totalKebiasaan = count($kebiasaanList);
            $selesai = collect($kebiasaanData)->filter()->count();
            $persen = $totalKebiasaan > 0 ? round(($selesai / $totalKebiasaan) * 100) : 0;
            $semuaSudahIsi = $selesai >= $totalKebiasaan;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 anim-fade-up-3">

            {{-- ── KOLOM KIRI: Profil + Aksi Cepat ── --}}
            <div class="flex flex-col gap-4">

                {{-- Profil Siswa --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    {{-- Card header --}}
                    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                                bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div
                            class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Profil Siswa</span>
                    </div>
                    {{-- Card body --}}
                    <div class="flex items-center gap-3.5 p-4">
                        {{-- Photo --}}
                        <div class="w-[76px] h-[76px] rounded-2xl photo-gradient border-[3px] border-indigo-100
                                    flex items-center justify-center shrink-0 overflow-hidden">
                            @if (!empty($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-9 h-9 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                        {{-- Info rows --}}
                        <div class="flex flex-col gap-2 flex-1">
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">Nama</span>
                                <span class="text-[12px] font-bold text-gray-900">{{ $user->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">NISN</span>
                                <span class="text-[12px] font-bold text-gray-900">{{ $user->nisn ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">Kelas</span>
                                <span
                                    class="text-[12px] font-bold text-gray-900">{{ $user->kelas?->nama_kelas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aksi Cepat --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                                bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div
                            class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Aksi Cepat</span>
                    </div>
                    <div class="p-2.5 flex flex-col gap-2">

                        {{-- Isi 7 Kebiasaan --}}
                        <a href="{{ route('student.kebiasaan') }}" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                                  no-underline bg-white transition-all duration-200
                                  hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                                  hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                        transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Isi 7 Kebiasaan
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Catat kebiasaan harian kamu</p>
                            </div>
                            <svg class="w-[15px] h-[15px] text-gray-300 transition-all duration-200
                                       group-hover:text-blue-500 group-hover:translate-x-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        {{-- Pesan Guru Wali --}}
                        <a href="{{ route('student.pesan') }}" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                                  no-underline bg-white transition-all duration-200
                                  hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                                  hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                        transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Pesan Guru Wali
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Lihat pesan terbaru dari guru</p>
                            </div>
                            <svg class="w-[15px] h-[15px] text-gray-300 transition-all duration-200
                                       group-hover:text-blue-500 group-hover:translate-x-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                    </div>
                </div>

            </div>{{-- end left col --}}

            {{-- ── KOLOM KANAN: 7 Kebiasaan ── --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex flex-col">

                <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-indigo-50 shrink-0">
                    <div class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                        <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Status 7 Kebiasaan Anak Indonesia Hebat</span>
                </div>

                <div class="flex flex-col gap-3.5 p-4 flex-1">

                    {{-- Progress bar --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-gray-500">Progres Kebiasaan</span>
                            <span class="text-[12px] font-extrabold text-blue-600" id="habitPct">{{ $persen }}%
                                Lengkap</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div id="habitFill" class="h-full rounded-full transition-[width] duration-700 ease-in-out
                                        {{ $semuaSudahIsi ? 'progress-fill-green' : 'progress-fill-blue' }}"
                                style="width: {{ $persen }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Checklist grid (interactive) --}}
                    <div class="grid grid-cols-2 gap-2" id="habitsGrid">
                        @foreach ($kebiasaanList as $key => $label)
                            @php $checked = !empty($kebiasaanData[$key]); @endphp
                            <div data-key="{{ $key }}"
                                class="habit-item flex items-center gap-2 px-3 py-2.5 rounded-[10px]
                                            border select-none transition-all duration-200
                                            {{ $checked ? 'bg-blue-50 border-blue-200 checked' : 'bg-white border-gray-200 ?>' }}">
                                {{-- Checkbox --}}
                                <div class="flex items-center justify-center w-[17px] h-[17px] rounded-[5px] border-2 shrink-0
                                                {{ $checked ? 'bg-blue-600 border-blue-600' : 'bg-white border-gray-300' }}">
                                    <svg class="w-2.5 h-2.5 text-white {{ $checked ? 'opacity-100' : 'opacity-0' }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span
                                    class="text-[12px] font-medium {{ $checked ? 'text-blue-700 font-bold' : 'text-gray-600' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="pt-2 border-t border-gray-100 mt-auto" id="habitFooter">
                        @if (!$semuaSudahIsi)
                            <a href="{{ route('student.kebiasaan') }}" class="inline-flex items-center gap-1.5 text-[12px] font-bold text-blue-600
                                          no-underline transition-all duration-200 hover:text-blue-800 hover:gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Lengkapi data kebiasaan →
                            </a>
                        @else
                            <div class="inline-flex items-center gap-1.5 text-[12px] font-bold text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Semua kebiasaan hari ini sudah diisi!
                            </div>
                        @endif
                    </div>

                </div>
            </div>{{-- end habit card --}}

        </div>{{-- end grid --}}

        {{-- ═══════════════════════════════════════════
        ALERT BAWAH: Status kebiasaan hari ini
        ════════════════════════════════════════════ --}}
        @if (!$kebiasaanHariIni)
            <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 rounded-2xl
                            px-4 py-3 anim-fade-up-4">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px]
                                bg-orange-500 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Belum mengisi kebiasaan hari ini</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Jangan lupa catat kebiasaanmu sebelum hari berakhir!</p>
                </div>
                <a href="{{ route('student.kebiasaan') }}" class="text-[12px] font-bold text-orange-600 no-underline whitespace-nowrap
                              hover:text-orange-800 hover:underline transition-colors shrink-0">
                    Isi Sekarang →
                </a>
            </div>
        @else
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl
                            px-4 py-3 anim-fade-up-4">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px]
                                bg-green-600 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Kebiasaan hari ini sudah diisi</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Pertahankan konsistensi kamu. Keren! 🎉</p>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
        STREAK CARD - WEB VERSION (Modern)
        ════════════════════════════════════════════ --}}
        <div id="streakCardWeb"
            style="position: relative; overflow: hidden; border-radius: 16px; background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #ffcd3c 100%); box-shadow: 0 8px 32px rgba(255, 107, 53, 0.3), inset 0 1px 0 rgba(255,255,255,0.3);">
            {{-- Background pattern --}}
            <div style="position: absolute; inset: 0; opacity: 0.1; background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
            
            {{-- Glowing orb decoration --}}
            <div style="position: absolute; top: -30px; right: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 70%); border-radius: 50%; filter: blur(10px);"></div>
            <div style="position: absolute; bottom: -20px; left: -20px; width: 60px; height: 60px; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%); border-radius: 50%; filter: blur(8px);"></div>

            {{-- Main content --}}
            <div style="position: relative; padding: 16px 20px;">
                {{-- Header row --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- Animated fire icon --}}
                        <div style="position: relative;">
                            <div style="font-size: 32px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); animation: firePulse 2s ease-in-out infinite;">🔥</div>
                            <div style="position: absolute; inset: 0; font-size: 32px; filter: blur(8px); opacity: 0.5; animation: fireGlow 2s ease-in-out infinite;">🔥</div>
                        </div>
                        <div>
                            <p style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 1px; margin: 0;">
                                🔥 Streak</p>
                            <p style="font-size: 28px; font-weight: 800; color: white; margin: -2px 0 0 0; line-height: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                {{ $streakCount }} <span style="font-size: 14px; font-weight: 500;">hari</span>
                            </p>
                        </div>
                    </div>
                    
                    {{-- Status badge --}}
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                        @if ($kebiasaanHariIni)
                            @if ($streakCount > 0 && $semuaSudahIsi)
                                <div style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <span style="font-size: 11px; font-weight: 700; color: white;">🔥 Aktif</span>
                                </div>
                            @endif
                        @else
                            @if ($streakCount == 0)
                                <div style="background: rgba(239, 68, 68, 0.3); backdrop-filter: blur(10px); padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <span style="font-size: 11px; font-weight: 700; color: white;">💔 Putus</span>
                                </div>
                            @else
                                <div style="background: rgba(59, 130, 246, 0.3); backdrop-filter: blur(10px); padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <span style="font-size: 11px; font-weight: 700; color: white;">❄️ Beku</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Progress section --}}
                @if ($streakCount > 0)
                    <div style="margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 10px; color: rgba(255,255,255,0.85); font-weight: 500;">Target Mingguan</span>
                            <span style="font-size: 10px; font-weight: 700; color: white; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px;">{{ $streakCount }}/7</span>
                        </div>
                        <div style="height: 6px; background: rgba(0,0,0,0.15); border-radius: 10px; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);">
                            <div style="height: 100%; background: linear-gradient(90deg, #fff 0%, #fff8e7 100%); border-radius: 10px; width: {{ ($streakCount / 7) * 100 }}%; box-shadow: 0 0 10px rgba(255,255,255,0.5); transition: width 0.5s ease;">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Action row --}}
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    @if ($streakCount > 0 && !$kebiasaanHariIni)
                        {{-- Frozen warning --}}
                        <div style="flex: 1; display: flex; align-items: center; gap: 8px; background: rgba(59, 130, 246, 0.2); backdrop-filter: blur(10px); padding: 8px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                            <span style="font-size: 16px;">❄️</span>
                            <div>
                                <p style="margin: 0; font-size: 10px; font-weight: 700; color: white;">Streak Beku!</p>
                                <p style="margin: 0; font-size: 9px; color: rgba(255,255,255,0.9);">Isi kebiasaan hari ini</p>
                            </div>
                        </div>
                    @elseif ($streakCount == 0)
                        {{-- Broken streak with recover option --}}
                        <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                            @if ($canRecoverStreak)
                                <div style="display: flex; align-items: center; gap: 8px; background: rgba(239, 68, 68, 0.2); backdrop-filter: blur(10px); padding: 8px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                                    <span style="font-size: 16px;">💔</span>
                                    <div>
                                        <p style="margin: 0; font-size: 10px; font-weight: 700; color: white;">Streak Putus!</p>
                                        <p style="margin: 0; font-size: 9px; color: rgba(255,255,255,0.9);">Bisa dipulihkan</p>
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <button onclick="recoverStreak()" 
                                        style="display: flex; align-items: center; justify-content: center; gap: 6px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255,255,255,0.25); border-radius: 12px; padding: 6px 10px; font-size: 9px; font-weight: 500; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                                        onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-1px)'" 
                                        onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(0)'">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity: 0.9;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span style="letter-spacing: 0.5px;">PULIHKAN</span>
                                    </button>
                                    <div style="text-align: center;">
                                        <span style="font-size: 7px; color: rgba(255,255,255,0.7); font-weight: 400; letter-spacing: 0.3px; text-transform: uppercase;">
                                            {{ $maxRecoveryPerWeek - $recoveryCount }}/{{ $maxRecoveryPerWeek }} tersisa
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; gap: 8px; background: rgba(107, 114, 128, 0.2); backdrop-filter: blur(10px); padding: 8px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                                    <span style="font-size: 16px;">💀</span>
                                    <div>
                                        <p style="margin: 0; font-size: 10px; font-weight: 700; color: white;">Streak Mati!</p>
                                        <p style="margin: 0; font-size: 9px; color: rgba(255,255,255,0.9);">Terlambat 2+ hari</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Motivational text --}}
                        <div style="flex: 1;">
                            <p style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.9); font-style: italic;">
                                @if ($streakCount >= 7)
                                    🔥 Luar biasa! Teruskan!
                                @elseif ($streakCount >= 3)
                                    💪 Konsisten! Jangan berhenti!
                                @elseif ($streakCount > 0)
                                    ⭐ Semangat! Pertahankan!
                                @else
                                    🎯 Mulai streak pertamamu!
                                @endif
                            </p>
                        </div>
                    @endif

                    {{-- Download button --}}
                    <button onclick="downloadStreak(event)"
                        style="display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.95); color: #ff6b35; border: none; border-radius: 12px; padding: 8px 14px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.15);"
                        onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.2)'" 
                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17" />
                        </svg>
                        Share
                    </button>
                </div>
            </div>

            {{-- CSS Animations --}}
            <style>
                @keyframes firePulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                }
                @keyframes fireGlow {
                    0%, 100% { opacity: 0.3; transform: scale(1); }
                    50% { opacity: 0.6; transform: scale(1.2); }
                }
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            </style>
        </div>

        {{-- Debug Info for Streak for developmen --}}
        {{-- @if(isset($debugInfo))
            <div
                style="background: #1f2937; color: #e5e7eb; padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 11px; font-family: monospace;">
                <strong>Debug Streak:</strong><br>
                Today: {{ $debugInfo['today'] }}<br>
                Last Streak: {{ $debugInfo['last_streak_date'] ?? 'null' }}<br>
                Days Since: {{ $debugInfo['days_since_last_streak'] ?? 'null' }}<br>
                Last Kehabisan: {{ $debugInfo['last_kebiasaan_tanggal'] ?? 'null' }}<br>
                Streak Count: {{ $debugInfo['streak_count'] }}
            </div>
        @endif --}}

        {{-- ═══════════════════════════════════════════
        NOTIFIKASI CARD
        ════════════════════════════════════════════ --}}
        <div class="notif-gradient border border-blue-200 rounded-2xl p-5 anim-fade-up-4">
            <div class="flex items-start gap-4">

                {{-- Icon --}}
                <div class="flex items-center justify-center w-[50px] h-[50px] rounded-[14px]
                            notif-btn-gradient shrink-0
                            shadow-[0_6px_20px_rgba(37,99,235,0.22)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-[15px] font-extrabold text-gray-900 mb-1.5">Notifikasi Pengingat</h3>
                    <p class="text-[13px] text-gray-500 leading-relaxed mb-3.5">
                        Aktifkan notifikasi untuk mendapatkan pengingat mengisi kebiasaan harian.
                        Tidak ada spam, hanya pengingat penting saja.
                    </p>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach (['Pengingat Harian', 'Tanpa Spam', 'Bisa Dinonaktifkan'] as $tag)
                            <span class="inline-flex items-center gap-1 bg-white border border-blue-100
                                             rounded-lg px-2.5 py-1 text-[11px] font-semibold text-gray-600">
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>

                    {{-- CTA Button --}}
                    <button onclick="askForPermission()" class="inline-flex items-center gap-2 notif-btn-gradient text-white
                                   border-none rounded-[11px] px-5 py-2.5 text-[13px] font-bold
                                   cursor-pointer font-sans transition-all duration-200
                                   shadow-[0_4px_14px_rgba(37,99,235,0.28)]
                                   hover:-translate-y-0.5 hover:shadow-[0_8px_22px_rgba(37,99,235,0.35)]
                                   active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Aktifkan Notifikasi
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- end max-width wrapper --}}

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>
    <!-- html2canvas for screenshot -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

@endsection

@section('scripts')
    <script>
        /* ══ RECOVER STREAK ════════════════ */
        async function recoverStreak() {
            try {
                const response = await fetch('/student/api/streak/recover', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    if (result.message) {
                        // Create temporary success alert
                        const alert = document.createElement('div');
                        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #22c55e, #16a34a); color: white; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3); z-index: 10000; font-size: 14px; font-weight: 600; animation: slideIn 0.3s ease;';
                        alert.innerHTML = `✅ ${result.message}`;
                        document.body.appendChild(alert);
                        
                        // Remove alert after 3 seconds
                        setTimeout(() => {
                            alert.style.animation = 'slideOut 0.3s ease';
                            setTimeout(() => alert.remove(), 300);
                        }, 3000);
                    }
                    
                    // Reload page to show updated streak
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    const alert = document.createElement('div');
                    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); z-index: 10000; font-size: 14px; font-weight: 600; animation: slideIn 0.3s ease;';
                    alert.innerHTML = `❌ ${result.message || 'Gagal memulihkan streak'}`;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.style.animation = 'slideOut 0.3s ease';
                        setTimeout(() => alert.remove(), 300);
                    }, 3000);
                }
            } catch (error) {
                console.error('Error recovering streak:', error);
                const alert = document.createElement('div');
                alert.style.cssText = 'position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); z-index: 10000; font-size: 14px; font-weight: 600; animation: slideIn 0.3s ease;';
                alert.innerHTML = '❌ Terjadi kesalahan, coba lagi';
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }, 3000);
            }
        }

        /* ══ UNDUH RUNTUNAN ════════════════ */
        async function downloadStreak(event) {
            const streakCardShare = document.getElementById('streakCardShare');
            const sharePhotoImg = document.getElementById('sharePhotoImg');

            if (!streakCardShare) {
                console.log('Share card tidak ditemukan!');
                return;
            }

            // Show loading state
            const shareButton = event.target.closest('button') || event.target;
            const originalText = shareButton.innerHTML;
            shareButton.innerHTML = '⏳';
            shareButton.disabled = true;

            // Preload image if exists and wait for it to fully load
            if (sharePhotoImg && sharePhotoImg.src) {
                try {
                    await new Promise((resolve, reject) => {
                        const checkComplete = () => {
                            if (sharePhotoImg.complete && sharePhotoImg.naturalWidth > 0) {
                                console.log('Image loaded, natural size:', sharePhotoImg.naturalWidth, 'x', sharePhotoImg.naturalHeight);
                                resolve();
                            } else if (!sharePhotoImg.complete) {
                                sharePhotoImg.onload = () => {
                                    console.log('Image loaded via onload, size:', sharePhotoImg.naturalWidth, 'x', sharePhotoImg.naturalHeight);
                                    resolve();
                                };
                                sharePhotoImg.onerror = () => {
                                    console.log('Image load error, continuing...');
                                    resolve();
                                };
                            } else {
                                resolve();
                            }
                        };
                        // Check immediately and also after a short delay
                        checkComplete();
                        setTimeout(checkComplete, 50);
                    });
                } catch (e) {
                    console.log('Image preload warning:', e);
                }
            }

            // Wait longer for layout and images to fully settle
            await new Promise(resolve => setTimeout(resolve, 300));

            // Make visible for capture but keep off-screen
            const originalVisibility = streakCardShare.style.visibility;
            streakCardShare.style.visibility = 'visible';

            html2canvas(streakCardShare, {
                backgroundColor: '#ff6b35',
                scale: 3,
                useCORS: true,
                logging: true,
                allowTaint: true,
                width: 400,
                height: 500
            }).then(canvas => {
                console.log('Canvas generated, size:', canvas.width, 'x', canvas.height);
                // Restore visibility
                streakCardShare.style.visibility = originalVisibility;

                // Convert canvas to blob
                canvas.toBlob(blob => {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.download = `streak-${{{ $streakCount }}}-hari.png`;
                    link.href = url;
                    link.click();

                    // Cleanup
                    URL.revokeObjectURL(url);
                    shareButton.innerHTML = originalText;
                    shareButton.disabled = false;

                    // Download completed silently
                }, 'image/png');
            }).catch(err => {
                console.error('Error generating image:', err);

                // Restore visibility
                streakCardShare.style.visibility = originalVisibility;

                shareButton.innerHTML = originalText;
                shareButton.disabled = false;
                console.error('Error:', err.message);
            });
        }

        /* ══ VIRTUAL PET FUNCTIONS ════════════════ */
        function animatePet() {
            const petAvatar = document.getElementById('petAvatar');
            if (petAvatar) {
                // Jump animation
                petAvatar.style.transform = 'scale(1.2) translateY(-10px)';
                setTimeout(() => {
                    petAvatar.style.transform = 'scale(1) translateY(0)';
                }, 200);
                setTimeout(() => {
                    petAvatar.style.transform = 'scale(1.1) translateY(-5px)';
                }, 400);
                setTimeout(() => {
                    petAvatar.style.transform = 'scale(1) translateY(0)';
                }, 600);
            }
        }

        async function changePetForm(form) {
            try {
                const response = await fetch('/api/pet/change-form', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ form: form })
                });

                if (response.ok) {
                    // Reload page to show new form
                    window.location.reload();
                }
            } catch (e) {
                console.error('Error changing pet form:', e);
            }
        }

        // Drag functionality for pet popup
        let isDraggingPet = false;
        let petOffsetX = 0;
        let petOffsetY = 0;
        let petCurrentX = 0;
        let petCurrentY = 0;
        let petCard = null;

        // Unified drag handler for mouse, touch, and pointer events
        function handleDragStart(e) {
            // Don't drag if clicking on controls
            if (e.target.closest('select') || e.target.closest('span[onclick]')) return;

            petCard = document.getElementById('virtualPetCard');
            if (!petCard) return;

            isDraggingPet = true;
            const rect = petCard.getBoundingClientRect();

            // Get coordinates (pointer, touch, or mouse)
            const clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            const clientY = e.clientY || (e.touches && e.touches[0] ? e.touches[0].clientY : 0);

            // Calculate offset from the pet card's current position
            petOffsetX = clientX - rect.left;
            petOffsetY = clientY - rect.top;

            // Store current position for relative movement
            petCurrentX = rect.left;
            petCurrentY = rect.top;

            // Lock dimensions during drag to prevent stretching
            petCard.style.width = rect.width + 'px';
            petCard.style.height = rect.height + 'px';
            petCard.style.transition = 'none';
            petCard.style.cursor = 'grabbing';
            
            // Add subtle scale and shadow for drag feedback
            petCard.style.transform = 'scale(1.05)';
            petCard.style.boxShadow = '0 8px 25px rgba(0,0,0,0.25)';
            petCard.style.zIndex = '1000';

            // Lock body scroll on mobile
            if (e.type === 'touchstart') {
                document.body.style.overflow = 'hidden';
                document.body.style.touchAction = 'none';
            }

            // Aggressive prevent default for all event types
            if (e.cancelable) {
                e.preventDefault();
            }
            e.stopPropagation();
            e.stopImmediatePropagation && e.stopImmediatePropagation();
            return false;
        }

        function handleDragMove(e) {
            if (!isDraggingPet || !petCard) return;

            // Get coordinates (pointer, touch, or mouse)
            const clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
            const clientY = e.clientY || (e.touches && e.touches[0] ? e.touches[0].clientY : 0);

            // Calculate new position relative to current position
            let newX = petCurrentX + (clientX - petOffsetX - petCurrentX);
            let newY = petCurrentY + (clientY - petOffsetY - petCurrentY);

            // Keep within viewport bounds with smooth boundaries
            const maxX = window.innerWidth - petCard.offsetWidth;
            const maxY = window.innerHeight - petCard.offsetHeight;
            const padding = 10;
            newX = Math.max(padding, Math.min(newX, maxX - padding));
            newY = Math.max(padding, Math.min(newY, maxY - padding));

            // Update current position
            petCurrentX = newX;
            petCurrentY = newY;

            // Use translate3d for hardware acceleration with relative positioning
            petCard.style.transform = `translate3d(${newX}px, ${newY}px, 0) scale(1.05)`;
            petCard.style.left = '0';
            petCard.style.top = '0';
            petCard.style.right = 'auto';
            petCard.style.bottom = 'auto';

            // Aggressive prevent default for all event types
            if (e.cancelable) {
                e.preventDefault();
            }
            e.stopPropagation();
            e.stopImmediatePropagation && e.stopImmediatePropagation();
            return false;
        }

        function handleDragEnd(e) {
            if (!isDraggingPet || !petCard) return;

            isDraggingPet = false;
            
            // Use stored position instead of getBoundingClientRect
            const finalX = petCurrentX;
            const finalY = petCurrentY;
            
            // Animate back to normal scale and position
            petCard.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            petCard.style.transform = `translate3d(${finalX}px, ${finalY}px, 0) scale(1)`;
            petCard.style.cursor = 'grab';
            petCard.style.zIndex = '100';
            
            // Reset to fixed positioning after animation
            setTimeout(() => {
                petCard.style.transform = '';
                petCard.style.left = finalX + 'px';
                petCard.style.top = finalY + 'px';
                petCard.style.width = '';
                petCard.style.height = '';
                petCard.style.transition = 'box-shadow 0.3s ease';
            }, 300);

            // Restore body scroll
            document.body.style.overflow = '';
            document.body.style.touchAction = '';

            // Save position to localStorage
            localStorage.setItem('petCardPosition', JSON.stringify({
                left: finalX,
                top: finalY
            }));
        }

        // Attach events to pet drag handle (header only) after DOM is ready
        function initPetDragEvents() {
            const petCardElement = document.getElementById('virtualPetCard');
            const dragHandle = document.getElementById('petDragHandle');
            if (!petCardElement || !dragHandle) return;

            // Allow drag from header only
            const isMobile = window.innerWidth <= 640;
            const dragTarget = isMobile ? dragHandle : petCardElement;

            // Prevent default touch behaviors on drag target
            dragTarget.style.touchAction = 'none';
            dragTarget.style.userSelect = 'none';
            dragTarget.style.webkitUserSelect = 'none';

            // Use window-level capture for maximum control on mobile
            const useCapture = true;

            // Touch events - mobile only, attached to window for capture
            dragTarget.addEventListener('touchstart', handleDragStart, { passive: false, capture: useCapture });
            window.addEventListener('touchmove', handleDragMove, { passive: false, capture: useCapture });
            window.addEventListener('touchend', handleDragEnd, { capture: useCapture });
            window.addEventListener('touchcancel', handleDragEnd, { capture: useCapture });

            // Pointer events - modern browsers
            dragTarget.addEventListener('pointerdown', handleDragStart, { passive: false });
            window.addEventListener('pointermove', handleDragMove, { passive: false });
            window.addEventListener('pointerup', handleDragEnd);
            window.addEventListener('pointercancel', handleDragEnd);

            // Mouse events - desktop
            dragTarget.addEventListener('mousedown', handleDragStart);
            window.addEventListener('mousemove', handleDragMove);
            window.addEventListener('mouseup', handleDragEnd);
        }

        // Restore saved position (but reset on mobile if position is off-screen)
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize drag events first
            initPetDragEvents();

            petCard = document.getElementById('virtualPetCard');
            const savedPos = localStorage.getItem('petCardPosition');
            const isMobile = window.innerWidth <= 640;

            // Default position: bottom right, not covering content
            const resetToDefault = () => {
                if (petCard) {
                    petCard.style.left = 'auto';
                    petCard.style.top = 'auto';
                    petCard.style.right = '10px';
                    petCard.style.bottom = '80px';
                    petCard.style.transform = '';
                    petCard.style.width = '';
                    petCard.style.height = '';
                }
            };

            if (savedPos && petCard && !isMobile) {
                const pos = JSON.parse(savedPos);
                // Check if position is reasonable and not too far from edges
                const maxLeft = window.innerWidth - 150;
                const maxTop = window.innerHeight - 200;
                if (pos.left > 10 && pos.left < maxLeft && pos.top > 10 && pos.top < maxTop) {
                    petCard.style.left = pos.left + 'px';
                    petCard.style.top = pos.top + 'px';
                    petCard.style.right = 'auto';
                    petCard.style.bottom = 'auto';
                } else {
                    // Reset to default if position is weird
                    resetToDefault();
                    localStorage.removeItem('petCardPosition');
                }
            } else {
                // Mobile or no saved position: use default
                resetToDefault();
                if (isMobile) localStorage.removeItem('petCardPosition');
            }

            // Ensure pet is visible within viewport
            setTimeout(() => {
                if (petCard) {
                    const rect = petCard.getBoundingClientRect();
                    const viewportWidth = window.innerWidth;
                    const viewportHeight = window.innerHeight;

                    // If off-screen, reset to default
                    if (rect.right > viewportWidth + 10 || rect.bottom > viewportHeight + 10 || rect.left < -10 || rect.top < -10) {
                        resetToDefault();
                    }
                }
            }, 100);

            setTimeout(animatePet, 1000);
        });

        // Toggle pet size (minimize/maximize)
        function togglePetSize(e) {
            e.stopPropagation();
            const content = document.getElementById('petContent');
            const btn = e.target;
            if (content.style.display === 'none') {
                content.style.display = 'block';
                btn.textContent = '➖';
            } else {
                content.style.display = 'none';
                btn.textContent = '➕';
            }
        }

        // Close pet (hide)
        function closePet(e) {
            e.stopPropagation();
            const petCard = document.getElementById('virtualPetCard');
            if (petCard) {
                petCard.style.display = 'none';
                // Show a floating button to bring it back
                showPetToggleButton();
            }
        }

        // Show button to bring pet back
        function showPetToggleButton() {
            let btn = document.getElementById('petToggleBtn');
            if (!btn) {
                btn = document.createElement('button');
                btn.id = 'petToggleBtn';
                btn.innerHTML = '🎮';
                btn.style.cssText = 'position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: white; font-size: 20px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 9998; transition: transform 0.2s;';
                btn.onclick = function () {
                    const petCard = document.getElementById('virtualPetCard');
                    if (petCard) {
                        petCard.style.display = 'block';
                        this.style.display = 'none';
                    }
                };
                document.body.appendChild(btn);
            } else {
                btn.style.display = 'block';
            }
        }

        /* ══ FIREBASE PUSH NOTIFICATION ════════════════ */
        const firebaseConfig = {
            apiKey: "AIzaSyA-AM4wp75BPE6qO_qpCOBJhI5Al20MAJ0",
            authDomain: "kaih-96705.firebaseapp.com",
            projectId: "kaih-96705",
            storageBucket: "kaih-96705.firebasestorage.app",
            messagingSenderId: "483886147031",
            appId: "1:483886147031:web:50feb71270712893dc1792"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        messaging.onMessage((payload) => {
            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: '/img/logo-1.png'
            });
        });

        function askForPermission() {
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    messaging.getToken({
                        vapidKey: 'BKF8hImiWIPNnOgb-jVu9IEV9mXCUR_y0OcbAMpXbSpVK3CImtAHg-hD9RQ_tV41vPvSAHq8RWMIS4K6wj46Rck'
                    }).then((currentToken) => {
                        if (currentToken) {
                            fetch('/save-token', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    token: currentToken
                                })
                            })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.success) alert('Notifikasi berhasil diaktifkan!');
                                });
                        }
                    }).catch(err => console.log('Error Token:', err));
                } else {
                    alert('Izin ditolak. Silakan aktifkan manual dari pengaturan browser (ikon gembok).');
                }
            });
        }
    </script>
@endsection