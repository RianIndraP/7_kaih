{{-- ══ LOGO ══ --}}
<div class="flex items-center gap-3 px-4 pt-5 pb-4 border-b border-white/10 relative">
    <div
        class="w-10 h-10 rounded-xl bg-white/18 border border-white/28 overflow-hidden flex items-center justify-center shrink-0">
        <img src="{{ asset('img/logo-1.png') }}" alt="Logo" class="w-9 h-9 object-contain">
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-[13px] font-extrabold text-white leading-tight">SMK Negeri 5</p>
        <p class="text-[11px] text-white/55 leading-tight">Telkom Banda Aceh</p>
    </div>
    <button id="sbCloseBtn"
        class="lg:hidden flex items-center justify-center w-7 h-7 rounded-lg bg-white/15 text-white border-none cursor-pointer shrink-0 hover:bg-white/25 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

{{-- ══ NAV ══ --}}
@php
    $complete = auth()->user()->isProfileComplete();

    $unreadCount = $complete
        ? \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
            ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
            ->count()
        : 0;

    $navGroups = [
        null => [
            ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'match' => 'student.dashboard', 'locked' => false, 'badge' => null],
            ['route' => 'student.profile', 'label' => 'Profil Siswa', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'match' => 'student.profile*', 'locked' => false, 'badge' => null],
        ],
        'Akademik' => [
            ['route' => 'student.kebiasaan', 'label' => '7 Kebiasaan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'match' => 'student.kebiasaan', 'locked' => !$complete, 'badge' => null],
            ['route' => 'student.pesan', 'label' => 'Pesan Guru Wali', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'match' => 'student.pesan', 'locked' => !$complete, 'badge' => ($unreadCount > 0 ? ($unreadCount > 9 ? '9+' : $unreadCount) : null)],
            ['route' => 'student.kuis', 'label' => 'Kuis Literasi & Numerasi', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'match' => 'student.kuis*', 'locked' => !$complete, 'badge' => null],
        ],
        'Lainnya' => [
            ['route' => 'student.ganti-password', 'label' => 'Ganti Password', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', 'match' => 'student.ganti-password*', 'locked' => false, 'badge' => null],
            ['route' => 'student.kirim-pesan-bantuan', 'label' => 'Kirim Pesan Bantuan', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'match' => 'student.kirim-pesan-bantuan*', 'locked' => false, 'badge' => null],
        ],
    ];
@endphp

<nav class="flex-1 flex flex-col px-2.5 py-3 overflow-y-auto sb-scroll gap-0.5">
    @foreach ($navGroups as $group => $items)
        @if ($group)
            <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-white/40 uppercase tracking-widest">{{ $group }}</p>
        @endif
        @foreach ($items as $item)
            @php $isActive = request()->routeIs($item['match']); @endphp
            @if ($item['locked'])
                <div
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium opacity-40 cursor-not-allowed text-white/72 bg-white/5">
                    <svg class="w-[17px] h-[17px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                    </svg>
                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                    <svg class="w-3 h-3 shrink-0 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @else
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium no-underline transition-all duration-200 {{ $isActive ? 'bg-white/22 text-white font-bold nav-active-accent' : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
                    <svg class="w-[17px] h-[17px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                    </svg>
                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                    @if ($item['badge'])
                        <span
                            class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none shrink-0">{{ $item['badge'] }}</span>
                    @elseif ($isActive)
                        <span class="w-1.5 h-1.5 rounded-full bg-white/60 shrink-0"></span>
                    @endif
                </a>
            @endif
        @endforeach
    @endforeach
</nav>

{{-- ══ LOGOUT ══ --}}
<div class="px-2.5 pb-3 pt-2.5 border-t border-white/10 shrink-0">
    <button onclick="document.getElementById('logout-form').submit()"
        class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-[11px] text-[13px] font-medium text-red-300/80 bg-transparent border-none cursor-pointer transition-all duration-200 font-sans hover:bg-red-500/18 hover:text-red-300">
        <svg class="w-[17px] h-[17px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Keluar
    </button>
</div>