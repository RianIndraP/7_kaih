<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-4 flex items-center justify-between">

        {{-- Kiri: hamburger + judul halaman --}}
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <button id="mobileMenuBtn"
                    class="lg:hidden p-2 text-gray-600 hover:text-gray-800 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-base font-semibold text-gray-900 truncate">
                @if(request()->routeIs('student.dashboard'))
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

        {{-- Kanan: 3 tombol aksi --}}
        <div class="flex items-center gap-1 flex-shrink-0">

            @if(auth()->user()->isSiswa())
                {{-- 🔔 Lonceng → Pesan Dari Guru Wali (Siswa) --}}
                <a href="{{ route('student.pesan') }}"
                   title="Pesan dari Guru Wali"
                   class="relative p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                 a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                                 C7.67 6.165 6 8.388 6 11v3.159
                                 c0 .538-.214 1.055-.595 1.436L4 17h5
                                 m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    {{-- Badge jumlah pesan belum dibaca --}}
                    @php
                        $unreadPesan = \App\Models\PesanGuru::where('siswa_id', auth()->id())
                            ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                            ->count();
                    @endphp
                    @if ($unreadPesan > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white
                                     text-[10px] font-bold rounded-full flex items-center justify-center
                                     leading-none">
                            {{ $unreadPesan > 9 ? '9+' : $unreadPesan }}
                        </span>
                    @endif
                </a>

                {{-- ⚙️ Gear → Ganti Password (Siswa) --}}
                <a href="{{ route('student.ganti-password') }}"
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

                {{-- ❓ Tanda Tanya → Kirim Pesan Bantuan (Siswa) --}}
                <a href="{{ route('student.kirim-pesan-bantuan') }}"
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
            @endif

            @if(auth()->user()->isGuru())
                {{-- ⚙️ Gear → Ganti Password (Guru) --}}
                <a href="{{ route('student.ganti-password') }}"
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

                {{-- ❓ Tanda Tanya → Kirim Pesan Bantuan (Guru) --}}
                <a href="{{ route('student.kirim-pesan-bantuan') }}"
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
            @endif

        </div>
    </div>
</header>