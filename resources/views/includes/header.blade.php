{{-- ── HEADER SISWA ── --}}
<header class="header-gradient sticky top-0 z-30 flex items-center justify-between
               px-4 lg:px-6 h-[56px] shrink-0
               shadow-[0_2px_18px_rgba(37,99,235,0.22)] print:hidden">

    {{-- Left --}}
    <div class="flex items-center gap-2.5 min-w-0">
        <button id="hamburgerBtn" class="lg:hidden flex items-center justify-center w-8 h-8 rounded-[9px]
                   bg-white/15 border-none text-white cursor-pointer shrink-0
                   hover:bg-white/25 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @php
            $titles = [
                'student.dashboard' => 'Dashboard',
                'student.profile*' => 'Profil Siswa',
                'student.kebiasaan*' => '7 Kebiasaan',
                'student.pesan*' => 'Pesan Guru Wali',
                'student.ganti-password*' => 'Ganti Password',
                'student.kirim-pesan-bantuan*' => 'Kirim Pesan Bantuan',
                'student.kuis*' => 'Kuis Literasi & Numerasi',
            ];
            $pageLabel = $pageTitle ?? 'Dashboard';
            foreach ($titles as $pattern => $label) {
                if (request()->routeIs($pattern)) {
                    $pageLabel = $label;
                    break;
                }
            }
            $complete = auth()->user()->isProfileComplete();
            $unread = $complete
                ? \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
                    ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                    ->count()
                : 0;
        @endphp
        <h1 class="text-[14.5px] font-bold text-white truncate">{{ $pageLabel }}</h1>
    </div>

    {{-- Right --}}
    <div class="flex items-center gap-1 shrink-0">

        {{-- Bell: Pesan Guru Wali --}}
        @if ($complete)
            <a href="{{ route('student.pesan') }}" title="Pesan Guru Wali" class="relative flex items-center justify-center w-8 h-8 rounded-[9px]
                          bg-white/15 text-white no-underline
                          hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if ($unread > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-indigo-600"></span>
                @endif
            </a>
        @else
            <div class="flex items-center justify-center w-8 h-8 rounded-[9px] bg-white/8 text-white/35 cursor-not-allowed"
                title="Lengkapi profil dulu">
                <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        @endif

        {{-- Ganti Password --}}
        @if ($complete)
            <a href="{{ route('student.ganti-password') }}" title="Ganti Password" class="flex items-center justify-center w-8 h-8 rounded-[9px]
                          bg-white/15 text-white no-underline
                          hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </a>
        @else
            <div class="flex items-center justify-center w-8 h-8 rounded-[9px] bg-white/8 text-white/35 cursor-not-allowed"
                title="Lengkapi profil dulu">
                <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        @endif

        {{-- Kirim Pesan Bantuan --}}
        <a href="{{ route('student.kirim-pesan-bantuan') }}" title="Kirim Pesan Bantuan" class="flex items-center justify-center w-8 h-8 rounded-[9px]
                  bg-white/15 text-white no-underline
                  hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

    </div>
</header>