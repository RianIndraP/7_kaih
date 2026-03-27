@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

    {{-- ===== GREETING ===== --}}
    <p class="text-base font-medium text-gray-800 mb-5">
        Selamat Datang, <span class="font-semibold">{{ $user->name ?? 'Siswa' }}</span> 👋
    </p>

    {{-- ===== TANGGAL INFO ===== --}}
    <div class="mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Data Hari Ini</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>
                @if($kebiasaanHariIni)
                    <div class="flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xs font-semibold">Sudah Lengkap</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-semibold">Belum Lengkap</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== TOP ROW ===== --}}
    @php
        $kebiasaanHariIni = $kebiasaanHariIni ?? false;
        $kebiasaanList = [
            'bangun_pagi'   => 'Bangun pagi',
            'beribadah'     => 'Beribadah',
            'berolahraga'   => 'Berolahraga',
            'makan_sehat'   => 'Makan sehat',
            'gemar_belajar' => 'Gemar belajar',
            'bermasyarakat' => 'Bermasyarakat',
            'tidur_cepat'   => 'Tidur cepat',
        ];
        $kebiasaanData  = $kebiasaanData ?? [];
        $totalKebiasaan = count($kebiasaanList);
        $selesai        = collect($kebiasaanData)->filter()->count();
        $persen         = $totalKebiasaan > 0 ? round(($selesai / $totalKebiasaan) * 100) : 0;
        $semuaSudahIsi  = $selesai >= $totalKebiasaan;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5 items-stretch">

        {{-- ============================================================
             KIRI: Profil Siswa (atas) + Aksi Cepat (bawah)
             keduanya dalam satu flex column agar setinggi kolom kanan
        ============================================================ --}}
        <div class="flex flex-col gap-5">

            {{-- ---- PROFIL SISWA ---- --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex-1">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-800">Profil Siswa</span>
                </div>

                <div class="p-5 flex items-center gap-5">
                    {{-- Foto --}}
                    <div class="shrink-0">
                        <div class="w-24 h-24 rounded-full bg-gray-200 border-4 border-gray-300
                                    flex items-center justify-center overflow-hidden">
                            @if (!empty($user->photo))
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                     alt="Foto {{ $user->name }}"
                                     class="w-full h-full object-cover"/>
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-1.5">
                        <p class="text-sm text-gray-700">
                            Nama: <span class="font-semibold text-gray-900">{{ $user->name ?? '-' }}</span>
                        </p>
                        <p class="text-sm text-gray-700">
                            NISN: <span class="font-semibold text-gray-900">{{ $user->nisn ?? '-' }}</span>
                        </p>
                        <p class="text-sm text-gray-700">
                            Kelas: <span class="font-semibold text-gray-900">{{ $user->kelas ?? '-' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ---- AKSI CEPAT ---- --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-800">Aksi Cepat</span>
                </div>

                <div class="p-4 space-y-3">
                    {{-- Isi data 7 Kebiasaan --}}
                    <a href="{{ route('student.kebiasaan') }}"
                       class="flex items-center gap-3 w-full border border-gray-200 hover:border-blue-300
                              hover:bg-blue-50 rounded-lg px-4 py-3 transition-colors group">
                        <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center
                                    justify-center shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                         00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2
                                         2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700 transition-colors">
                                Isi data 7 Kebiasaan
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">Catat kebiasaan harian kamu</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    {{-- Pesan Dari Guru Wali --}}
                    <a href="{{ route('student.pesan') }}"
                       class="flex items-center gap-3 w-full border border-gray-200 hover:border-purple-300
                              hover:bg-purple-50 rounded-lg px-4 py-3 transition-colors group">
                        <div class="w-8 h-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center
                                    justify-center shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0
                                         012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 group-hover:text-purple-700 transition-colors">
                                Pesan Dari Guru Wali
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">Lihat pesan terbaru dari guru wali</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-500 transition-colors shrink-0"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>{{-- end kolom kiri --}}

        {{-- ============================================================
             KANAN: Status 7 Kebiasaan — mengisi penuh tinggi kolom kiri
        ============================================================ --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-800">Status 7 Kebiasaan Anak Indonesia Hebat</span>
            </div>

            <div class="p-4 flex flex-col gap-3 flex-1">

                {{-- Warning: hanya muncul jika BELUM semua diisi --}}
                @if (!$semuaSudahIsi)
                    <div class="flex items-center gap-2 border border-yellow-300 bg-yellow-50 rounded-lg px-3 py-2.5">
                        <svg class="w-4 h-4 text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                     001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-yellow-700">Belum Mengisi Data</span>
                    </div>
                @endif

                {{-- Progress bar --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-xs font-medium text-gray-600">Progres Kebiasaan</span>
                        <span class="text-xs font-semibold text-blue-600">{{ $persen }}% Lengkap</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="h-3 rounded-full transition-all duration-500
                                    @if($persen >= 100) bg-green-500
                                    @elseif($persen >= 50) bg-blue-500
                                    @else bg-gray-700
                                    @endif"
                             style="width: {{ $persen }}%">
                        </div>
                    </div>
                </div>

                {{-- Checklist 7 kebiasaan — READ ONLY, otomatis dari data form siswa --}}
                <div class="grid grid-cols-2 gap-x-4 gap-y-3 flex-1">
                    @foreach ($kebiasaanList as $key => $label)
                        @php $checked = !empty($kebiasaanData[$key]); @endphp
                        <div class="flex items-center gap-2 select-none cursor-default">
                            {{-- Checkbox read-only --}}
                            <span class="w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0
                                         @if($checked) bg-blue-600 border-blue-600
                                         @else bg-white border-gray-400
                                         @endif">
                                @if($checked)
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </span>
                            <span class="text-sm {{ $checked ? 'text-gray-800 font-medium' : 'text-gray-500' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Footer: tombol isi / sudah selesai --}}
                <div class="pt-2 border-t border-gray-100 mt-auto">
                    @if (!$semuaSudahIsi)
                        <a href="{{ route('student.kebiasaan') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold
                                  text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                         m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Lengkapi data kebiasaan →
                        </a>
                    @else
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-xs font-medium text-green-600">Semua kebiasaan hari ini sudah diisi!</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>{{-- end kolom kanan --}}

    </div>{{-- end top row --}}

    {{-- ===== NOTIFICATION BANNER BAWAH ===== --}}
    @if (!$kebiasaanHariIni)
        <div class="flex items-center gap-3 bg-white border border-yellow-200 rounded-xl px-5 py-3 shadow-sm">
            <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                         001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-sm text-gray-700">Anda belum mengisi data kebiasaan hari ini</p>
            <a href="{{ route('student.kebiasaan') }}"
               class="ml-auto text-xs font-semibold text-blue-600 hover:text-blue-800
                      hover:underline shrink-0 transition-colors">
                Isi Sekarang →
            </a>
        </div>
    @else
        <div class="flex items-center gap-3 bg-white border border-green-200 rounded-xl px-5 py-3 shadow-sm">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm text-gray-700">Kebiasaan hari ini sudah diisi. Pertahankan! 🎉</p>
        </div>
    @endif

</div>

@endsection