<!-- Mobile Overlay -->
<div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<!-- Sidebar -->
<div id="sidebar"
    class="sidebar-transition fixed lg:relative lg:translate-x-0 -translate-x-full w-64 sidebar-gradient min-h-screen shadow-xl z-40">
    <div class="p-6">
        <!-- Mobile Close Button -->
        <button id="mobileCloseBtn" class="lg:hidden absolute top-4 right-4 p-2 text-white/80 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="flex flex-col items-center mb-8">
            <div class="size-20 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center mb-4 border border-white/30">
                <img src="{{ asset('img/logo-1.png') }}" alt="Logo SMK Negeri 5" class="size-[76px] object-contain">
            </div>
            <h2 class="text-lg font-bold text-white text-center">SMK NEGERI 5</h2>
            <p class="text-sm text-white/80 text-center">TELKOM BANDA ACEH</p>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1">
            <a href="{{ route('student.dashboard') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-white font-medium' : 'text-white/80 hover:text-white hover:bg-white/10' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('student.profile') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.profile*') ? 'bg-white/20 text-white font-medium' : 'text-white/80 hover:text-white hover:bg-white/10' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Siswa</span>
            </a>
            {{-- 7 Kebiasaan - Locked if profile incomplete --}}
            @if (auth()->user()->isProfileComplete())
                <a href="{{ route('student.kebiasaan') }}"
                    class="flex items-center space-x-3 {{ request()->routeIs('student.kebiasaan') ? 'bg-white/20 text-white font-medium' : 'text-white/80 hover:text-white hover:bg-white/10' }} rounded-lg p-3 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <span>7 Kebiasaan</span>
                </a>
            @else
                <div class="flex items-center space-x-3 text-white/50 bg-white/5 rounded-lg p-3 cursor-not-allowed opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <span class="flex-1">7 Kebiasaan</span>
                    <svg class="w-4 h-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @endif

            {{-- Pesan Dari Guru Wali - Locked if profile incomplete --}}
            @if (auth()->user()->isProfileComplete())
                @php
                    $unreadCount = \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
                        ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                        ->count();
                @endphp
                <a href="{{ route('student.pesan') }}"
                    class="flex items-center space-x-3 {{ request()->routeIs('student.pesan') ? 'bg-white/20 text-white font-medium' : 'text-white/80 hover:text-white hover:bg-white/10' }} rounded-lg p-3 transition-colors">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        @if ($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-md">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </div>
                    <span>Pesan Dari Guru Wali</span>
                </a>
            @else
                <div class="flex items-center space-x-3 text-white/50 bg-white/5 rounded-lg p-3 cursor-not-allowed opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="flex-1">Pesan Dari Guru Wali</span>
                    <svg class="w-4 h-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @endif
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center gap-3 text-red-300 hover:bg-red-500/20 rounded-lg
                  px-3 py-2.5 text-sm transition-colors cursor-pointer">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7
                         a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </a>
        </nav>
    </div>
</div>

<!-- Logout Form (Hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
