<!-- Mobile Overlay -->
<div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<!-- Sidebar -->
<div id="sidebar"
    class="sidebar-transition fixed lg:relative lg:translate-x-0 -translate-x-full w-64 bg-white min-h-screen shadow-md border-r border-gray-200 z-40">
    <div class="p-6">
        <!-- Mobile Close Button -->
        <button id="mobileCloseBtn" class="lg:hidden absolute top-4 right-4 p-2 text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 sidebar-logo rounded-lg flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 text-center">SMK NEGERI 5</h2>
            <p class="text-sm text-gray-600 text-center">TELKOM BANDA ACEH</p>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1">
            <a href="{{ route('student.dashboard') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.dashboard') ? 'nav-active' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('student.profile') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.profile*') ? 'nav-active' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Siswa</span>
            </a>
            <a href="{{ route('student.kebiasaan') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.kebiasaan') ? 'nav-active' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span>7 Kebiasaan</span>
            </a>
            <a href="{{ route('student.pesan') }}"
                class="flex items-center space-x-3 {{ request()->routeIs('student.pesan') ? 'nav-active' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }} rounded-lg p-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                <span>Pesan Dari Guru Wali</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center space-x-3 text-red-600 hover:bg-red-50 rounded-lg p-3 transition-colors mt-8 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span>Keluar</span>
            </a>
        </nav>
    </div>
</div>

<!-- Logout Form (Hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
