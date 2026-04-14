@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pelaporan')

@php
    $logoLeft = asset('img/logo-2.png');
    $logoRight = asset('img/logo-1.png');

    $kopSurat =
        '
    <div class="flex items-start gap-4 pb-3 mb-4 border-b-2 border-gray-800">
        <div class="shrink-0">
            <img src="' .
        $logoLeft .
        '" alt="Logo Pancacita" class="h-16 w-16 object-contain">
        </div>
        <div class="flex-1 text-center">
            <div class="text-sm font-semibold tracking-wide text-gray-700">PEMERINTAH ACEH</div>
            <div class="text-sm font-semibold tracking-wide text-gray-700">DINAS PENDIDIKAN</div>
            <div class="text-base font-bold tracking-wider text-gray-900 uppercase">SMK NEGERI 5 TELKOM BANDA ACEH</div>
            <div class="text-xs text-gray-600 mt-0.5">JL. Stadion H. Dimurtala No.5 Lampineung kota Banda Aceh Kode Pos 23125</div>
            <div class="text-xs text-gray-600">Telp/Fax. (0651) 7552314 Email: smkn5telkombandaaceh@gmail.com, Website: smkn5telkombandaaceh.sch.id</div>
        </div>
        <div class="shrink-0">
            <img src="' .
        $logoRight .
        '" alt="Logo SMKN 5" class="h-16 w-16 object-contain">
        </div>
    </div>';
@endphp

@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- ══ FILTER BAR ══ --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4 flex flex-wrap items-center gap-2">
            <select
                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer"
                id="lampiranSelect" onchange="handleLampiran(this.value)">
                <option value="">Pilih Lampiran</option>
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
                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                    <option>{{ $b }}</option>
                @endforeach
            </select>

            <select
                class="sub-filter hidden text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                id="sf-pertemuan">
                <option value="" disabled selected>-- Pertemuan --</option>
                @foreach ($pertemuanList as $pt)
                    <option>{{ $pt }}</option>
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
                type="button" onclick="handleLampiran(document.getElementById('lampiranSelect').value)">Cari</button>

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

            {{-- ══ LAMPIRAN A ══ --}}
            <div class="lampiran-panel bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-A">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-a')
                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        <div class="mt-14 border-t border-gray-700 pt-1 font-bold">
                            {{ $user->name ?? '____________________' }}</div>
                        <div class="text-xs text-gray-500">NIP. {{ $user->nip ?? '____________________' }}</div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN B ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-B">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-b')
                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        <div class="mt-14 border-t border-gray-700 pt-1 font-bold">
                            {{ $guru->user->name ?? '____________________' }}</div>
                        <div class="text-xs text-gray-500">NIP. {{ $guru->nip ?? '____________________' }}</div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN C ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-C">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-c')
                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        <div class="mt-14 border-t border-gray-700 pt-1 font-bold">
                            {{ $guru->user->name ?? '____________________' }}</div>
                        <div class="text-xs text-gray-500">NIP. {{ $guru->nip ?? '____________________' }}</div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN D ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-D">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-d')
                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        <div class="mt-14 border-t border-gray-700 pt-1 font-bold">
                            {{ $guru->user->name ?? '____________________' }}</div>
                        <div class="text-xs text-gray-500">NIP. {{ $guru->nip ?? '____________________' }}</div>
                    </div>
                </div>
            </div>

            {{-- ══ LAMPIRAN E ══ --}}
            <div class="lampiran-panel hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6" id="lp-E">
                {!! $kopSurat !!}
                @include('guru.laporan.lampiran-e')
                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm text-gray-700">
                        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
                        <div class="mt-14 border-t border-gray-700 pt-1 font-bold">
                            {{ $guru->user->name ?? '____________________' }}</div>
                        <div class="text-xs text-gray-500">NIP. {{ $guru->nip ?? '____________________' }}</div>
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
            ['sf-bulan', 'sf-pertemuan', 'sf-tahun', 'sf-semester', 'sf-semester-e']
            .forEach(id => document.getElementById(id).classList.add('hidden'));
            (subMap[val] || []).forEach(id => document.getElementById(id).classList.remove('hidden'));
            document.querySelectorAll('.lampiran-panel').forEach(p => p.classList.add('hidden'));
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

        document.addEventListener('DOMContentLoaded', () => {
            handleLampiran('A');
        });
    </script>
@endpush
