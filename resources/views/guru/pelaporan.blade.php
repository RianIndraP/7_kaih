@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pelaporan')

@php
    $logoLeft = asset('img/logo-2.png');
    $logoRight = asset('img/logo-1.png');

    $kopSurat =
        '
    <div class="flex items-center gap-2 md:gap-4 pb-3 mb-4 border-b-2 border-gray-800">
        <div class="shrink-0">
            <img src="' .
        $logoLeft .
        '" alt="Logo Pancacita" class="h-10 w-10 md:h-16 md:w-16 object-contain">
        </div>
        <div class="flex-1 text-center px-1">
            <div class="text-xs md:text-sm font-semibold tracking-wide text-gray-700">PEMERINTAH ACEH</div>
            <div class="text-xs md:text-sm font-semibold tracking-wide text-gray-700">DINAS PENDIDIKAN</div>
            <div class="text-sm md:text-base font-bold tracking-wider text-gray-900 uppercase leading-tight">SMK NEGERI 5 TELKOM BANDA ACEH</div>
            <div class="text-[10px] md:text-xs text-gray-600 mt-0.5 leading-tight">JL. Stadion H. Dimurtala No.5, Banda Aceh 23125</div>
            <div class="text-[10px] md:text-xs text-gray-600 leading-tight">Telp: (0651) 7552314 | Email: smkn5@sch.id</div>
        </div>
        <div class="shrink-0">
            <img src="' .
        $logoRight .
        '" alt="Logo SMKN 5" class="h-10 w-10 md:h-16 md:w-16 object-contain">
        </div>
    </div>';
@endphp

