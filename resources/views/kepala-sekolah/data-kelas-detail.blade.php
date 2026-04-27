@extends('layouts.kepala-sekolah')

@section('title', 'Detail Kelas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('kepala-sekolah.data-kelas') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Data Kelas
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Kelas</h1>
        <p class="text-gray-600">Daftar siswa di kelas {{ $kelas->nama_kelas }}</p>
    </div>

    {{-- Summary Card --}}
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Nama Kelas</p>
                <p class="text-2xl font-bold">{{ $kelas->nama_kelas }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            NIS
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Guru Wali
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Nomor Telepon
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Alamat
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($siswaList as $index => $siswa)
                    <tr class="hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.01]">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($siswa->nisn))
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                    {{ $siswa->nisn }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="shrink-0 h-12 w-12">
                                    @if($siswa->foto)
                                        <img class="h-12 w-12 rounded-xl object-cover shadow-md"
                                             src="{{ asset('storage/' . $siswa->foto) }}"
                                             alt="{{ $siswa->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">
                                                {{ substr($siswa->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $siswa->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($siswa->waliKelas && $siswa->waliKelas->user)
                                <div class="flex items-center gap-2">
                                    <div class="shrink-0 h-8 w-8">
                                        @if($siswa->waliKelas->user->foto)
                                            <img class="h-8 w-8 rounded-lg object-cover"
                                                 src="{{ asset('storage/' . $siswa->waliKelas->user->foto) }}"
                                                 alt="{{ $siswa->waliKelas->user->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-600 font-medium text-xs">
                                                    {{ substr($siswa->waliKelas->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <span>{{ $siswa->waliKelas->user->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($siswa->no_telepon))
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $siswa->no_telepon }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if(!empty($siswa->alamat))
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $siswa->alamat }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada siswa di kelas ini</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambahkan siswa ke kelas ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
