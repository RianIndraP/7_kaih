@extends('layouts.kepala-sekolah')

@section('title', 'Data Guru Wali')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Guru Wali</h1>
        <p class="text-gray-600">Daftar guru wali yang aktif di sekolah</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Guru Wali</p>
                    <p class="text-3xl font-bold">{{ $guruWaliData->count() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Guru dengan NIP</p>
                    <p class="text-3xl font-bold">{{ $guruWaliData->filter(function($g) { return !empty($g->user->nip); })->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Guru dengan NIK</p>
                    <p class="text-3xl font-bold">{{ $guruWaliData->filter(function($g) { return !empty($g->user->nik); })->count() }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a3 3 0 11-6 0 3 3 0 016 0zm2 8H7m2-2a3 3 0 11-6 0 3 3 0 016 0zm0 0a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="mb-6">
        <form action="{{ route('kepala-sekolah.data-guru-wali') }}" method="GET" class="flex items-center gap-4">
            <div class="flex-1 relative">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari berdasarkan nama..."
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
            @if($search)
            <a href="{{ route('kepala-sekolah.data-guru-wali') }}"
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            NIP/NIK
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Status Pegawai
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Nomor Telepon
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Email
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($guruWaliData as $index => $guru)
                    <tr class="hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.01]">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($guru->user->nip))
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                    {{ $guru->user->nip }}
                                </span>
                            @elseif(!empty($guru->user->nik))
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $guru->user->nik }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="shrink-0 h-12 w-12">
                                    @if($guru->user->foto)
                                        <img class="h-12 w-12 rounded-xl object-cover shadow-md"
                                             src="{{ asset('storage/' . $guru->user->foto) }}"
                                             alt="{{ $guru->user->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">
                                                {{ substr($guru->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $guru->user->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($guru->status_pegawai))
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $guru->status_pegawai }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($guru->user->no_telepon))
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $guru->user->no_telepon }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($guru->user->email))
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $guru->user->email }}
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
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data guru wali</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambahkan data guru wali terlebih dahulu</p>
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
