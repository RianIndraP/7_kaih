@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard Guru')

@section('content')

    <div class="max-w-[1100px] mx-auto space-y-3">

        {{-- ═══════════════════════════════════════════
             GREETING CARD
        ════════════════════════════════════════════ --}}
        <div
            class="greeting-gradient relative overflow-hidden rounded-2xl px-6 py-5
                flex items-center justify-between anim-fade-up">
            {{-- Decorative circles --}}
            <span class="absolute -top-14 -right-10 w-44 h-44 rounded-full bg-white/7 pointer-events-none"></span>
            <span class="absolute -bottom-8 right-20 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></span>

            <div class="relative z-10">
                <h1 class="text-[18px] font-extrabold text-white mb-1 leading-tight">
                    Selamat Datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-[13px] text-white/72">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    @if ($guru?->kelas_wali)
                        · Wali Kelas {{ $guru->kelas_wali }}
                    @endif
                </p>
            </div>

            <div
                class="relative z-10 w-[58px] h-[58px] rounded-2xl bg-white/18 border-2 border-white/30
                    flex items-center justify-center shrink-0 overflow-hidden">
                @if (!empty($user->foto))
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->name }}"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>
        </div>

        {{-- Update Message Notification --}}
        @php
            $updateMessage = \App\Models\WebsiteManagement::getUpdateMessage('guru');
        @endphp
        @include('guru._update_message', ['updateMessage' => $updateMessage])

        {{-- ═══════════════════════════════════════════
             ALERT: PROFIL BELUM LENGKAP
        ════════════════════════════════════════════ --}}
        @if (empty(auth()->user()->email) || empty(auth()->user()->no_telepon))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-red-600 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Profil Belum Lengkap</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Silakan lengkapi email dan nomor telepon untuk mengakses semua fitur.</p>
                </div>
                <a href="{{ route('guru.profil') }}"
                    class="text-[12px] font-bold text-red-600 no-underline whitespace-nowrap
                      hover:text-red-800 hover:underline transition-colors shrink-0">
                    Lengkapi Sekarang →
                </a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
             ALERT: ADA SISWA BELUM MENGISI
        ════════════════════════════════════════════ --}}
        @if ($siswaBlumMengisi->count() > 0)
            <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-orange-500 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">
                        {{ $siswaBlumMengisi->count() }} Siswa Belum Mengisi Form
                    </p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Beberapa siswa asuh belum melengkapi data kebiasaan hari ini.</p>
                </div>
                <button onclick="scrollToSiswa()"
                    class="text-[12px] font-bold text-orange-600 no-underline whitespace-nowrap
                      hover:text-orange-800 hover:underline transition-colors shrink-0 bg-transparent border-none cursor-pointer">
                    Lihat Siswa →
                </button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
             DATE ROW
        ════════════════════════════════════════════ --}}
        <div
            class="flex items-center justify-between bg-white border border-gray-200 rounded-2xl
                px-5 py-3.5 anim-fade-up-2">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-[10px] icon-gradient shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Hari Ini</p>
                    <p class="text-[15px] font-extrabold text-gray-900">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            {{-- Ringkasan jumlah siswa --}}
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 bg-blue-100 text-blue-700 font-bold text-[12px] px-3.5 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                    </svg>
                    {{ $totalSiswaAsuh ?? 0 }} Siswa Asuh
                </div>
                @if ($siswaBlumMengisi->count() === 0)
                    <div class="flex items-center gap-1.5 bg-green-100 text-green-700 font-bold text-[12px] px-3.5 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Semua Mengisi
                    </div>
                @else
                    <div class="flex items-center gap-1.5 bg-orange-100 text-orange-700 font-bold text-[12px] px-3.5 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $siswaBlumMengisi->count() }} Belum Mengisi
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             MAIN GRID: Profil + Status Siswa
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 anim-fade-up-3">

            {{-- ── KOLOM KIRI: Profil Guru + Aksi Cepat ── --}}
            <div class="flex flex-col gap-4">

                {{-- Profil Guru --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Profil Guru</span>
                    </div>

                    <div class="flex items-center gap-3.5 p-4">
                        {{-- Foto --}}
                        <div class="w-[76px] h-[76px] rounded-2xl photo-gradient border-[3px] border-indigo-100
                                flex items-center justify-center shrink-0 overflow-hidden">
                            @if (!empty($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-9 h-9 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Info rows --}}
                        <div class="flex flex-col gap-2 flex-1">
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">Nama</span>
                                <span class="text-[12px] font-bold text-gray-900">{{ auth()->user()->name }}</span>
                            </div>
                            @if (auth()->user()->nip)
                                <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                    <span class="text-[11px] font-semibold text-gray-400">NIP</span>
                                    <span class="text-[12px] font-bold text-gray-900">{{ auth()->user()->nip }}</span>
                                </div>
                            @elseif(auth()->user()->nik)
                                <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                    <span class="text-[11px] font-semibold text-gray-400">NIK</span>
                                    <span class="text-[12px] font-bold text-gray-900">{{ auth()->user()->nik }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">Status</span>
                                <span class="text-[12px] font-bold text-gray-900">{{ $guru?->status_pegawai ?? '-' }}</span>
                            </div>
                            @if ($guru?->kelas_wali)
                                <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                    <span class="text-[11px] font-semibold text-gray-400">Kelas Wali</span>
                                    <span class="text-[12px] font-bold text-gray-900">{{ $guru->kelas_wali }}</span>
                                </div>
                            @endif
                            @if ($guru?->jabatan)
                                <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                    <span class="text-[11px] font-semibold text-gray-400">Jabatan</span>
                                    <span class="text-[12px] font-bold text-gray-900">{{ $guru->jabatan }}</span>
                                </div>
                            @endif
                            @if ($guru?->unit_kerja)
                                <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                    <span class="text-[11px] font-semibold text-gray-400">Unit Kerja</span>
                                    <span class="text-[12px] font-bold text-gray-900">{{ $guru->unit_kerja }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-blue-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-blue-500">Total Siswa Asuh</span>
                                <span class="text-[12px] font-bold text-blue-700">{{ $totalSiswaAsuh ?? 0 }} Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aksi Cepat --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Aksi Cepat</span>
                    </div>

                    <div class="p-2.5 flex flex-col gap-2">

                        {{-- Pelaporan Guru --}}
                        <a href="{{ route('guru.pelaporan') }}"
                            class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                              no-underline bg-white transition-all duration-200
                              hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                              hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                    transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Pelaporan Guru
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Buat dan kelola laporan siswa</p>
                            </div>
                            <svg class="w-[15px] h-[15px] text-gray-300 transition-all duration-200
                                   group-hover:text-blue-500 group-hover:translate-x-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        {{-- Absen Siswa --}}
                        <a href="{{ route('guru.absensi-murid') }}"
                            class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                              no-underline bg-white transition-all duration-200
                              hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                              hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                    transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Absen Siswa
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Rekap kehadiran siswa hari ini</p>
                            </div>
                            <svg class="w-[15px] h-[15px] text-gray-300 transition-all duration-200
                                   group-hover:text-blue-500 group-hover:translate-x-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                    </div>
                </div>

            </div>{{-- end left col --}}

            {{-- ── KOLOM KANAN: Status Siswa Belum Mengisi ── --}}
            <div id="siswa-section" class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex flex-col">

                <div class="flex items-center justify-between gap-2.5 px-4 py-3 border-b border-gray-100
                        bg-gradient-to-r from-blue-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Status Siswa Belum Mengisi</span>
                    </div>
                    {{-- Badge count --}}
                    @if ($siswaBlumMengisi->count() > 0)
                        <span class="bg-orange-100 text-orange-700 font-bold text-[11px] px-2.5 py-1 rounded-full shrink-0">
                            {{ $siswaBlumMengisi->count() }} belum
                        </span>
                    @endif
                </div>

                {{-- Progress bar --}}
                @php
                    $total = $totalSiswaAsuh ?? 0;
                    $belum = $siswaBlumMengisi->count();
                    $sudah = max(0, $total - $belum);
                    $persen = $total > 0 ? round(($sudah / $total) * 100) : 100;
                @endphp
                <div class="px-4 pt-3 pb-2 shrink-0">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-semibold text-gray-400">Progres Pengisian</span>
                        <span id="progressPct" class="text-[11px] font-bold {{ $persen >= 100 ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $persen }}% ({{ $sudah }}/{{ $total }})
                        </span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all duration-700 {{ $persen >= 100 ? 'progress-fill-green' : 'progress-fill-blue' }}"
                            style="width: {{ $persen }}%">
                        </div>
                    </div>
                </div>

                {{-- List siswa --}}
                <div class="flex flex-col gap-1.5 p-2.5 overflow-y-auto flex-1 max-h-[340px]">
                    @forelse ($siswaBlumMengisi as $siswa)
                        <div
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border border-orange-100
                                bg-orange-50/60 hover:bg-orange-50 transition-colors">
                            <div class="flex items-center justify-center w-8 h-8 rounded-[9px] bg-orange-100 shrink-0">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 truncate">{{ $siswa->name }}</p>
                                <p class="text-[11px] text-orange-500 font-semibold mt-0.5">Belum melengkapi form</p>
                            </div>
                            <span class="shrink-0 text-[10px] font-bold bg-orange-100 text-orange-600 px-2 py-1 rounded-full">
                                Pending
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center flex-1">
                            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-[14px] font-bold text-gray-800">Semua Sudah Mengisi!</p>
                            <p class="text-[12px] text-gray-400 mt-1">Seluruh siswa asuh telah melengkapi form hari ini.</p>
                        </div>
                    @endforelse
                </div>

            </div>{{-- end right col --}}

        </div>{{-- end grid --}}

    </div>{{-- end max-width wrapper --}}

@endsection

@section('scripts')
    <script>
        function scrollToSiswa() {
            const el = document.getElementById('siswa-section');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
@endsection