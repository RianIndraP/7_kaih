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
                <select name="kuis_id" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach($kuisList as $k)
                        <option value="{{ $k->id }}" {{ ($selectedKuis?->id ?? null) == $k->id ? 'selected' : '' }}>
                            {{ $k->judul }} — {{ $k->tema }}
                        </option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua status</option>
                    <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah menjawab</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum menjawab</option>
                </select>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection