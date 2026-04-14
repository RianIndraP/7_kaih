<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    @if (str_contains(request()->header('host'), 'ngrok-free.dev') || str_contains(request()->header('host'), 'ngrok.io'))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'SMK N 5 Telkom Banda Aceh')</title>
    @vite('resources/css/app.css')
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .nav-active {
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        .sidebar-logo {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="flex relative min-h-screen">

        {{-- ========== MOBILE OVERLAY ========== --}}
        <div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        {{-- ========== SIDEBAR GURU ========== --}}
        <div id="sidebar"
            class="sidebar-transition fixed lg:static lg:translate-x-0 -translate-x-full
                w-64 bg-white min-h-screen border-r border-gray-200 shadow-md z-50 flex-shrink-0">
            <div class="p-6 flex flex-col h-full">

                {{-- Mobile close --}}
                <button id="mobileCloseBtn"
                    class="lg:hidden absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-800
                           rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Brand --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="size-20 sidebar-logo rounded-xl flex items-center justify-center mb-3">
                        <img src="{{ asset('img/logo-1.png') }}" alt="Logo SMK Negeri 5" class="size-[76px] object-contain">
                    </div>
                    <h2 class="text-sm font-bold text-gray-900 text-center leading-tight">SMK NEGERI 5</h2>
                    <p class="text-xs text-gray-500 text-center">TELKOM BANDA ACEH</p>
                </div>

                {{-- Navigasi Guru --}}
                <nav class="space-y-1 flex-1">

                    <a href="{{ route('guru.dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                          {{ request()->routeIs('guru.dashboard') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1
                                 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1
                                 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('guru.profil') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                          {{ request()->routeIs('guru.profil') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil Guru
                    </a>

                    <a href="{{ route('guru.list-murid') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                          {{ request()->routeIs('guru.list-murid') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        List murid
                    </a>

                    <a href="{{ route('guru.absensi-murid') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                          {{ request()->routeIs('guru.absensi-murid') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Absensi murid
                    </a>

                    <a href="{{ route('guru.pelaporan') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                          {{ request()->routeIs('guru.pelaporan') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Pelaporan
                    </a>

                    {{-- Logout --}}
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-guru').submit();"
                        class="flex items-center gap-3 text-red-500 hover:bg-red-50 rounded-lg
                          px-3 py-2.5 text-sm transition-colors">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7
                                 a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </a>

                </nav>
            </div>
        </div>

        {{-- Hidden logout form --}}
        <form id="logout-form-guru" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

        {{-- ========== MAIN ========== --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- ========== HEADER GURU ========== --}}
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="px-6 py-3 flex items-center justify-between">

                    {{-- Kiri: hamburger + judul --}}
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <button id="mobileMenuBtn"
                            class="lg:hidden p-2 text-gray-500 hover:text-gray-800 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-base font-semibold text-gray-900 truncate">
                            @if (request()->routeIs('guru.dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('guru.profil'))
                                Profil Guru
                            @elseif(request()->routeIs('guru.list-murid'))
                                List Murid
                            @elseif(request()->routeIs('guru.absensi-murid'))
                                Absensi Murid
                            @elseif(request()->routeIs('guru.pelaporan'))
                                Pelaporan
                            @else
                                {{ $pageTitle ?? 'Dashboard Guru' }}
                            @endif
                        </h1>
                    </div>

                    {{-- Kanan: ikon aksi --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        {{-- ⚙️ Gear → Ganti Password --}}
                        <a href="{{ route('guru.ganti-password') }}"
                           title="Ganti Password"
                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0
                                         a1.724 1.724 0 002.573 1.066
                                         c1.543-.94 3.31.826 2.37 2.37
                                         a1.724 1.724 0 001.065 2.572
                                         c1.756.426 1.756 2.924 0 3.35
                                         a1.724 1.724 0 00-1.066 2.573
                                         c.94 1.543-.826 3.31-2.37 2.37
                                         a1.724 1.724 0 00-2.572 1.065
                                         c-.426 1.756-2.924 1.756-3.35 0
                                         a1.724 1.724 0 00-2.573-1.066
                                         c-1.543.94-3.31-.826-2.37-2.37
                                         a1.724 1.724 0 00-1.065-2.572
                                         c-1.756-.426-1.756-2.924 0-3.35
                                         a1.724 1.724 0 001.066-2.573
                                         c-.94-1.543.826-3.31 2.37-2.37
                                         .996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>

                        {{-- ❓ Tanda Tanya → Kirim Pesan Bantuan --}}
                        <a href="{{ route('guru.kirim-pesan-bantuan') }}"
                           title="Kirim Pesan Bantuan"
                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8.228 9c.549-1.165 2.03-2 3.772-2
                                         2.21 0 4 1.343 4 3
                                         0 1.4-1.278 2.575-3.006 2.907
                                         -.542.104-.994.54-.994 1.093
                                         m0 3h.01
                                         M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            {{-- ========== PAGE CONTENT ========== --}}
            <main class="flex-1">
                @yield('content')
            </main>

        </div>{{-- end main --}}
    </div>
    
    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        mobileMenuBtn?.addEventListener('click', openSidebar);
        mobileCloseBtn?.addEventListener('click', closeSidebar);
        mobileOverlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }
    </script>

</body>

</html>
