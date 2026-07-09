@extends('layouts.layouts-guru')

@section('title', 'Pemantauan Kuis Siswa | SMK N 5 Telkom')
@section('page_title', 'Pemantauan Kuis Siswa')

@section('content')
    <div class="space-y-5">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Sudah Menjawab</p>
                <p class="text-2xl font-bold text-green-600">{{ $totalSudah }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Belum Menjawab</p>
                <p class="text-2xl font-bold text-amber-600">{{ $totalSiswa - $totalSudah }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <div class="">
                    <select name="kuis_id" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        @if($kuisList->isEmpty())
                            <option value="">Belum ada kuis tersedia</option>
                        @else
                            @foreach($kuisList as $k)
                                <option value="{{ $k->id }}" {{ ($selectedKuis?->id ?? null) == $k->id ? 'selected' : '' }}>
                                    {{ $k->judul }} — {{ $k->tema }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua status</option>
                        <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah menjawab</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum menjawab</option>
                    </select>
                </div>
                <div class="ml-auto">
                    <button
                        class="flex justify-center items-center gap-1.5 w-full sm:w-auto px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                        type="submit" formaction="{{ route('guru.pemantauan-kuis.cetak') }}" formtarget="_blank">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Download PDF
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Siswa</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">NISN</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Jawaban</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-700">Waktu Kirim</th>
                            {{-- tambahkan setelah <th>Waktu Kirim</th> --}}
                            <th class="px-4 py-3 text-center font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $row)
                            @php
                                $statusBadge = match ($row['status']) {
                                    'sudah_dikerjakan' => ['bg-green-50 text-green-700', 'Sudah menjawab'],
                                    'sedang_berlangsung' => ['bg-blue-50 text-blue-700', 'Sedang mengerjakan'],
                                    'kadaluarsa' => ['bg-gray-100 text-gray-500', 'Kadaluarsa'],
                                    default => ['bg-amber-50 text-amber-700', 'Belum menjawab'],
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $row['siswa']->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $row['siswa']->nisn }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge[0] }}">
                                        {{ $statusBadge[1] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-[300px]">
                                    @if($row['jawaban']?->jawaban)
                                        <span class="line-clamp-2">{{ $row['jawaban']->jawaban }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada jawaban</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-gray-500 text-xs whitespace-nowrap">
                                    {{ $row['jawaban']?->waktu_kirim?->format('d M Y, H:i') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('guru.pemantauan-kuis.laporan', $row['siswa']->id) }}?kuis_id={{ $selectedKuis?->id }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                        </svg>
                                        Laporan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection