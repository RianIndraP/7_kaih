@extends('layouts.admin')

@section('title', 'Pesan Bantuan | Admin')
@section('page_title', 'Pesan Bantuan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Pesan Bantuan</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar pesan bantuan dari siswa</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span>{{ $pesanBantuan->count() ?? 0 }} pesan</span>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center font-medium text-gray-700 w-12">#</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Nama Pengirim</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Kategori</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Judul</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Isi Pesan</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanBantuan as $index => $pesan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-600">{{ substr($pesan->nama_pengirim, 0, 1) }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $pesan->nama_pengirim }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $kategoriColors = [
                                    'teknis' => 'bg-blue-50 text-blue-700',
                                    'akademik' => 'bg-green-50 text-green-700',
                                    'lainnya' => 'bg-gray-50 text-gray-700',
                                    'default' => 'bg-purple-50 text-purple-700'
                                ];
                                $color = $kategoriColors[$pesan->kategori] ?? $kategoriColors['default'];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 {{ $color }} rounded-md text-xs font-medium capitalize">{{ $pesan->kategori }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 font-medium">{{ $pesan->judul }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $pesan->isi }}">{{ $pesan->isi }}</td>
                        <td class="px-4 py-3 text-center text-gray-500 text-xs">{{ $pesan->created_at?->diffForHumans() ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada pesan bantuan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
