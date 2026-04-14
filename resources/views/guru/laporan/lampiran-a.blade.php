<div class="text-center mb-4">
    <div class="inline-block px-3 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN A
    </div>
    <div class="text-base font-semibold text-gray-800">Daftar Identitas Siswa Binaan Guru Wali</div>
    <div class="text-xs text-gray-500 mt-1">
        Tahun Ajaran: {{ $tahunAjaran }} &nbsp;|&nbsp;
        Guru Wali: {{ $user->name }}
    </div>
</div>
<div class="overflow-x-auto">
    <form action="{{ route('guru.lampiran-a.store') }}" method="post">
        @csrf
        <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">

        <table class="w-full min-w-[800px] text-sm border-collapse">
            <thead>
                <tr class="bg-blue-50 text-gray-700">
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">NO</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Nama Murid</th>
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">NIS/NISN</th>
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Kelas</th>
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Jenis Kelamin</th>
                    <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Kontak Orang Tua</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Catatan Khusus <br>
                        (jika ada)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muridList as $i)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $loop->iteration }}</td>
                        <td class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                            {{ $i->name }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $i->nisn ?? '-' }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $i->kelas->nama_kelas ?? '-' }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $i->gender }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                            {{ $i->no_ortu ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            <textarea name="catatan[{{ $i->id }}]" placeholder="Tulis catatan..." class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 min-h-[60px]">{{ $catatanA[$i->id] ?? '' }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-6">
            Simpan
        </button>
    </form>
</div>
