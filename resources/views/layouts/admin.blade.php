<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'Admin | SMK N 5 Telkom Banda Aceh')</title>
    @vite('resources/css/app.css')
    <style>
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        .nav-active { background-color: #eff6ff; color: #1d4ed8; }
        .sidebar-logo { background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%); }
        .card-shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .inline-edit {
            background: transparent;
            border: none;
            outline: none;
            transition: all 0.2s ease;
        }
        .inline-edit:hover {
            background: #f3f4f6;
            border-radius: 4px;
            padding: 2px 4px;
        }
        .inline-edit:focus {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 2px 4px;
            box-shadow: 0 0 0 1px #3b82f6;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex relative min-h-screen">
        
        {{-- ========== MOBILE OVERLAY ========== --}}
        <div id="mobileOverlay"
             class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

        {{-- ========== SIDEBAR ADMIN ========== --}}
        <div id="sidebar"
             class="sidebar-transition fixed lg:static lg:translate-x-0 -translate-x-full
                    w-64 bg-white min-h-screen border-r border-gray-200 shadow-md z-50 shrink-0">
            <div class="p-6 flex flex-col h-full">

                {{-- Mobile close --}}
                <button id="mobileCloseBtn"
                        class="lg:hidden absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-800
                               rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Brand --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="w-14 h-14 sidebar-logo rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9
                                     0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1
                                     1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900 text-center leading-tight">SMK NEGERI 5</h2>
                    <p class="text-xs text-gray-500 text-center">TELKOM BANDA ACEH</p>
                </div>

                {{-- Navigasi Admin --}}
                <nav class="space-y-1 flex-1">

                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.dashboard') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1
                                     1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.profil') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.profil') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Operator
                    </a>

                    <a href="{{ route('admin.siswa') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.siswa*') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                                     20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0
                                     019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2
                                     2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Manajemen Siswa
                    </a>

                    <a href="{{ route('admin.guru') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.guru*') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Manajemen Guru
                    </a>

                    <a href="{{ route('admin.kebiasaan') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.kebiasaan*') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2
                                     2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Data 7 Kebiasaan
                    </a>

                    <a href="{{ route('admin.pesan-bantuan') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                              {{ request()->routeIs('admin.pesan-bantuan*') ? 'nav-active font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pesan Bantuan
                    </a>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm
                                       text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7
                                         a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 lg:ml-0 min-w-0">
            
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button id="mobileMenuBtn"
                                class="lg:hidden p-2 text-gray-500 hover:text-gray-800 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-base font-semibold text-gray-900">
                            @yield('page_title', 'Dashboard')
                        </h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.pengaturan') }}"
                           class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94
                                         3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724
                                         1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572
                                         1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31
                                         -.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724
                                         1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-6 bg-gray-50 min-h-screen">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        var sidebar       = document.getElementById('sidebar');
        var mobileMenuBtn = document.getElementById('mobileMenuBtn');
        var mobileClose   = document.getElementById('mobileCloseBtn');
        var overlay       = document.getElementById('mobileOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        mobileMenuBtn?.addEventListener('click', openSidebar);
        mobileClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
        if (window.innerWidth < 1024) sidebar.classList.add('-translate-x-full');
    </script>

    @yield('scripts')
</body>
</html>