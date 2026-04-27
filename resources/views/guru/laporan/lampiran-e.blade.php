<div class="text-center mb-4 print:hidden">
    <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN E
    </div>
    <div class="text-base font-semibold text-gray-800">Dokumentasi Foto Pertemuan Guru Wali</div>
    <div class="text-xs text-gray-500 mt-1">2 Pertemuan per Bulan —
        {{ request('semester', 'Semester Genap 2025/2026') }}</div>
</div>
<div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4 flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-700 print:hidden">
    <div><span class="font-semibold">Guru Wali</span> : {{ $guru->user->name }}</div>
    <div><span class="font-semibold">Kelas</span> : {{ $guru->unit_kerja }}</div>
    <div><span class="font-semibold">Tahun Ajaran</span> : {{ $tahunAjaran }}</div>
</div>

<div class="space-y-5">

    @foreach ($fotoPertemuan as $bulan => $items)
        <div class="border border-gray-200 rounded-xl overflow-hidden">

            {{-- HEADER BULAN --}}
            <div class="bg-blue-50 border-b border-blue-100 px-4 py-2.5 text-sm font-semibold text-gray-700">
                {{ $bulan }}
            </div>

            <div class="p-4 space-y-4">

                {{-- LOOP PER 2 ITEM (2 FOTO PER BARIS) --}}
                @foreach ($items->chunk(2) as $row)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        @foreach ($row as $data)
                            @php
                                $tanggalObj = \Carbon\Carbon::parse($data->tanggal_mulai);
                                $tanggal = $tanggalObj->translatedFormat('d F Y');
                                $foto = $data->foto_dokumentasi;
                                $caption = "Dokumentasi pertemuan {$data->pertemuan_ke} tanggal {$tanggal}";
                            @endphp

                            <div class="border border-gray-200 rounded-lg overflow-hidden">

                                {{-- HEADER --}}
                                <div
                                    class="flex items-center justify-between px-3 py-2 bg-blue-50 border-b border-blue-100">
                                    <span class="text-xs font-semibold text-blue-600">
                                        Pertemuan {{ $data->pertemuan_ke }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $tanggal }}
                                    </span>
                                </div>

                                {{-- FOTO --}}
                                <div class="relative h-44 bg-gray-100 flex items-center justify-center group">

                                    {{-- JIKA ADA FOTO --}}
                                    @if ($foto)
                                        <img src="{{ asset('storage/' . $foto) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-gray-400 text-xs">
                                            Belum ada foto
                                        </div>
                                    @endif
                                </div>

                                {{-- CAPTION --}}
                                <div class="px-3 py-2 border-t border-gray-100 text-xs text-gray-600">
                                    {{ $caption }}
                                </div>

                            </div>
                        @endforeach

                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
