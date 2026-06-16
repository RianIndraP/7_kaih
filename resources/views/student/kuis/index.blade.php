@extends('layouts.app')

@section('title', 'Kuis Literasi & Numerasi | SMK N 5 Telkom')
@section('page_title', 'Kuis Literasi & Numerasi')

@section('content')
<div class="space-y-6">

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">
        {{ session('success') }}
    </div>
    @endif

    @php
    $sections = [
    ['title' => 'Sedang Berlangsung / Belum Dikerjakan', 'data' => $kuisAktif, 'badge' => 'bg-green-50 text-green-700'],
    ['title' => 'Akan Datang', 'data' => $kuisTerjadwal, 'badge' => 'bg-amber-50 text-amber-700'],
    ['title' => 'Sudah Dikerjakan', 'data' => $kuisSelesai, 'badge' => 'bg-blue-50 text-blue-700'],
    ['title' => 'Kadaluarsa', 'data' => $kuisKadaluarsa, 'badge' => 'bg-gray-100 text-gray-500'],
    ];
    @endphp

    @foreach($sections as $section)
    @if($section['data']->count() > 0)
    <div>
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">{{ $section['title'] }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($section['data'] as $kuis)
            @php
            $clickable = in_array($kuis->status_siswa, ['sedang_berlangsung', 'belum_dikerjakan', 'sudah_dikerjakan']);
            $catColor = $kuis->kategori === 'literasi' ? 'bg-teal-50 text-teal-700' : 'bg-purple-50 text-purple-700';

            $statusLabel = match($kuis->status_siswa) {
            'sedang_berlangsung' => 'Sedang berlangsung',
            'belum_dikerjakan' => 'Belum dikerjakan',
            'sudah_dikerjakan' => 'Sudah dikerjakan',
            'kadaluarsa' => 'Kadaluarsa',
            'belum_dibuka' => 'Belum dibuka',
            default => '-',
            };
            @endphp

            <div onclick="{{ $clickable ? " window.location='" . route('student.kuis.show', $kuis->id) . "'" : '' }}"
                class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 {{ $clickable ? 'cursor-pointer
                hover:border-blue-300 hover:shadow-md transition-all' : 'opacity-60' }}">
                <div class="flex items-start justify-between mb-2 gap-2">
                    <div>
                        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded {{ $catColor }} mb-1.5">
                            {{ ucfirst($kuis->kategori) }}
                        </span>
                        <p class="font-semibold text-gray-900">{{ $kuis->judul }}</p>
                    </div>
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-full {{ $section['badge'] }} whitespace-nowrap">
                        {{ $statusLabel }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-3">Tema: {{ $kuis->tema }}</p>
                <div class="flex items-center gap-4 text-xs text-gray-500 border-t border-gray-100 pt-3">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                        {{ $kuis->durasi_menit }} menit
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        {{ $kuis->waktu_mulai->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach

    @if($kuisAktif->isEmpty() && $kuisSelesai->isEmpty() && $kuisKadaluarsa->isEmpty() && $kuisTerjadwal->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p>Belum ada kuis tersedia.</p>
    </div>
    @endif

</div>
@endsection