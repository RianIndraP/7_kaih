{{-- ── HEADER ADMIN ── --}}
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
                'admin.dashboard' => 'Dashboard',
                'admin.profil' => 'Profil Operator',
                'admin.siswa*' => 'Manajemen Siswa',
                'admin.guru*' => 'Manajemen Guru',
                'admin.kelas*' => 'Manajemen Kelas',
                'admin.kuis*' => 'Manajemen Kuis',
                'admin.kebiasaan*' => 'Data 7 Kebiasaan',
                'admin.pesan-bantuan*' => 'Pesan Bantuan',
                'admin.manajemen-website*' => 'Manajemen Website',
                'admin.pengaturan*' => 'Pengaturan',
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
        @php
            $headerActions = [
                [
                    'href' => route('admin.pengaturan'),
                    'title' => 'Pengaturan',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z|M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                ],
            ];
        @endphp
        @foreach ($headerActions as $action)
            <a href="{{ $action['href'] }}" title="{{ $action['title'] }}" class="flex items-center justify-center w-8 h-8 rounded-[9px]
                              bg-white/15 text-white no-underline
                              hover:bg-white/26 hover:scale-[1.08] transition-all duration-200">
                <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @foreach (explode('|', $action['icon']) as $p)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p }}" />
                    @endforeach
                </svg>
            </a>
        @endforeach
    </div>
</header>