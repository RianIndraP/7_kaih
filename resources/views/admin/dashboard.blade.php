@extends('layouts.admin')

@section('title', 'Dashboard Admin | SMK N 5 Telkom Banda Aceh')
@section('page_title', 'Dashboard')

@section('content')

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

        {{-- Total Siswa --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5
                    flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2
                             0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-0.5">Total Siswa</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalSiswa) }}</p>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5
                    flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-0.5">Total Guru</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalGuru) }}</p>
            </div>
        </div>

        {{-- Total Alumni --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5
                    flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012
                             20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-0.5">Total Alumni</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAlumni) }}</p>
            </div>
        </div>
    </div>

    {{-- ===== STAT SEKUNDER ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

        {{-- Total pengisian kebiasaan bulan ini --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4
                    flex items-center gap-4">
            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2
                             2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm text-gray-600">Total Pengisian Kebiasaan Siswa Perbulan</span>
                <span class="text-xl font-bold text-gray-900">{{ number_format($totalKebiasaanBulanIni) }}</span>
            </div>
        </div>

        {{-- Total pesan bantuan hari ini --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4
                    flex items-center gap-4">
            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                </svg>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm text-gray-600">Total Pesan Bantuan Hari Ini</span>
                <span class="text-xl font-bold text-gray-900">{{ number_format($totalPesanHariIni) }}</span>
            </div>
        </div>
    </div>

    {{-- ===== KONTEN BAWAH: Pesan Bantuan + Aksi Cepat ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Pesan Bantuan Terbaru (2/3) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7
                                 a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-800">Pesan Bantuan Terbaru</span>
                </div>
                <a href="{{ route('admin.pesan-bantuan') }}"
                   class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600">Pesan</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanBantuanTerbaru as $pesan)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-800 whitespace-nowrap">
                                    {{ $pesan->nama_pengirim }}
                                </td>
                                <td class="px-5 py-3 text-gray-600 max-w-[200px] truncate">
                                    {{ $pesan->isi }}
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                                    {{ $pesan->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-400 text-sm">
                                    Belum ada pesan bantuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aksi Cepat (1/3) --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-800">Aksi Cepat</span>
            </div>
            <div class="p-4 space-y-3">

                <a href="{{ route('admin.siswa') }}"
                   class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3
                          hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                    <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg
                                flex items-center justify-center flex-shrink-0 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                                     20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002
                                     0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Tambah Siswa</span>
                </a>

                <a href="{{ route('admin.guru') }}"
                   class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3
                          hover:border-green-300 hover:bg-green-50 transition-colors group">
                    <div class="w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg
                                flex items-center justify-center flex-shrink-0 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Tambah Guru</span>
                </a>

                <a href="{{ route('admin.pesan-bantuan') }}"
                   class="flex items-center gap-3 border border-gray-200 rounded-xl px-4 py-3
                          hover:border-red-300 hover:bg-red-50 transition-colors group">
                    <div class="w-9 h-9 bg-red-100 group-hover:bg-red-200 rounded-lg
                                flex items-center justify-center flex-shrink-0 transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">Pesan Bantuan</span>
                </a>
            </div>
@endsection