@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard Guru')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

    {{-- ===== GREETING ===== --}}
    <p class="text-sm font-medium text-gray-800 mb-5">
        Selamat Datang, <span class="font-semibold">{{ $guru?->nama_lengkap ?? auth()->user()->name }}</span> 👋
    </p>

    {{-- ===== TOP ROW: Profil Guru + Status Siswa ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5 items-stretch">

        {{-- ---- PROFIL GURU ---- --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-800">Profil Guru</span>
            </div>

            <div class="p-5 flex items-center gap-5 flex-1">
                {{-- Foto --}}
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-full bg-gray-200 border-4 border-gray-300
                                flex items-center justify-center overflow-hidden">
                        @if ($guru?->foto)
                            <img src="{{ asset('storage/' . $guru->foto) }}"
                                 alt="Foto Guru" class="w-full h-full object-cover"/>
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
                        Nama: <span class="font-semibold text-gray-900">
                            {{ $guru?->nama_lengkap ?? auth()->user()->name }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-700">
                        NIP: <span class="font-semibold text-gray-900">
                            {{ $guru?->nip ?? '-' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- ---- STATUS SISWA YANG BELUM MENGISI ---- --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-800">Status Siswa Yang Belum Mengisi</span>
            </div>

            <div class="p-4 flex flex-col gap-2 flex-1">
                @forelse ($siswaBlumMengisi as $siswa)
                    <div class="flex items-center gap-3 border border-gray-200 rounded-lg px-4 py-3
                                hover:bg-gray-50 transition-colors">
                        {{-- Ikon shield X --}}
                        <div class="flex-shrink-0 w-7 h-7 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                                         a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591
                                         3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                                         0-1.042-.133-2.052-.382-3.016z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 flex-1">
                            <span class="font-medium">{{ $siswa->name }}</span>
                            belum melengkapi form
                        </p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center flex-1">
                        <svg class="w-10 h-10 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Semua siswa sudah mengisi form</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ===== AKSI CEPAT ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-sm font-semibold text-gray-800">Aksi Cepat</span>
        </div>

        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">

            {{-- Pelaporan Guru --}}
            <a href="{{ route('guru.pelaporan') }}"
               class="flex items-center gap-3 border border-gray-200 hover:border-blue-300
                      hover:bg-blue-50 rounded-lg px-4 py-3 transition-colors group">
                <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center
                            justify-center flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-800 group-hover:text-blue-700 transition-colors">
                    Pelaporan Guru
                </span>
            </a>

            {{-- Absen Siswa --}}
            <a href="{{ route('guru.absensi-murid') }}"
               class="flex items-center gap-3 border border-gray-200 hover:border-blue-300
                      hover:bg-blue-50 rounded-lg px-4 py-3 transition-colors group">
                <div class="w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center
                            justify-center flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-800 group-hover:text-blue-700 transition-colors">
                    Absen siswa
                </span>
            </a>

        </div>
    </div>

</div>

@endsection