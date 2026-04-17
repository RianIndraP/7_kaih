<form action="{{ route('guru.lampiran-c.store') }}" method="post">
    @csrf
    <input type="hidden" name="pertemuan" value="{{ request('pertemuan', 1) }}">
    <div class="text-center mb-4">
        <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN C
        </div>
        <div class="text-base font-semibold text-gray-800">Catatan Pertemuan Guru Wali dengan Siswa</div>
        <div class="text-xs text-gray-500 mt-1">
            @if ($pertemuan)
                Pertemuan {{ request('pertemuan', 1) }} -
                {{ \Carbon\Carbon::parse($pertemuan->tanggal_mulai)->translatedFormat('d M Y') }} &nbsp;|&nbsp;
                Guru Wali: {{ $guru->user->name }}
            @else
                <span class="text-yellow-600 italic">
                    Pertemuan belum ada
                </span>
            @endif
        </div>
    </div>
    <div class="overflow-x-auto">
        @if (!$pertemuan)
            <div class="bg-yellow-100 text-yellow-700 p-3 rounded mb-4 text-sm print:hidden">
                ⚠️ Data pertemuan belum tersedia.<br>
                Laporan hanya bisa diisi setelah pertemuan berlangsung.
            </div>
        @endif
        {{-- Mobile Scroll Hint --}}
        <p class="md:hidden text-xs text-gray-500 mb-2 text-center">
            &larr; Geser ke kanan untuk melihat semua data &rarr;
        </p>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm border-collapse">
            <thead>
                <tr class="bg-blue-50 text-gray-700">
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">NO</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Tanggal Pertemuan</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Nama Murid</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Topik / Masalah Yang
                        Dibahas</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Tindak Lanjut</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muridList as $i => $m)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $i + 1 }}
                        </td>

                        {{-- Tanggal Pertemuan --}}
                        <td class="border border-gray-300 px-3 py-2 text-gray-700">
                            {{ $pertemuan ? \Carbon\Carbon::parse($pertemuan->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </td>

                        {{-- Nama Murid --}}
                        <td class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                            {{ $m->name }}
                        </td>

                        {{-- Topik --}}
                        <td class="border border-gray-300 px-2 py-1">
                            <textarea name="data[{{ $m->id }}][topik]" {{ !$pertemuan ? 'disabled' : '' }} placeholder="Topik/masalah..."
                                class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 min-h-[60px]"></textarea>
                        </td>

                        {{-- Tindak --}}
                        <td class="border border-gray-300 px-2 py-1">
                            <textarea name="data[{{ $m->id }}][tindak]" {{ !$pertemuan ? 'disabled' : '' }} placeholder="Tindak lanjut..."
                                class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 min-h-[60px]"></textarea>
                        </td>

                        {{-- Keterangan --}}
                        <td class="border border-gray-300 px-2 py-1 text-center">
                            @php
                                $status = $absensi[$m->id]->status ?? null;
                            @endphp

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
    </div>
    <button type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded print:hidden {{ !$pertemuan ? 'opacity-50 cursor-not-allowed' : '' }}"
        {{ !$pertemuan ? 'disabled' : '' }}>
        Simpan Lampiran C
    </button>
</form>
