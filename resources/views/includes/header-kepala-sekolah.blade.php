{{-- ── HEADER KEPALA SEKOLAH ── --}}
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
                'kepala-sekolah.dashboard' => 'Dashboard',
                'kepala-sekolah.profil*' => 'Profil Kepala Sekolah',
                'kepala-sekolah.data-kebiasaan*' => 'Data Kebiasaan',
                'kepala-sekolah.data-guru-wali*' => 'Data Guru Wali',
                'kepala-sekolah.data-kelas*' => 'Data Kelas',
                'kepala-sekolah.ganti-password*' => 'Ganti Password',
                'kepala-sekolah.kirim-pesan-bantuan*' => 'Kirim Pesan Bantuan',
            ];
            $pageLabel = $pageTitle ?? 'Dashboard';
            foreach ($titles as $pattern => $label) {
                if (request()->routeIs($pattern)) {
                    $pageLabel = $label;
                    break;
                }
            }
        @endphp
        <h1 class="text-[14.5px] font-bold text-white truncate">{{ $pageLabel }}</h1>
    </div>

    {{-- Right --}}
    <div class="flex items-center gap-1 shrink-0">

        {{-- Ganti Password --}}
        <a href="{{ route('kepala-sekolah.ganti-password') }}" title="Ganti Password" class="flex items-center justify-center w-8 h-8 rounded-[9px]
                  bg-white/15 text-white no-underline
                  hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </a>

        {{-- Kirim Pesan Bantuan --}}
        <a href="{{ route('kepala-sekolah.kirim-pesan-bantuan') }}" title="Kirim Pesan Bantuan" class="flex items-center justify-center w-8 h-8 rounded-[9px]
                  bg-white/15 text-white no-underline
                  hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </a>

    </div>
</header>