<div class="text-center mb-4">
    <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN B
    </div>
    <div class="text-base font-semibold text-gray-800">Catatan Perkembangan Siswa Binaan</div>
</div>
<div class="flex flex-wrap gap-2 mb-4">
    @foreach ($muridList as $i => $m)
        <button
            class="student-tab px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:border-blue-500 hover:text-blue-600 transition-colors {{ $loop->first ? '!bg-blue-600 !text-white !border-blue-600' : '' }}"
            onclick="switchStudent({{ $i }}, this)">{{ Str::words($m->name, 2, '') }}</button>
    @endforeach
</div>
@foreach ($muridList as $i => $m)
    <div class="student-pane {{ $loop->first ? '' : 'hidden' }}" id="sp-{{ $i }}">
        <div
            class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-3 text-sm text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-1">
            <div><span class="font-semibold">Nama Murid :</span> {{ $m->name }}</div>
            <div><span class="font-semibold">Kelas :</span> {{ $m->kelas ?? '-' }}</div>
            <div><span class="font-semibold">Periode Pemantauan :</span>
                {{ request('bulan', 'Januari 2026') }}</div>
            <div><span class="font-semibold">Guru Wali :</span> {{ $guru->user->name }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-blue-50 text-gray-700">
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Aspek
                            Pemantauan</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Deskripsi
                            Perkembangan</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Tindak Lanjut
                            Yang Dilakukan</th>
                        <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Keterangan
                            Tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['Akademik', 'Karakter', 'Sosial Emosional', 'Kedisiplinan', 'Potensi & Minat'] as $aspek)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="border border-gray-300 px-3 py-2 text-gray-700">{{ $aspek }}
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                <select name="b{{ $i }}_{{ Str::slug($aspek) }}_deskripsi"
                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <option>Sangat Berkembang</option>
                                    <option>Berkembang</option>
                                    <option selected>Mulai Berkembang</option>
                                    <option>Belum Berkembang</option>
                                </select>
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                <select name="b{{ $i }}_{{ Str::slug($aspek) }}_tindak"
                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <option>Pertahankan</option>
                                    <option selected>Perlu Ditingkatkan</option>
                                    <option>Butuh Motivasi</option>
                                    <option>Perlu Perhatian Khusus</option>
                                </select>
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                <textarea name="b{{ $i }}_{{ Str::slug($aspek) }}_ket" placeholder="Keterangan..."
                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 min-h-[60px]"></textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach