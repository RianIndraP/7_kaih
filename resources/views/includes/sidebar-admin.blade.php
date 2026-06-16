{{-- ── LOGO ── --}}
<div class="flex items-center gap-3 px-4 pt-5 pb-4 border-b border-white/10 relative">

    <div
        class="w-10 h-10 rounded-xl bg-white/18 border border-white/28 overflow-hidden flex items-center justify-center shrink-0">
        <img src="{{ asset('img/logo-1.png') }}" alt="Logo" class="w-9 h-9 object-contain">
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-[13px] font-extrabold text-white leading-tight">SMK Negeri 5</p>
        <p class="text-[11px] text-white/55 leading-tight">Telkom Banda Aceh</p>
    </div>

    <button id="sbCloseBtn" class="lg:hidden flex items-center justify-center w-7 h-7 rounded-lg
               bg-white/15 text-white border-none cursor-pointer shrink-0
               hover:bg-white/25 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

{{-- ── NAV ── --}}
<nav class="flex-1 flex flex-col px-2.5 py-3 overflow-y-auto sb-scroll gap-0.5">

    @php
        $link = fn(string $route, string $label, string $icon, string $match) =>
            view()->make('includes._sb-link', compact('route', 'label', 'icon', 'match'))->render();

        $groups = [
            null => [
                ['admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'admin.dashboard'],
                ['admin.profil', 'Profil Operator', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'admin.profil*'],
            ],
            'Manajemen' => [
                ['admin.siswa', 'Siswa', 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'admin.siswa*'],
                ['admin.guru', 'Guru', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'admin.guru*'],
                ['admin.kelas', 'Kelas', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'admin.kelas*'],
                ['admin.kuis', 'Manajemen Kuis', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'admin.kuis*'],
            ],
            'Data & Laporan' => [
                ['admin.kebiasaan', 'Data 7 Kebiasaan', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'admin.kebiasaan*'],
                ['admin.pesan-bantuan', 'Pesan Bantuan', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'admin.pesan-bantuan*'],
            ],
            'Sistem' => [
                ['admin.manajemen-website', 'Manajemen Website', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'admin.manajemen-website*'],
            ],
        ];
    @endphp

    @foreach ($groups as $groupLabel => $items)

        @if ($groupLabel)
            <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-white/40 uppercase tracking-widest">
                {{ $groupLabel }}
            </p>
        @endif

        @foreach ($items as [$routeName, $label, $iconPath, $matchPattern])
            @php $isActive = request()->routeIs($matchPattern); @endphp
            <a href="{{ route($routeName) }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px]
                                       text-[13px] font-medium no-underline transition-all duration-200
                                       {{ $isActive
                    ? 'bg-white/22 text-white font-bold nav-active-accent'
                    : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
                <svg class="w-[17px] h-[17px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @foreach (explode(' M', $iconPath) as $i => $part)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="{{ $i === 0 ? $part : 'M' . $part }}" />
                    @endforeach
                </svg>
                <span class="flex-1">{{ $label }}</span>
                @if ($isActive)
                    <span class="w-1.5 h-1.5 rounded-full bg-white/60 shrink-0"></span>
                @endif
            </a>
        @endforeach

    @endforeach
    {{-- ── LOGOUT ── --}}
    <div class="px-2.5 pb-3 border-t border-white/10 pt-2.5">
        <button onclick="document.getElementById('logout-form').submit()" class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-[11px]
               text-[13px] font-medium text-red-300/80 bg-transparent border-none
               cursor-pointer transition-all duration-200 font-sans
               hover:bg-red-500/18 hover:text-red-300">
            <svg class="w-[17px] h-[17px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </button>
    </div>

</nav>