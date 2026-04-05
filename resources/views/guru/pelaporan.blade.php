@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pelaporan')

@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- ══ FILTER BAR ══ --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4 flex flex-wrap items-center gap-2">
            <select
                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400 cursor-pointer"
                id="lampiranSelect" onchange="handleLampiran(this.value)">
                <option value="A">Lampiran A</option>
                <option value="B">Lampiran B</option>
                <option value="C">Lampiran C</option>
                <option value="D">Lampiran D</option>
                <option value="E">Lampiran E</option>
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                id="sf-bulan">
                <option value="" disabled selected>-- Bulan --</option>
                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                    <option>{{ $b }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                id="sf-pertemuan">
                <option value="" disabled selected>-- Pertemuan --</option>
                @foreach ($pertemuanList ?? [] as $pt)
                    <option>{{ $pt }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                id="sf-tahun">
                <option value="" disabled selected>Tahun</option>
                @foreach ([2024, 2025, 2026] as $y)
                    <option>{{ $y }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                id="sf-semester">
                <option value="" disabled selected>Semester</option>
                <option>Semester Ganjil</option>
                <option>Semester Genap</option>
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                id="sf-semester-e">
                <option value="" disabled selected>Semester</option>
                <option>Semester Ganjil</option>
                <option>Semester Genap</option>
            </select>

            <span class="flex items-center gap-1 text-xs text-gray-400 italic">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                Pilih Lampiran yang ingin di unduh
            </span>

            <div class="flex-1"></div>

            <button
                class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                type="button">Cari</button>
            <button
                class="flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                type="button" onclick="window.print()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Download PDF
            </button>
        </div>

        {{-- ══ DOC WRAP ══ --}}
        <div class="space-y-4">

            @php
                $kopSurat = '
        <div class="flex items-start gap-4 pb-3 mb-4 border-b-2 border-gray-800">
            <div class="shrink-0">
                <img src="/images/logo-pancacita.png" alt="Logo Pancacita" class="h-16 w-16 object-contain" onerror="this.style.display=\'none\'">
            </div>
            <div class="flex-1 text-center">
                <div class="text-sm font-semibold tracking-wide text-gray-700">PEMERINTAH ACEH</div>
                <div class="text-sm font-semibold tracking-wide text-gray-700">DINAS PENDIDIKAN</div>
                <div class="text-base font-bold tracking-wider text-gray-900 uppercase">SMK NEGERI 5 TELKOM BANDA ACEH</div>
                <div class="text-xs text-gray-600 mt-0.5">JL. Stadion H. Dimurtala No.5 Lampineung kota Banda Aceh Kode Pos 23125</div>
                <div class="text-xs text-gray-600">Telp/Fax. (0651) 7552314 Email: smkn5telkombandaaceh@gmail.com, Website: smkn5telkombandaaceh.sch.id</div>
            </div>
            <div class="shrink-0">
                <img src="/images/logo-smkn5.png" alt="Logo SMK N 5" class="h-16 w-16 object-contain" onerror="this.style.display=\'none\'">
            </div>
        </div>';
            @endphp

            {{-- ══ LAMPIRAN A — Daftar Siswa ══ --}}
            <div class="lampiran-panel bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-A">
                {!! $kopSurat !!}
                <div class="text-center mb-4">
                    <div class="inline-block px-3 py-0.5 bg-red-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN A
                    </div>
                    <div class="text-base font-semibold text-gray-800">Daftar Identitas Siswa Binaan Guru Wali</div>
                    <div class="text-xs text-gray-500 mt-1">Kelas: {{ $guru->unit_kerja ?? 'XII RPL 1' }} &nbsp;|&nbsp;
                        Tahun Ajaran: {{ $tahunAjaran ?? '2025/2026' }} &nbsp;|&nbsp; Guru Wali:
                        {{ $guru->user->name ?? '-' }}</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">NO</th>
                                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Nama Siswa</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Kelas</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Jenis Kelamin</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Kontak Orang Tua</th>
                                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Catatan Khusus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($muridList as $i => $m)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                                        {{ $i + 1 }}</td>
                                    <td class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                                        {{ $m->name }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                                        {{ $m->kelas ?? '-' }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                                        {{ $m->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                                        {{ $m->no_ortu ?? '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <textarea name="catatan_a{{ $i + 1 }}" placeholder="Tulis catatan..."
                                            class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('guru.partials.ttd')
            </div>

            {{-- ══ LAMPIRAN B — Catatan Perkembangan Per Siswa ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-B">
                {!! $kopSurat !!}
                <div class="text-center mb-4">
                    <div class="inline-block px-3 py-0.5 bg-red-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN B
                    </div>
                    <div class="text-base font-semibold text-gray-800">Catatan Perkembangan Siswa Binaan</div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($muridList as $i => $m)
                        <button
                            class="student-tab px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:border-red-500 hover:text-red-600 transition-colors {{ $loop->first ? '!bg-red-600 !text-white !border-red-600' : '' }}"
                            onclick="switchStudent({{ $i }}, this)">{{ Str::words($m->name, 2, '') }}</button>
                    @endforeach
                </div>
                @foreach ($muridList as $i => $m)
                    <div class="student-pane {{ $loop->first ? '' : 'hidden' }}" id="sp-{{ $i }}">
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3 text-sm text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-1">
                            <div><span class="font-semibold">Nama Murid :</span> {{ $m->name }}</div>
                            <div><span class="font-semibold">Kelas :</span> {{ $m->kelas ?? '-' }}</div>
                            <div><span class="font-semibold">Periode Pemantauan :</span>
                                {{ request('bulan', 'Januari 2026') }}</div>
                            <div><span class="font-semibold">Guru Wali :</span> {{ $guru->user->name ?? '-' }}</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700">
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
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="border border-gray-300 px-3 py-2 text-gray-700">{{ $aspek }}
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <select name="b{{ $i }}_{{ Str::slug($aspek) }}_deskripsi"
                                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                    <option>Sangat Berkembang</option>
                                                    <option>Berkembang</option>
                                                    <option selected>Mulai Berkembang</option>
                                                    <option>Belum Berkembang</option>
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <select name="b{{ $i }}_{{ Str::slug($aspek) }}_tindak"
                                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                    <option>Pertahankan</option>
                                                    <option selected>Perlu Ditingkatkan</option>
                                                    <option>Butuh Motivasi</option>
                                                    <option>Perlu Perhatian Khusus</option>
                                                </select>
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <textarea name="b{{ $i }}_{{ Str::slug($aspek) }}_ket" placeholder="Keterangan..."
                                                    class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                @include('guru.partials.ttd')
            </div>

            {{-- ══ LAMPIRAN C — Catatan Pertemuan ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-C">
                {!! $kopSurat !!}
                <div class="text-center mb-4">
                    <div class="inline-block px-3 py-0.5 bg-red-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN C
                    </div>
                    <div class="text-base font-semibold text-gray-800">Catatan Pertemuan Guru Wali dengan Siswa</div>
                    <div class="text-xs text-gray-500 mt-1">Pertemuan: {{ request('pertemuan', 'Pertemuan 1') }}
                        &nbsp;|&nbsp; Guru Wali: {{ $guru->user->name ?? '-' }}</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
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
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-300 px-3 py-2 text-center text-gray-600">
                                        {{ $i + 1 }}</td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="date" name="c{{ $i }}_tanggal"
                                            class="text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-300 w-full">
                                    </td>
                                    <td class="border border-gray-300 px-3 py-2 font-semibold text-gray-800">
                                        {{ $m->name }}</td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <textarea name="c{{ $i }}_topik" placeholder="Topik/masalah..."
                                            class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <textarea name="c{{ $i }}_tindak" placeholder="Tindak lanjut..."
                                            class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <textarea name="c{{ $i }}_ket" placeholder="Keterangan..."
                                            class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('guru.partials.ttd')
            </div>

            {{-- ══ LAMPIRAN D — Rekap Pertemuan Bulanan ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-D">
                {!! $kopSurat !!}
                <div class="text-center mb-4">
                    <div class="inline-block px-3 py-0.5 bg-red-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN D
                    </div>
                    <div class="text-base font-semibold text-gray-800">Rekap Pertemuan Guru Wali Bulanan</div>
                </div>
                <div
                    class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 grid grid-cols-1 sm:grid-cols-2 gap-1 text-sm text-gray-700">
                    <div><span class="font-semibold">Nama Guru Wali :</span> {{ $guru->user->name ?? '-' }}</div>
                    <div><span class="font-semibold">Kelas/Murid Dampingan :</span> {{ $guru->unit_kerja ?? '-' }}</div>
                    <div><span class="font-semibold">Semester :</span> {{ request('semester', 'Genap/Ganjil') }}</div>
                    <div><span class="font-semibold">Tahun Ajaran :</span> {{ $tahunAjaran ?? '2025/2026' }}</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Bulan</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Jumlah Pertemuan
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Format
                                    (Individu/Kelompok)</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-semibold">Presentase Kehadiran
                                </th>
                                <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-300 px-3 py-2 text-gray-700">{{ $bulan }}</td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">
                                        <input type="number" name="d_{{ Str::slug($bulan) }}_jml" min="0"
                                            value="0"
                                            class="w-16 text-center text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-red-300">
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">
                                        <select name="d_{{ Str::slug($bulan) }}_format"
                                            class="text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            <option>Individu</option>
                                            <option>Kelompok</option>
                                        </select>
                                    </td>
                                    <td class="border border-gray-300 px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div
                                                    class="d-bar h-full w-0 bg-gradient-to-r from-red-500 to-yellow-400 rounded-full transition-all duration-300">
                                                </div>
                                            </div>
                                            <input type="number" name="d_{{ Str::slug($bulan) }}_pct" min="0"
                                                max="100" value="0"
                                                class="d-pct w-12 text-center text-xs border border-gray-200 rounded-md px-1.5 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-red-300"
                                                oninput="updateBar(this)">
                                            <span class="text-xs text-gray-400">%</span>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <textarea name="d_{{ Str::slug($bulan) }}_ket" placeholder="Keterangan..."
                                            class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 min-h-[60px]"></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('guru.partials.ttd')
            </div>

            {{-- ══ LAMPIRAN E — Dokumentasi Foto ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-E">
                {!! $kopSurat !!}
                <div class="text-center mb-4">
                    <div class="inline-block px-3 py-0.5 bg-red-600 text-white text-xs font-bold rounded mb-1">LAMPIRAN E
                    </div>
                    <div class="text-base font-semibold text-gray-800">Dokumentasi Foto Pertemuan Guru Wali</div>
                    <div class="text-xs text-gray-500 mt-1">2 Pertemuan per Bulan —
                        {{ request('semester', 'Semester Genap 2025/2026') }}</div>
                </div>

                <div
                    class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-700">
                    <div><span class="font-semibold">Guru Wali</span> : {{ $guru->user->name ?? '-' }}</div>
                    <div><span class="font-semibold">Kelas</span> : {{ $guru->unit_kerja ?? '-' }}</div>
                    <div><span class="font-semibold">Tahun Ajaran</span> : {{ $tahunAjaran ?? '2025/2026' }}</div>
                </div>

                @php
                    $months = [
                        ['bulan' => 'Januari 2026', 'p1_date' => '6 Januari 2026', 'p2_date' => '20 Januari 2026'],
                        ['bulan' => 'Februari 2026', 'p1_date' => '3 Februari 2026', 'p2_date' => '17 Februari 2026'],
                        ['bulan' => 'Maret 2026', 'p1_date' => '3 Maret 2026', 'p2_date' => '17 Maret 2026'],
                        ['bulan' => 'April 2026', 'p1_date' => '7 April 2026', 'p2_date' => '21 April 2026'],
                        ['bulan' => 'Mei 2026', 'p1_date' => '5 Mei 2026', 'p2_date' => '19 Mei 2026'],
                        ['bulan' => 'Juni 2026', 'p1_date' => '2 Juni 2026', 'p2_date' => '16 Juni 2026'],
                    ];
                    $pg = 1;
                @endphp

                <div class="space-y-5">
                    @foreach ($months as $month)
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div
                                class="flex items-center gap-2 bg-gray-50 border-b border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ $month['bulan'] }}
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                                @foreach (['p1' => $month['p1_date'], 'p2' => $month['p2_date']] as $pKey => $pDate)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div
                                            class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b border-gray-200">
                                            <span class="text-xs font-semibold text-red-600">Pertemuan
                                                {{ $pg }}</span>
                                            <span class="text-xs text-gray-500">{{ $pDate }}</span>
                                        </div>
                                        <div class="relative h-44 bg-gray-100 cursor-pointer flex items-center justify-center group"
                                            onclick="triggerUpload('img-{{ $pg }}')"
                                            id="zone-{{ $pg }}">
                                            <img id="preview-{{ $pg }}" src="" alt=""
                                                class="hidden w-full h-full object-cover absolute inset-0">
                                            <div id="ph-{{ $pg }}"
                                                class="flex flex-col items-center gap-1.5 text-gray-400 group-hover:text-red-400 transition-colors">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"
                                                        ry="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <polyline points="21 15 16 10 5 21" />
                                                </svg>
                                                <span class="text-xs font-medium">Klik untuk unggah foto</span>
                                                <span class="text-xs text-gray-400">JPG, PNG — maks. 5MB</span>
                                            </div>
                                            <input type="file" id="img-{{ $pg }}" accept="image/*"
                                                class="hidden" onchange="previewPhoto(this,{{ $pg }})">
                                        </div>
                                        <div class="px-3 py-2 border-t border-gray-100">
                                            <input type="text" name="cap_{{ $pg }}"
                                                placeholder="Keterangan foto (opsional)..."
                                                class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        </div>
                                    </div>
                                    @php $pg++; @endphp
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @include('guru.partials.ttd')
            </div>

        </div>{{-- end space-y-4 --}}
    </div>

@endsection

@push('scripts')
    <script>
        const subMap = {
            A: [],
            B: ['sf-bulan'],
            C: ['sf-pertemuan'],
            D: ['sf-tahun', 'sf-semester'],
            E: ['sf-semester-e']
        };

        function handleLampiran(val) {
            // Hide all sub-filters
            ['sf-bulan', 'sf-pertemuan', 'sf-tahun', 'sf-semester', 'sf-semester-e']
            .forEach(id => document.getElementById(id).classList.add('hidden'));

            // Show relevant sub-filters
            (subMap[val] || []).forEach(id => {
                document.getElementById(id).classList.remove('hidden');
            });

            // Hide all lampiran panels
            document.querySelectorAll('.lampiran-panel').forEach(p => p.classList.add('hidden'));

            // Show selected panel
            const panel = document.getElementById('lp-' + val);
            if (panel) panel.classList.remove('hidden');
        }

        function switchStudent(idx, btn) {
            document.querySelectorAll('.student-tab').forEach(b => {
                b.classList.remove('!bg-red-600', '!text-white', '!border-red-600');
            });
            document.querySelectorAll('.student-pane').forEach(p => p.classList.add('hidden'));

            btn.classList.add('!bg-red-600', '!text-white', '!border-red-600');
            const pane = document.getElementById('sp-' + idx);
            if (pane) pane.classList.remove('hidden');
        }

        function triggerUpload(id) {
            document.getElementById(id).click();
        }

        function previewPhoto(input, idx) {
            if (!input.files || !input.files[0]) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('preview-' + idx);
                const placeholder = document.getElementById('ph-' + idx);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }

        function updateBar(input) {
            const pct = Math.min(100, Math.max(0, input.value || 0));
            const bar = input.closest('td').querySelector('.d-bar');
            if (bar) bar.style.width = pct + '%';
        }

        // Initialize: show Lampiran A on page load
        document.addEventListener('DOMContentLoaded', () => {
            handleLampiran('A');
        });
    </script>
@endpush
