<div class="text-center mb-4 px-2">
    <div class="inline-block px-2 py-0.5 bg-blue-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN A</div>
    <div class="text-sm md:text-base font-semibold text-gray-800">Daftar Identitas Siswa Binaan Guru Wali</div>
    <div class="text-xs text-gray-500 mt-1">
        Tahun Ajaran: {{ $tahunAjaran }} | Guru Wali: {{ $user->name }}
    </div>
</div>

{{-- Mobile scroll hint --}}
<div class="md:hidden text-xs text-gray-500 text-center mb-2 px-2 print:hidden">
    ← Geser ke kanan untuk melihat semua data →
</div>

<div class="overflow-x-auto pb-2">
    <form action="{{ route('guru.lampiran-a.store') }}" method="post">
        @csrf
        <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">

        <table class="w-full min-w-[600px] md:min-w-[800px] text-xs md:text-sm border-collapse">
            <thead>
                <tr class="bg-blue-50 text-gray-700">
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-center font-semibold">NO</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-left font-semibold">Nama Murid</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-center font-semibold">NIS/NISN</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-center font-semibold">Kelas</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-center font-semibold">Jenis Kelamin</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-center font-semibold">Kontak Ortu</th>
                    <th class="border border-gray-300 px-2 md:px-3 py-2 text-left font-semibold">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muridList as $i)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="border border-gray-300 px-2 md:px-3 py-2 text-center text-gray-600">
                            {{ $loop->iteration }}</td>
                        <td class="border border-gray-300 px-2 md:px-3 py-2 font-semibold text-gray-800 text-xs md:text-sm">
                            {{ $i->name }}</td>
                        <td class="border border-gray-300 px-2 md:px-3 py-2 text-center text-gray-600 text-xs md:text-sm">
                            {{ $i->nisn ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 md:px-3 py-2 text-center text-gray-600 text-xs md:text-sm">
                            {{ $i->kelas->nama_kelas ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 md:px-3 py-2 text-center text-gray-600 text-xs md:text-sm">
                            {{ $i->gender ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 md:px-3 py-2 text-center text-gray-600 text-xs md:text-sm">
                            {{ $i->no_ortu ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            <textarea name="catatan[{{ $i->id }}]" placeholder="Tulis catatan..." class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 min-h-[50px] md:min-h-[60px]">{{ $catatanA[$i->id] ?? '' }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded mt-6 text-sm font-medium shadow-md hover:bg-blue-700 transition-colors print:hidden">
            Simpan
        </button>
    </form>
</div>