@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- ══ FILTER BAR ══ --}}
        <div
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4 flex flex-wrap items-center gap-2 print:hidden">
            <select
                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer"
                id="lampiranSelect" onchange="handleLampiran(this.value)">
                <option value="" selected>Pilih Lampiran</option>
                <option value="A">Lampiran A</option>
                <option value="B">Lampiran B</option>
                <option value="C">Lampiran C</option>
                <option value="D">Lampiran D</option>
                <option value="E">Lampiran E</option>
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-bulan">
                <option value="" disabled selected>-- Bulan --</option>
                @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $b)
                    <option value="{{ $num }}">{{ $b }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-pertemuan">
                <option value="" disabled selected>-- Pertemuan --</option>
                @foreach ($pertemuanData as $pt)
                    <option value="{{ $pt->pertemuan_ke }}">
                        Pertemuan {{ $pt->pertemuan_ke }} -
                        {{ \Carbon\Carbon::parse($pt->tanggal_mulai)->format('d-m-Y') }}
                    </option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-tahun">
                <option value="" disabled selected>Tahun</option>
                @foreach ([2024, 2025, 2026] as $y)
                    <option>{{ $y }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-semester">
                <option value="" disabled selected>Semester</option>
                <option>Semester Ganjil</option>
                <option>Semester Genap</option>
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-semester-e">
                <option value="Semester Ganjil">Semester Ganjil</option>
                <option value="Semester Genap">Semester Genap</option>
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
                type="button" onclick="applyFilter()">Cari</button>

            <button
                class="flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
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

            {{-- ══ PILIH LAMPIRAN MESSAGE ══ --}}
            <div id="lampiran-message"
                class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center print:hidden">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Silahkan Pilih Lampiran</h3>
                <p class="text-sm text-gray-600">Pilih lampiran dari dropdown di atas untuk melihat atau mengisi laporan.
                </p>
            </div>

            {{-- ══ LAMPIRAN A ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-A">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-a')
                <div class="mt-8 flex justify-between items-start px-10">
                    {{-- Tanda Tangan Kepala Sekolah (Kiri) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Mengetahui,</div>
                        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
                        {{-- Area Tanda Tangan & Stempel --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $kepalaSekolah->name ?? 'NAMA KEPALA SEKOLAH, M.Pd' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $kepalaSekolah->nip ?? '19XXXXXXXXXXXXX' }}
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan Guru (Kanan) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        {{-- Area Tanda Tangan --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $user->name ?? '____________________' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN B ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-B">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-b')
                <div class="mt-8 flex justify-between items-start px-10">
                    {{-- Tanda Tangan Kepala Sekolah (Kiri) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Mengetahui,</div>
                        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
                        {{-- Area Tanda Tangan & Stempel --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $kepalaSekolah->name ?? 'NAMA KEPALA SEKOLAH, M.Pd' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $kepalaSekolah->nip ?? '19XXXXXXXXXXXXX' }}
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan Guru (Kanan) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        {{-- Area Tanda Tangan --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $user->name ?? '____________________' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN C ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-C">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-c')
                <div class="mt-8 flex justify-between items-start px-10">
                    {{-- Tanda Tangan Kepala Sekolah (Kiri) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Mengetahui,</div>
                        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
                        {{-- Area Tanda Tangan & Stempel --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $kepalaSekolah->name ?? 'NAMA KEPALA SEKOLAH, M.Pd' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $kepalaSekolah->nip ?? '19XXXXXXXXXXXXX' }}
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan Guru (Kanan) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        {{-- Area Tanda Tangan --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $user->name ?? '____________________' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN D ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-D">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-d')
                <div class="mt-8 flex justify-between items-start px-10">
                    {{-- Tanda Tangan Kepala Sekolah (Kiri) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Mengetahui,</div>
                        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
                        {{-- Area Tanda Tangan & Stempel --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $kepalaSekolah->name ?? 'NAMA KEPALA SEKOLAH, M.Pd' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $kepalaSekolah->nip ?? '19XXXXXXXXXXXXX' }}
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan Guru (Kanan) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        {{-- Area Tanda Tangan --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $user->name ?? '____________________' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN E ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-E">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-e')
                <div class="mt-8 flex justify-between items-start px-10">
                    {{-- Tanda Tangan Kepala Sekolah (Kiri) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Mengetahui,</div>
                        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
                        {{-- Area Tanda Tangan & Stempel --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $kepalaSekolah->name ?? 'NAMA KEPALA SEKOLAH, M.Pd' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $kepalaSekolah->nip ?? '19XXXXXXXXXXXXX' }}
                            </div>
                        </div>
                    </div>

                    {{-- Tanda Tangan Guru (Kanan) --}}
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        {{-- Area Tanda Tangan --}}
                        <div class="mt-20">
                            <div class="font-bold border-b border-gray-700 inline-block px-1">
                                {{ $user->name ?? '____________________' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
                        </div>
                    </div>
                </div>
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
            const msg = document.getElementById('lampiran-message');

            // kalau belum pilih apa-apa
            if (!val) {
                if (msg) msg.classList.remove('hidden');

                document.querySelectorAll('.lampiran-panel')
                    .forEach(p => p.classList.add('hidden'));

                return;
            }

            if (val === 'E') {
                const bulan = new Date().getMonth() + 1; // bulan sekarang
                let semester = (bulan >= 7) ? 'Semester Ganjil' : 'Semester Genap';
                const el = document.getElementById('sf-semester-e');
                if (el) el.value = semester;
            }
            // kalau sudah pilih
            if (msg) msg.classList.add('hidden');

            [
                'sf-bulan',
                'sf-pertemuan',
                'sf-tahun',
                'sf-semester',
                'sf-semester-e'
            ].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });

            (subMap[val] || []).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('hidden');
            });

            document.querySelectorAll('.lampiran-panel')
                .forEach(p => p.classList.add('hidden'));

            const panel = document.getElementById('lp-' + val);
            if (panel) panel.classList.remove('hidden');
        }

        function switchStudent(idx, btn) {
            document.querySelectorAll('.student-tab').forEach(b => {
                b.classList.remove('!bg-blue-600', '!text-white', '!border-blue-600');
            });
            document.querySelectorAll('.student-pane').forEach(p => p.classList.add('hidden'));
            btn.classList.add('!bg-blue-600', '!text-white', '!border-blue-600');
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

        function applyFilter() {
            const lampiran = document.getElementById('lampiranSelect').value;
            const bulan = document.getElementById('sf-bulan').value;
            const pertemuan = document.getElementById('sf-pertemuan').value;
            const tahun = document.getElementById('sf-tahun').value;
            let semester = '';

            // 🔥 ambil semester sesuai lampiran
            if (lampiran === 'E') {
                semester = document.getElementById('sf-semester-e').value;
            } else {
                semester = document.getElementById('sf-semester').value;
            }
            let url = new URL(window.location.href);

            if (bulan) url.searchParams.set('bulan', bulan);
            if (tahun) url.searchParams.set('tahun', tahun);
            if (lampiran) url.searchParams.set('lampiran', lampiran);
            if (pertemuan) url.searchParams.set('pertemuan', pertemuan);
            if (semester) url.searchParams.set('semester', semester);

            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const url = new URLSearchParams(window.location.search);
            const lampiran = url.get('lampiran');

            ['bulan', 'pertemuan', 'tahun', 'semester'].forEach(key => {
                const val = url.get(key);
                if (val) {
                    if (key === 'semester') {
                        const lampiran = url.get('lampiran');
                        const el = (lampiran === 'E') ?
                            document.getElementById('sf-semester-e') :
                            document.getElementById('sf-semester');
                        if (el) el.value = val;
                    } else {
                        const el = document.getElementById('sf-' + key);
                        if (el) el.value = val;
                    }
                }
            });

            if (lampiran) {
                document.getElementById('lampiranSelect').value = lampiran;
                handleLampiran(lampiran);
            } else {
                handleLampiran('')
            }
        });
    </script>
@endpush
