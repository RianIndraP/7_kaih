<form action="{{ route('guru.lampiran-c.store') }}" method="post">
    @csrf
    <input type="hidden" name="pertemuan" value="{{ request('pertemuan', 1) }}">

    <style>
        @media print {
            .print-table-container {
                overflow: visible !important;
            }
            .print-table {
                min-width: auto !important;
                width: 100% !important;
                font-size: 10px !important;
            }
            .print-table th,
            .print-table td {
                padding: 4px 6px !important;
                font-size: 10px !important;
            }
            .print-table th:nth-child(1),
            .print-table td:nth-child(1) {
                width: 5% !important;
            }
            .print-table th:nth-child(2),
            .print-table td:nth-child(2) {
                width: 15% !important;
            }
            .print-table th:nth-child(3),
            .print-table td:nth-child(3) {
                width: 20% !important;
            }
            .print-table th:nth-child(4),
            .print-table td:nth-child(4) {
                width: 25% !important;
            }
            .print-table th:nth-child(5),
            .print-table td:nth-child(5) {
                width: 25% !important;
            }
            .print-table th:nth-child(6),
            .print-table td:nth-child(6) {
                width: 10% !important;
            }
        }
    </style>

    @if ($pertemuan)
        {{-- ══ KONTEN JIKA DATA ADA ══ --}}
        <div class="text-center mb-6 print:hidden">
            <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN C</div>
            <div class="text-base font-semibold text-gray-800">Catatan Pertemuan Guru Wali dengan Siswa</div>
            <div class="text-xs text-gray-500 mt-1">
                Pertemuan {{ request('pertemuan', 1) }} -
                {{ \Carbon\Carbon::parse($pertemuan->tanggal_mulai)->translatedFormat('d M Y') }} &nbsp;|&nbsp;
                Guru Wali: {{ $guru->user->name }}
            </div>
        </div>

        <div class="overflow-x-auto print-table-container">
            <table class="w-full min-w-[900px] text-sm border-collapse print-table">
                <thead>
                    <tr class="bg-blue-50 text-gray-700">
                        <th class="border border-gray-300 px-3 py-2 text-center font-semibold w-10">NO</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold w-32">Tanggal Pertemuan</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold w-48">Nama Murid</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Topik / Masalah</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Tindak Lanjut</th>
                        <th class="border border-gray-300 px-3 py-2 text-center font-semibold w-24">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($muridList as $i => $m)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">{{ $i + 1 }}
                            </td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-700">
                                {{ \Carbon\Carbon::parse($pertemuan->tanggal_mulai)->translatedFormat('d M Y') }}
                            </td>
                            <td class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">{{ $m->name }}
                            </td>

                            @php $c = $catatanC[$m->id] ?? null; @endphp

                            <td class="border border-gray-300 px-2 py-1">
                                @if(filled($c->topik ?? ''))
                                    <div class="text-xs text-gray-700 print:block hidden">{{ $c->topik }}</div>
                                @endif
                                <textarea name="data[{{ $m->id }}][topik]" placeholder="Topik/masalah..."
                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:ring-2 focus:ring-blue-300 min-h-[60px] print:hidden">{{ $c->topik ?? '' }}</textarea>
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if(filled($c->tindak_lanjut ?? ''))
                                    <div class="text-xs text-gray-700 print:block hidden">{{ $c->tindak_lanjut }}</div>
                                @endif
                                <textarea name="data[{{ $m->id }}][tindak]" placeholder="Tindak lanjut..."
                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:ring-2 focus:ring-blue-300 min-h-[60px] print:hidden">{{ $c->tindak_lanjut ?? '' }}</textarea>
                            </td>
                            <td class="border border-gray-300 px-2 py-1 text-center">
                                @php $status = $absensi[$m->id]->status ?? null; @endphp
                                @if ($status == 'hadir')
                                    <span class="text-green-600 font-semibold">Hadir</span>
                                @elseif ($status == 'sakit')
                                    <span class="text-yellow-600">Sakit</span>
                                @elseif ($status == 'izin')
                                    <span class="text-blue-600">Izin</span>
                                @elseif ($status == 'tidak_hadir')
                                    <span class="text-red-600">Tidak Hadir</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors shadow-sm print:hidden">
                Simpan Lampiran C
            </button>
        </div>
    @else
        {{-- ══ TAMPILAN JIKA DATA KOSONG ══ --}}
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center my-4">
            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Data Pertemuan Belum Tersedia</h3>
            <p class="text-sm text-gray-500 max-w-xs mx-auto">
                Laporan untuk Lampiran C hanya dapat diakses jika sudah ada data absensi/pertemuan pada filter yang
                dipilih.
            </p>
        </div>
    @endif
</form>