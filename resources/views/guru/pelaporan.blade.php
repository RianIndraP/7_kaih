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

    <div class="p-6 bg-gray-50 min-h-screen" id="pelaporan-container">

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
                @foreach ([2024, 2025, 2026] as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

            {{-- Filter Semester untuk Lampiran D --}}
            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-semester">
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
        <div class="space-y-4" id="doc-wrap">

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
            <div class="lampiran-panel hidden" id="lp-A">
                @if (!request('tahun'))
                    {{-- Tampilan instruksi: Disamakan style-nya dengan pesan data kosong --}}
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-8 text-center my-4">
                        <svg class="w-16 h-16 mx-auto mb-4 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Tentukan Tahun Ajaran</h3>
                        <p class="text-sm text-blue-700">Silakan pilih tahun pada filter di atas untuk melihat daftar siswa
                            binaan.</p>
                    </div>
                @elseif (!$hasData['A'])
                    {{-- Jika data KOSONG --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-8 text-center my-4">
                        <svg class="w-16 h-16 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Data siswa belum ada</h3>
                        <p class="text-sm text-yellow-700">Data siswa tidak ditemukan untuk tahun ajaran
                            {{ $tahunAjaran }}</p>
                    </div>
                @else
                    {{-- Jika data ADA --}}
                    {!! $kopSurat !!}
                    @include('guru.laporan.lampiran-a')
                    @include('guru.laporan.ttd')
                @endif

            </div>

            {{-- ══ LAMPIRAN B ══ --}}
            <div class="lampiran-panel hidden" id="lp-B">
                @if ($hasData['B'])
                    {!! $kopSurat !!}
                    @include('guru.laporan.lampiran-b')
                    @include('guru.laporan.ttd')
                @else
                    {{-- Jika data KOSONG, tampilkan pesan peringatan mirip lampiran-message --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-8 text-center my-4">
                        <svg class="w-16 h-16 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Data Pertemuan Belum Ada</h3>
                        <p class="text-sm text-yellow-700">Tidak ada jadwal pertemuan yang ditemukan untuk filter ini.</p>
                    </div>
                @endif
            </div>

            {{-- ══ LAMPIRAN C ══ --}}
            <div class="lampiran-panel hidden" id="lp-C">
                @if ($pertemuan)
                    {{-- Hanya muncul jika ada data --}}
                    {!! $kopSurat !!}
                    @include('guru.laporan.lampiran-c')
                    @include('guru.laporan.ttd')
                @else
                    {{-- Pesan Error yang menggantikan seluruh isi panel --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center my-4">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-50 text-red-500 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Laporan Belum Tersedia</h3>
                        <p class="text-gray-500 mt-2 max-w-sm mx-auto">
                            Data untuk **Pertemuan {{ request('pertemuan') }}** tidak ditemukan.
                            Silakan lakukan absensi terlebih dahulu atau pilih pertemuan lain.
                        </p>
                        <div class="mt-6">
                            <button onclick="location.reload()" class="text-sm font-medium text-blue-600 hover:underline">
                                Refresh Halaman
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ══ LAMPIRAN D ══ --}}
            <div class="lampiran-panel hidden" id="lp-D">
                @if ($hasData['D'])
                    {!! $kopSurat !!}
                    @include('guru.laporan.lampiran-d')
                    @include('guru.laporan.ttd')
                @else
                    {{-- Jika data KOSONG, tampilkan pesan peringatan mirip lampiran-message --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-8 text-center my-4">
                        <svg class="w-16 h-16 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Data Pertemuan Belum Ada</h3>
                        <p class="text-sm text-yellow-700">Tidak ada jadwal pertemuan yang ditemukan untuk filter ini.</p>
                    </div>
                @endif
            </div>

            {{-- ══ LAMPIRAN E ══ --}}
            <div class="lampiran-panel hidden" id="lp-E">
                @if ($hasData['E'])
                    {!! $kopSurat !!}
                    @include('guru.laporan.lampiran-e')
                    @include('guru.laporan.ttd')
                @else
                    {{-- Jika data KOSONG, tampilkan pesan peringatan mirip lampiran-message --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-8 text-center my-4">
                        <svg class="w-16 h-16 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Data Pertemuan Belum Ada</h3>
                        <p class="text-sm text-yellow-700">Tidak ada jadwal pertemuan yang ditemukan untuk filter ini.</p>
                    </div>
                @endif
            </div>

        </div>{{-- end space-y-4 --}}
    </div>

@endsection

@push('styles')
    <style>
        .lampiran-panel.hidden {
            display: none;
        }

        /* Tampilan normal di layar - card style */
        .lampiran-panel {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            padding: 1.5rem;
        }

        @media print {

            /* ── Sembunyikan semua elemen dulu ── */
            body * {
                visibility: hidden;
            }

            /* ── Tampilkan hanya panel lampiran aktif ── */
            .lampiran-panel:not(.hidden),
            .lampiran-panel:not(.hidden) * {
                visibility: visible;
            }

            /* ── Posisikan panel ke sudut kiri atas kertas ── */
            .lampiran-panel:not(.hidden) {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                padding: 15mm !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: white !important;
            }

            /* ── Ukuran kertas & margin ── */
            @page {
                size: A4 portrait;
                margin: 0;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /* ── Reset border-radius & shadow semua elemen ── */
            * {
                border-radius: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            /* ── Pertahankan border tabel ── */
            table,
            th,
            td {
                border: 1px solid #000 !important;
                background: white !important;
            }

            /* ── Sembunyikan elemen print:hidden ── */
            .print\:hidden {
                display: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const subMap = {
            A: ['sf-tahun'],
            B: ['sf-bulan', 'sf-tahun'],
            C: ['sf-pertemuan'],
            D: ['sf-tahun', 'sf-semester'],
            E: ['sf-tahun', 'sf-semester']
        };

        function handleLampiran(val) {
            const msg = document.getElementById('lampiran-message');
            const allFilters = ['sf-bulan', 'sf-pertemuan', 'sf-tahun', 'sf-semester'];

            // 1. Reset Awal
            allFilters.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            document.querySelectorAll('.lampiran-panel').forEach(p => p.classList.add('hidden'));

            if (!val) {
                if (msg) msg.classList.remove('hidden');
                return;
            }
            if (msg) msg.classList.add('hidden');

            // 2. Logika Otomatisasi untuk B, D, dan E
            if (val === 'B' || val === 'D' || val === 'E') {
                const urlParams = new URLSearchParams(window.location.search);

                // 🔥 Perbaikan: Cek URL ATAU jika value masih di tahun pertama (2024) secara default
                if (!urlParams.get('tahun')) {
                    const elTahun = document.getElementById('sf-tahun');
                    const tahunSekarang = new Date().getFullYear().toString();
                    if (elTahun) elTahun.value = tahunSekarang;
                }

                // 🔥 Perbaikan: Cek URL untuk Semester
                if (!urlParams.get('semester')) {
                    const bulan = new Date().getMonth() + 1;
                    const semesterDefault = (bulan >= 7) ? 'Semester Ganjil' : 'Semester Genap';
                    const elSem = document.getElementById('sf-semester');
                    if (elSem) elSem.value = semesterDefault;
                }

                if (!urlParams.get('bulan')) {
                    const elBulan = document.getElementById('sf-bulan');
                    if (elBulan) elBulan.value = new Date().getMonth() + 1;
                }

            }

            // 3. Tampilkan sub-filter sesuai map
            (subMap[val] || []).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('hidden');
            });

            // 4. Tampilkan panel
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
            if (!lampiran) return alert('Pilih lampiran dulu!');

            let url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('lampiran', lampiran);

            const bulan = document.getElementById('sf-bulan').value;
            const pertemuan = document.getElementById('sf-pertemuan').value;
            const tahun = document.getElementById('sf-tahun').value;
            const semester = document.getElementById('sf-semester').value;

            if (bulan) url.searchParams.set('bulan', bulan);
            if (pertemuan) url.searchParams.set('pertemuan', pertemuan);
            if (tahun) url.searchParams.set('tahun', tahun);
            if (semester) url.searchParams.set('semester', semester);

            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const url = new URLSearchParams(window.location.search);
            const lampiran = url.get('lampiran');

            ['bulan', 'pertemuan', 'tahun', 'semester'].forEach(key => {
                const val = url.get(key);
                if (val) {
                    const el = document.getElementById('sf-' + key);
                    if (el) el.value = val;
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
