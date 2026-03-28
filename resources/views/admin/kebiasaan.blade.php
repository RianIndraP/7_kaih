@extends('layouts.admin')

@section('title', 'Data 7 Kebiasaan | SMK N 5 Telkom Banda Aceh')

@section('content')

<div class="p-6">
    {{-- Header dengan icon settings --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Data 7 Kebiasaan</h1>
        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94
                         3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724
                         1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572
                         1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31
                         -.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724
                         1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
    </div>

    {{-- Filter Section --}}
    <form method="GET" action="{{ route('admin.kebiasaan') }}" class="mb-6">
        <div class="flex flex-wrap items-end gap-4">
            {{-- Guru Wali Dropdown --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Guru Wali</label>
                <select name="guru_wali_id" class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    @foreach ($guruList as $guru)
                        <option value="{{ $guru->id }}" {{ request('guru_wali_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->user?->name ?? 'Guru #' . $guru->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            {{-- Search --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..."
                           class="w-48 px-3 py-2 pl-9 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.kebiasaan') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Terapkan
                </button>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-12">#</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Siswa</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Guru Wali</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Penyelesaian Form</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($kebiasaan as $index => $item)
                    @php
                        $persen = 0;
                        if ($item->user) {
                            $persen = $item->persentaseSelesai();
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $kebiasaan->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $item->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user?->waliKelas?->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden max-w-[100px]">
                                    <div class="h-full bg-gray-800 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600 min-w-[40px]">{{ $persen }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada data kebiasaan untuk tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($kebiasaan->hasPages())
        <div class="mt-4 flex items-center justify-center gap-1">
            {{-- First page --}}
            <a href="{{ $kebiasaan->url(1) }}" class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 {{ $kebiasaan->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                &lt;&lt;
            </a>
            
            {{-- Previous page --}}
            <a href="{{ $kebiasaan->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 {{ $kebiasaan->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                &lt;
            </a>

            {{-- Page numbers --}}
            @foreach ($kebiasaan->getUrlRange(1, $kebiasaan->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="px-3 py-1.5 border rounded text-sm {{ $page == $kebiasaan->currentPage() ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                    {{ $page }}
                </a>
            @endforeach

            {{-- Next page --}}
            <a href="{{ $kebiasaan->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 {{ $kebiasaan->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                &gt;
            </a>

            {{-- Last page --}}
            <a href="{{ $kebiasaan->url($kebiasaan->lastPage()) }}" class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 {{ $kebiasaan->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                &gt;&gt;
            </a>
        </div>
    @endif
</div>

@endsection
