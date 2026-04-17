<div class="text-center mb-4">
    <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN D
    </div>
    <div class="text-base font-semibold text-gray-800">Rekap Pertemuan Guru Wali Bulanan</div>
</div>
<div
    class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4 grid grid-cols-1 sm:grid-cols-2 gap-1 text-sm text-gray-700">
    <div><span class="font-semibold">Nama Guru Wali :</span> {{ $guru->user->name }}</div>
    <div><span class="font-semibold">Kelas/Murid Dampingan :</span> {{ $guru->unit_kerja }}</div>
    <div><span class="font-semibold">Semester :</span> {{ request('semester', 'Genap/Ganjil') }}</div>
    <div><span class="font-semibold">Tahun Ajaran :</span> {{ $tahunAjaran }}</div>
</div>
{{-- Mobile Scroll Hint --}}
<p class="md:hidden text-xs text-gray-500 mb-2 text-center">
    &larr; Geser ke kanan untuk melihat semua data &rarr;
</p>
<div class="overflow-x-auto">
    <table class="w-full min-w-[700px] text-sm border-collapse">
        <thead>
            <tr class="bg-blue-50 text-gray-700">
                <th class="border border-gray-300 px-3 py-2 text-left font-semibold w-[20%]">Bulan</th>
                <th class="border border-gray-300 px-3 py-2 text-center font-semibold w-[20%]">Jumlah Pertemuan
                </th>
                <th class="border border-gray-300 px-3 py-2 text-center font-semibold w-[30%]">Format
                    (Individu/Kelompok)</th>
                <th class="border border-gray-300 px-3 py-2 text-center font-semibold w-[30%]">Presentase Kehadiran
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $bulanList = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];
            @endphp
            @foreach ($bulanList as $num => $nama)
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="border border-gray-300 px-3 py-2 text-gray-700">{{ $nama }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">
                        {{ $rekapD[$num]['jumlah'] ?? 0 }}
                    </td>

                    <td class="border border-gray-300 px-2 py-1 text-center">
                        <select name="data[{{ $num }}][format]"
                            class="text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="individu">Individu</option>
                            <option value="kelompok" selected>Kelompok</option>
                        </select>
                    </td>
                    <td class="border border-gray-300 px-3 py-2">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div
                                    class="d-bar h-full w-0 bg-gradient-to-r from-blue-500 to-blue-300 rounded-full transition-all duration-300">
                                </div>
                            </div>
                            {{ $rekapD[$num]['persentase'] ?? 0 }}%
                            <span class="text-xs text-gray-400">%</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
