@extends('layouts.kepala-sekolah')

@section('title', 'Data Kelas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Kelas</h1>
        <p class="text-gray-600">Daftar kelas yang aktif di sekolah</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Kelas</p>
                    <p class="text-3xl font-bold">{{ $kelasList->count() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Siswa</p>
                    <p class="text-3xl font-bold">{{ $kelasList->sum('siswa_count') }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kelas</h2>
            <p class="text-sm text-gray-500 mt-1">Total {{ $kelasList->count() }} kelas aktif</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" id="searchKelas" placeholder="Cari kelas..."
                    class="pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-52 shadow-sm transition-all">
                <svg class="w-4 h-4 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Grid Kelas --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="kelasGrid">
        @forelse($kelasList as $kelas)
            @php
                $colors = [
                    0 => ['from' => '#3b82f6', 'to' => '#1d4ed8'],
                    1 => ['from' => '#8b5cf6', 'to' => '#6d28d9'],
                    2 => ['from' => '#ec4899', 'to' => '#be185d'],
                    3 => ['from' => '#f97316', 'to' => '#c2410c'],
                    4 => ['from' => '#10b981', 'to' => '#047857'],
                    5 => ['from' => '#06b6d4', 'to' => '#0e7490'],
                    6 => ['from' => '#f43f5e', 'to' => '#be123c'],
                    7 => ['from' => '#84cc16', 'to' => '#4d7c0f'],
                    8 => ['from' => '#a78bfa', 'to' => '#7c3aed'],
                    9 => ['from' => '#fb923c', 'to' => '#c2410c'],
                    10 => ['from' => '#34d399', 'to' => '#059669'],
                    11 => ['from' => '#38bdf8', 'to' => '#0284c7'],
                ];
                $c = $colors[($kelas->color_index ?? 0) % count($colors)];
            @endphp
            <div class="kelas-card rounded-2xl overflow-hidden border border-black/5 hover:-translate-y-1 hover:shadow-lg transition-all duration-200"
                data-nama="{{ strtolower($kelas->nama_kelas) }}">
                {{-- Top colored section --}}
                <div class="p-5 flex flex-col items-center gap-3 text-white"
                    style="background: linear-gradient(135deg, {{ $c['from'] }}, {{ $c['to'] }})">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">

                        @php
                            $n = strtoupper($kelas->nama_kelas);
                        @endphp

                        {{-- Jurusan Perangkat Lunak (RPL untuk kls 11-12, PPLG untuk kls 10) --}}
                        @if (str_contains($n, 'PPLG') || str_contains($n, 'RPL'))
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.8">
                                <polyline points="16,18 22,12 16,6" />
                                <polyline points="8,6 2,12 8,18" />
                            </svg>

                            {{-- Jurusan Jaringan & Telko (TKJ/TJA untuk kls 11-12, TJKT untuk kls 10) --}}
                        @elseif(str_contains($n, 'TKJ') || str_contains($n, 'TJA') || str_contains($n, 'TJKT'))
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.8">
                                <rect x="2" y="3" width="20" height="14" rx="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                            </svg>

                            {{-- Jurusan Perfilman (PF untuk kls 10-12) --}}
                        @elseif(str_contains($n, 'PF'))
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.8">
                                <polygon points="23,7 16,12 23,17" />
                                <rect x="1" y="5" width="15" height="14" rx="2" />
                            </svg>

                            {{-- Default untuk nama kelas lainnya --}}
                        @else
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.8">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-base leading-tight">{{ $kelas->nama_kelas }}</p>
                        <span class="text-xs text-white/70 bg-white/20 rounded-full px-2.5 py-0.5 mt-1.5 inline-block">
                            {{ $kelas->siswa_count ?? 0 }} siswa
                        </span>
                    </div>
                </div>
                {{-- Bottom actions --}}
                <div class="bg-white px-3 py-2.5 flex items-center justify-between border-t border-black/5">
                    {{-- Tombol Lihat --}}
                    <a href="{{ route('kepala-sekolah.data-kelas.show', $kelas->id) }}"
                        class="flex items-center gap-1 px-2 py-1.5 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg text-xs font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Lihat</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <p class="text-sm">Belum ada kelas</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    // Fitur Pencarian
    document.getElementById('searchKelas').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#kelasGrid .kelas-card').forEach(card => {
            const nama = card.dataset.nama || '';
            card.style.display = nama.includes(q) ? '' : 'none';
        });
    });
</script>
@endsection
