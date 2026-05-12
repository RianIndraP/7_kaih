{{-- ── SIDEBAR LOGO ── --}}
<div class="relative flex flex-col items-center gap-2.5 px-5 pt-6 pb-5
            border-b border-white/10">

    {{-- Mobile close button --}}
    <button id="sbCloseBtn"
        class="lg:hidden absolute top-3.5 right-3.5 flex items-center justify-center
                   w-8 h-8 rounded-lg bg-white/15 text-white border-none cursor-pointer
                   hover:bg-white/25 transition-colors">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    {{-- Logo image --}}
    <div
        class="flex items-center justify-center w-[62px] h-[62px] rounded-2xl
                bg-white/18 border-2 border-white/28 overflow-hidden">
        <img src="{{ asset('img/logo-1.png') }}" alt="Logo SMK Negeri 5" class="w-[56px] h-[56px] object-contain">
    </div>

    <h2 class="text-[13px] font-extrabold text-white text-center tracking-wide leading-tight">
        SMK NEGERI 5
    </h2>
    <p class="text-[11px] text-white/62 text-center -mt-1">TELKOM BANDA ACEH</p>
</div>

{{-- ── NAVIGATION ── --}}
<nav class="flex-1 flex flex-col gap-0.5 px-2.5 py-3.5 overflow-y-auto sb-scroll">

    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.dashboard')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">🏠</span>
        <span class="flex-1">Dashboard</span>
    </a>

    {{-- Profil Operator --}}
    <a href="{{ route('admin.profil') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.profil*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">👤</span>
        <span class="flex-1">Profil Operator</span>
    </a>

    {{-- Manajemen Siswa --}}
    <a href="{{ route('admin.siswa') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.siswa*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">🎓</span>
        <span class="flex-1">Manajemen Siswa</span>
    </a>

    {{-- Manajemen Guru --}}
    <a href="{{ route('admin.guru') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.guru*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">👨‍🏫</span>
        <span class="flex-1">Manajemen Guru</span>
    </a>

    {{-- Manajemen Kelas --}}
    <a href="{{ route('admin.kelas') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.kelas*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">🏫</span>
        <span class="flex-1">Manajemen Kelas</span>
    </a>

    {{-- Data 7 Kebiasaan --}}
    <a href="{{ route('admin.kebiasaan') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.kebiasaan*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">📝</span>
        <span class="flex-1">Data 7 Kebiasaan</span>
    </a>

    {{-- Pesan Bantuan --}}
    <a href="{{ route('admin.pesan-bantuan') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.pesan-bantuan*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">❓</span>
        <span class="flex-1">Pesan Bantuan</span>
    </a>

    {{-- Manajemen Website --}}
    <a href="{{ route('admin.manajemen-website') }}"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-[11px] text-[13px] font-medium
              no-underline transition-all duration-200
              {{ request()->routeIs('admin.manajemen-website*')
                  ? 'bg-white/22 text-white font-bold nav-active-accent'
                  : 'text-white/72 hover:bg-white/14 hover:text-white hover:translate-x-[3px]' }}">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">⚙️</span>
        <span class="flex-1">Manajemen Website</span>
    </a>

</nav>

{{-- ── LOGOUT ── --}}
<div class="px-2.5 py-2.5 border-t border-white/10">
    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-[11px]
                   text-[13px] font-medium text-red-300/82 bg-transparent border-none
                   cursor-pointer transition-all duration-200 font-sans
                   hover:bg-red-500/18 hover:text-red-300">
        <span class="w-[17px] h-[17px] shrink-0 flex items-center justify-center text-lg">🚪</span>
        Keluar
    </button>
</div>
