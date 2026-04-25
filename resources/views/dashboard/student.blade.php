@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard')

@section('content')

    <div class="max-w-[1100px] mx-auto space-y-4">

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
                    Selamat Datang, {{ $user->name ?? 'Siswa' }} 👋
                </h1>
                <p class="text-[13px] text-white/72">
                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    · {{ $user->kelas?->nama_kelas ?? '-' }}
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

        {{-- ═══════════════════════════════════════════
         ALERT: PROFIL BELUM LENGKAP
    ════════════════════════════════════════════ --}}
        @if (!$user->isProfileComplete())
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-red-600 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Profil Belum Lengkap</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Silakan lengkapi profil untuk mengakses semua fitur.</p>
                </div>
                <a href="{{ route('student.profile') }}"
                    class="text-[12px] font-bold text-red-600 no-underline whitespace-nowrap
                      hover:text-red-800 hover:underline transition-colors shrink-0">
                    Lengkapi Sekarang →
                </a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
         ALERT: PESAN BELUM DIBACA
    ════════════════════════════════════════════ --}}
        @if (auth()->user()->isProfileComplete())
            @php
                $unreadPesan = \App\Models\PesanGuruSiswa::where('siswa_id', auth()->id())
                    ->whereDoesntHave('reads', fn($q) => $q->where('siswa_id', auth()->id()))
                    ->count();
            @endphp
            @if ($unreadPesan > 0)
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 anim-fade-up-1">
                    <div class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px] bg-blue-600 shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-gray-900">
                            {{ $unreadPesan }} Pesan Baru dari Guru Wali
                        </p>
                        <p class="text-[12px] text-gray-500 mt-0.5">Anda memiliki pesan yang belum dibaca.</p>
                    </div>
                    <a href="{{ route('student.pesan') }}"
                        class="text-[12px] font-bold text-blue-600 no-underline whitespace-nowrap
                          hover:text-blue-800 hover:underline transition-colors shrink-0">
                        Lihat Pesan →
                    </a>
                </div>
            @endif
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
                    <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Data Hari Ini</p>
                    <p class="text-[15px] font-extrabold text-gray-900">
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            @if ($kebiasaanHariIni)
                <div
                    class="flex items-center gap-1.5 bg-green-100 text-green-700 font-bold
                        text-[12px] px-3.5 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Sudah Lengkap
                </div>
            @else
                <div
                    class="flex items-center gap-1.5 bg-orange-100 text-orange-700 font-bold
                        text-[12px] px-3.5 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Belum Lengkap
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════
         MAIN GRID: Profil + 7 Kebiasaan
    ════════════════════════════════════════════ --}}
        @php
            $kebiasaanList = [
                'bangun_pagi' => 'Bangun pagi',
                'beribadah' => 'Beribadah',
                'berolahraga' => 'Berolahraga',
                'makan_sehat' => 'Makan sehat',
                'gemar_belajar' => 'Gemar belajar',
                'bermasyarakat' => 'Bermasyarakat',
                'tidur_cepat' => 'Tidur cepat',
            ];
            $kebiasaanData = $kebiasaanData ?? [];
            $totalKebiasaan = count($kebiasaanList);
            $selesai = collect($kebiasaanData)->filter()->count();
            $persen = $totalKebiasaan > 0 ? round(($selesai / $totalKebiasaan) * 100) : 0;
            $semuaSudahIsi = $selesai >= $totalKebiasaan;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 anim-fade-up-3">

            {{-- ── KOLOM KIRI: Profil + Aksi Cepat ── --}}
            <div class="flex flex-col gap-4">

                {{-- Profil Siswa --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    {{-- Card header --}}
                    <div
                        class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div
                            class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Profil Siswa</span>
                    </div>
                    {{-- Card body --}}
                    <div class="flex items-center gap-3.5 p-4">
                        {{-- Photo --}}
                        <div
                            class="w-[76px] h-[76px] rounded-2xl photo-gradient border-[3px] border-indigo-100
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
                                <span class="text-[12px] font-bold text-gray-900">{{ $user->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">NISN</span>
                                <span class="text-[12px] font-bold text-gray-900">{{ $user->nisn ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-2.5 py-1.5 bg-slate-50 rounded-lg">
                                <span class="text-[11px] font-semibold text-gray-400">Kelas</span>
                                <span
                                    class="text-[12px] font-bold text-gray-900">{{ $user->kelas?->nama_kelas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aksi Cepat --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div
                        class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                            bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div
                            class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                            <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Aksi Cepat</span>
                    </div>
                    <div class="p-2.5 flex flex-col gap-2">

                        {{-- Isi 7 Kebiasaan --}}
                        <a href="{{ route('student.kebiasaan') }}"
                            class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                              no-underline bg-white transition-all duration-200
                              hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                              hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                    transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Isi 7 Kebiasaan
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Catat kebiasaan harian kamu</p>
                            </div>
                            <svg class="w-[15px] h-[15px] text-gray-300 transition-all duration-200
                                   group-hover:text-blue-500 group-hover:translate-x-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        {{-- Pesan Guru Wali --}}
                        <a href="{{ route('student.pesan') }}"
                            class="group flex items-center gap-3 px-3.5 py-3 rounded-xl border border-gray-200
                              no-underline bg-white transition-all duration-200
                              hover:border-blue-300 hover:bg-blue-50 hover:-translate-y-0.5
                              hover:shadow-[0_6px_18px_rgba(37,99,235,0.10)]">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-[11px] icon-gradient shrink-0
                                    transition-transform duration-200 group-hover:scale-110">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    Pesan Guru Wali
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Lihat pesan terbaru dari guru</p>
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

            {{-- ── KOLOM KANAN: 7 Kebiasaan ── --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex flex-col">

                <div
                    class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100
                        bg-gradient-to-r from-blue-50 to-indigo-50 shrink-0">
                    <div class="flex items-center justify-center w-[30px] h-[30px] rounded-[8px] icon-gradient shrink-0">
                        <svg class="w-[15px] h-[15px] text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Status 7 Kebiasaan Anak Indonesia Hebat</span>
                </div>

                <div class="flex flex-col gap-3.5 p-4 flex-1">

                    {{-- Progress bar --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] font-medium text-gray-500">Progres Kebiasaan</span>
                            <span class="text-[12px] font-extrabold text-blue-600" id="habitPct">{{ $persen }}%
                                Lengkap</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div id="habitFill"
                                class="h-full rounded-full transition-[width] duration-700 ease-in-out
                                    {{ $semuaSudahIsi ? 'progress-fill-green' : 'progress-fill-blue' }}"
                                style="width: {{ $persen }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Checklist grid (interactive) --}}
                    <div class="grid grid-cols-2 gap-2" id="habitsGrid">
                        @foreach ($kebiasaanList as $key => $label)
                            @php $checked = !empty($kebiasaanData[$key]); @endphp
                            <div data-key="{{ $key }}" onclick="toggleHabit(this)"
                                class="habit-item flex items-center gap-2 px-3 py-2.5 rounded-[10px]
                                    border cursor-pointer select-none transition-all duration-200
                                    {{ $checked
                                        ? 'bg-blue-50 border-blue-200 checked'
                                        : 'bg-white border-gray-200 hover:border-blue-200 hover:bg-blue-50/50' }}">
                                {{-- Checkbox --}}
                                <div
                                    class="hbox flex items-center justify-center w-[17px] h-[17px] rounded-[5px] border-2
                                        shrink-0 transition-all duration-200
                                        {{ $checked ? 'bg-blue-600 border-blue-600' : 'bg-white border-gray-300' }}">
                                    <svg class="w-2.5 h-2.5 text-white {{ $checked ? 'opacity-100' : 'opacity-0' }}
                                            transition-opacity duration-150"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span
                                    class="text-[12px] font-medium transition-colors duration-200
                                         {{ $checked ? 'text-blue-700 font-bold' : 'text-gray-600' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="pt-2 border-t border-gray-100 mt-auto" id="habitFooter">
                        @if (!$semuaSudahIsi)
                            <a href="{{ route('student.kebiasaan') }}"
                                class="inline-flex items-center gap-1.5 text-[12px] font-bold text-blue-600
                                  no-underline transition-all duration-200 hover:text-blue-800 hover:gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Lengkapi data kebiasaan →
                            </a>
                        @else
                            <div class="inline-flex items-center gap-1.5 text-[12px] font-bold text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Semua kebiasaan hari ini sudah diisi!
                            </div>
                        @endif
                    </div>

                </div>
            </div>{{-- end habit card --}}

        </div>{{-- end grid --}}

        {{-- ═══════════════════════════════════════════
         ALERT BAWAH: Status kebiasaan hari ini
    ════════════════════════════════════════════ --}}
        @if (!$kebiasaanHariIni)
            <div
                class="flex items-center gap-3 bg-orange-50 border border-orange-200 rounded-2xl
                    px-4 py-3 anim-fade-up-4">
                <div
                    class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px]
                        bg-orange-500 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Belum mengisi kebiasaan hari ini</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Jangan lupa catat kebiasaanmu sebelum hari berakhir!</p>
                </div>
                <a href="{{ route('student.kebiasaan') }}"
                    class="text-[12px] font-bold text-orange-600 no-underline whitespace-nowrap
                      hover:text-orange-800 hover:underline transition-colors shrink-0">
                    Isi Sekarang →
                </a>
            </div>
        @else
            <div
                class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl
                    px-4 py-3 anim-fade-up-4">
                <div
                    class="flex items-center justify-center w-[34px] h-[34px] rounded-[9px]
                        bg-green-600 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Kebiasaan hari ini sudah diisi</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Pertahankan konsistensi kamu. Keren! 🎉</p>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
         NOTIFIKASI CARD
    ════════════════════════════════════════════ --}}
        <div class="notif-gradient border border-blue-200 rounded-2xl p-5 anim-fade-up-4">
            <div class="flex items-start gap-4">

                {{-- Icon --}}
                <div
                    class="flex items-center justify-center w-[50px] h-[50px] rounded-[14px]
                        notif-btn-gradient shrink-0
                        shadow-[0_6px_20px_rgba(37,99,235,0.22)]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-[15px] font-extrabold text-gray-900 mb-1.5">Notifikasi Pengingat</h3>
                    <p class="text-[13px] text-gray-500 leading-relaxed mb-3.5">
                        Aktifkan notifikasi untuk mendapatkan pengingat mengisi kebiasaan harian.
                        Tidak ada spam, hanya pengingat penting saja.
                    </p>

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach (['Pengingat Harian', 'Tanpa Spam', 'Bisa Dinonaktifkan'] as $tag)
                            <span
                                class="inline-flex items-center gap-1 bg-white border border-blue-100
                                     rounded-lg px-2.5 py-1 text-[11px] font-semibold text-gray-600">
                                <svg class="w-2.5 h-2.5 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>

                    {{-- CTA Button --}}
                    <button onclick="askForPermission()"
                        class="inline-flex items-center gap-2 notif-btn-gradient text-white
                               border-none rounded-[11px] px-5 py-2.5 text-[13px] font-bold
                               cursor-pointer font-sans transition-all duration-200
                               shadow-[0_4px_14px_rgba(37,99,235,0.28)]
                               hover:-translate-y-0.5 hover:shadow-[0_8px_22px_rgba(37,99,235,0.35)]
                               active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Aktifkan Notifikasi
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- end max-width wrapper --}}

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>

@endsection

@section('scripts')
    <script>
        /* ══ HABITS INTERACTIVE TOGGLE ══════════════════ */
        const TOTAL_HABITS = {{ count($kebiasaanList) }};
        const habitFill = document.getElementById('habitFill');
        const habitPct = document.getElementById('habitPct');
        const habitFooter = document.getElementById('habitFooter');
        const kebiasaanRoute = "{{ route('student.kebiasaan') }}";

        function toggleHabit(el) {
            const isChecked = el.classList.contains('checked');
            const hbox = el.querySelector('.hbox');
            const checkSvg = hbox.querySelector('svg');
            const label = el.querySelector('span');

            if (isChecked) {
                el.classList.remove('checked', 'bg-blue-50', 'border-blue-200');
                el.classList.add('bg-white', 'border-gray-200', 'hover:border-blue-200', 'hover:bg-blue-50/50');
                hbox.classList.remove('bg-blue-600', 'border-blue-600');
                hbox.classList.add('bg-white', 'border-gray-300');
                checkSvg.classList.replace('opacity-100', 'opacity-0');
                label.classList.remove('text-blue-700', 'font-bold');
                label.classList.add('text-gray-600');
            } else {
                el.classList.add('checked', 'bg-blue-50', 'border-blue-200');
                el.classList.remove('bg-white', 'border-gray-200', 'hover:border-blue-200', 'hover:bg-blue-50/50');
                hbox.classList.add('bg-blue-600', 'border-blue-600');
                hbox.classList.remove('bg-white', 'border-gray-300');
                checkSvg.classList.replace('opacity-0', 'opacity-100');
                label.classList.add('text-blue-700', 'font-bold');
                label.classList.remove('text-gray-600');
            }

            updateProgress();
        }

        function updateProgress() {
            const checked = document.querySelectorAll('.habit-item.checked').length;
            const p = Math.round(checked / TOTAL_HABITS * 100);

            habitFill.style.width = p + '%';
            habitPct.textContent = p + '% Lengkap';

            if (p >= 100) {
                habitFill.classList.remove('progress-fill-blue');
                habitFill.classList.add('progress-fill-green');
                habitFooter.innerHTML = `
                <div class="inline-flex items-center gap-1.5 text-[12px] font-bold text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Semua kebiasaan hari ini sudah diisi!
                </div>`;
            } else {
                habitFill.classList.remove('progress-fill-green');
                habitFill.classList.add('progress-fill-blue');
                habitFooter.innerHTML = `
                <a href="${kebiasaanRoute}"
                   class="inline-flex items-center gap-1.5 text-[12px] font-bold text-blue-600
                          no-underline transition-all duration-200 hover:text-blue-800 hover:gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Lengkapi data kebiasaan →
                </a>`;
            }
        }

        /* ══ FIREBASE PUSH NOTIFICATION ════════════════ */
        const firebaseConfig = {
            apiKey: "AIzaSyA-AM4wp75BPE6qO_qpCOBJhI5Al20MAJ0",
            authDomain: "kaih-96705.firebaseapp.com",
            projectId: "kaih-96705",
            storageBucket: "kaih-96705.firebasestorage.app",
            messagingSenderId: "483886147031",
            appId: "1:483886147031:web:50feb71270712893dc1792"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        messaging.onMessage((payload) => {
            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: '/img/logo-1.png'
            });
        });

        function askForPermission() {
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    messaging.getToken({
                        vapidKey: 'BKF8hImiWIPNnOgb-jVu9IEV9mXCUR_y0OcbAMpXbSpVK3CImtAHg-hD9RQ_tV41vPvSAHq8RWMIS4K6wj46Rck'
                    }).then((currentToken) => {
                        if (currentToken) {
                            fetch('/save-token', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        token: currentToken
                                    })
                                })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.success) alert('Notifikasi berhasil diaktifkan!');
                                });
                        }
                    }).catch(err => console.log('Error Token:', err));
                } else {
                    alert('Izin ditolak. Silakan aktifkan manual dari pengaturan browser (ikon gembok).');
                }
            });
        }
    </script>
@endsection
