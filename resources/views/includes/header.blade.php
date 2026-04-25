{{-- ── APP HEADER ── --}}
<header
    class="header-gradient sticky top-0 z-30 flex items-center justify-between
               px-5 lg:px-6 h-[58px] shrink-0
               shadow-[0_2px_18px_rgba(37,99,235,0.22)]">

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
            @if (request()->routeIs('student.dashboard'))
                Dashboard
            @elseif(request()->routeIs('student.profile'))
                Profil Siswa
            @elseif(request()->routeIs('student.kebiasaan'))
                7 Kebiasaan Anak Indonesia Hebat
            @elseif(request()->routeIs('student.pesan'))
                Pesan Dari Guru Wali
            @elseif(request()->routeIs('student.ganti-password'))
                Ganti Password
            @elseif(request()->routeIs('student.kirim-pesan-bantuan'))
                Kirim Pesan Bantuan
            @elseif(request()->routeIs('guru.dashboard'))
                Dashboard Guru
            @elseif(request()->routeIs('guru.profil'))
                Profil Guru
            @elseif(request()->routeIs('guru.list-murid'))
                List Murid
            @elseif(request()->routeIs('guru.absensi-murid'))
                Absensi Murid
            @elseif(request()->routeIs('guru.pelaporan'))
                Pelaporan
            @else
                {{ $pageTitle ?? 'Dashboard' }}
            @endif
        </h1>
    </div>

    {{-- Right: action buttons --}}
    <div class="flex items-center gap-1 shrink-0">

        @if (auth()->user()->isSiswa())

            {{-- Bell: Pesan Guru Wali --}}
            @if (auth()->user()->isProfileComplete())
                @php
                    $unreadPesan = \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
                        ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                        ->count();
                @endphp
                <a href="{{ route('student.pesan') }}" title="Pesan dari Guru Wali"
                    class="relative flex items-center justify-center w-9 h-9 rounded-[9px]
                          bg-white/15 text-white no-underline
                          hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if ($unreadPesan > 0)
                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full
                                     border-2 border-indigo-600"></span>
                    @endif
                </a>
            @else
                <div class="flex items-center justify-center w-9 h-9 rounded-[9px]
                            bg-white/10 text-white/42 opacity-42 cursor-not-allowed"
                    title="Profil belum lengkap">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            @endif

            {{-- Gear: Ganti Password --}}
            @if (auth()->user()->isProfileComplete())
                <a href="{{ route('student.ganti-password') }}" title="Ganti Password"
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
            @else
                <div class="flex items-center justify-center w-9 h-9 rounded-[9px]
                            bg-white/10 text-white/42 opacity-42 cursor-not-allowed"
                    title="Profil belum lengkap">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            @endif

            {{-- Help: Kirim Pesan Bantuan --}}
            <a href="{{ route('student.kirim-pesan-bantuan') }}" title="Kirim Pesan Bantuan"
                class="flex items-center justify-center w-9 h-9 rounded-[9px]
                      bg-white/15 text-white no-underline
                      hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>

        @endif

        @if (auth()->user()->isGuru())
            {{-- Gear: Ganti Password (Guru) --}}
            <a href="{{ route('student.ganti-password') }}" title="Ganti Password"
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

            {{-- Help: Kirim Pesan Bantuan (Guru) --}}
            <a href="{{ route('student.kirim-pesan-bantuan') }}" title="Kirim Pesan Bantuan"
                class="flex items-center justify-center w-9 h-9 rounded-[9px]
                      bg-white/15 text-white no-underline
                      hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        @endif

    </div>
</header>
