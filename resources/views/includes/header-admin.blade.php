{{-- ── APP HEADER ── --}}
<header
    class="header-gradient sticky top-0 z-30 flex items-center justify-between
               px-5 lg:px-6 h-[58px] shrink-0
               shadow-[0_2px_18px_rgba(37,99,235,0.22)] print:hidden">

    {{-- Left: hamburger + page title --}}
    <div class="flex items-center gap-2.5 min-w-0">

        {{-- Hamburger (mobile only) --}}
        <button id="hamburgerBtn"
            class="lg:hidden flex items-center justify-center w-9 h-9 rounded-[9px]
                       bg-white/15 border-none text-white cursor-pointer shrink-0
                       hover:bg-white/25 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-[15px] font-bold text-white truncate">
            @if (request()->routeIs('admin.dashboard'))
                Dashboard
            @elseif(request()->routeIs('admin.profil'))
                Profil Operator
            @elseif(request()->routeIs('admin.siswa*'))
                Manajemen Siswa
            @elseif(request()->routeIs('admin.guru*'))
                Manajemen Guru
            @elseif(request()->routeIs('admin.kelas*'))
                Manajemen Kelas
            @elseif(request()->routeIs('admin.kebiasaan*'))
                Data 7 Kebiasaan
            @elseif(request()->routeIs('admin.pesan-bantuan*'))
                Pesan Bantuan
            @else
                {{ $pageTitle ?? 'Dashboard' }}
            @endif
        </h1>
    </div>

    {{-- Right: action buttons --}}
    <div class="flex items-center gap-1 shrink-0">

        {{-- Gear: Pengaturan --}}
        <a href="{{ route('admin.pengaturan') }}" title="Pengaturan"
            class="flex items-center justify-center w-9 h-9 rounded-[9px]
                  bg-white/15 text-white no-underline
                  hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>

    </div>
</header>
