@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Absensi Murid')

@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- ===== AREA INSTRUKSI + TOMBOL ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
            <!-- Menggunakan items-stretch agar tinggi kotak teks otomatis sama dengan tinggi kolom tombol -->
            <div class="flex flex-col sm:flex-row items-stretch gap-4">

                {{-- Teks instruksi: flex-1 agar memenuhi sisa ruang, flex & items-center agar teks di tengah secara vertikal --}}
                <div
                    class="flex-1 flex items-center justify-center text-center 
                    text-sm text-gray-600 leading-relaxed border border-gray-200 
                    rounded-lg p-4 bg-gray-50">
                    <p>
                        Mohon dipilih status untuk siswa yang datang. Biarkan saja jika tidak hadir,
                        absensi akan direfresh tiap 2 minggu setelah disimpan. Mohon di isi hati-hati.
                        Jika tidak ada pertemuan mohon tekan <strong>Tidak ada pertemuan</strong>.
                    </p>
                </div>

                {{-- Tombol aksi: flex-col agar tombol berjejer ke bawah di sisi kanan --}}
                <div class="flex flex-col gap-2 flex-shrink-0 justify-center">
                    <button id="btnTidakAdaPertemuan" onclick="tandaiLibur()"
                        class="px-4 py-2 bg-white border border-gray-300
                       text-sm font-medium text-gray-700 rounded-lg transition-colors
                       shadow-sm whitespace-nowrap w-full">
                        Tidak ada pertemuan
                    </button>

                    <button onclick="simpanAbsen()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-sm font-medium
                       text-white rounded-lg transition-colors shadow-sm whitespace-nowrap w-full">
                        Simpan Absen
                    </button>

                    <input type="file" id="fotoInput" accept="image/*" class="hidden">

                    <button onclick="uploadFoto()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-sm font-medium 
                       text-white rounded-lg shadow-sm w-full">
                        Upload Dokumentasi
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== INFO PERTEMUAN ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4 mb-5 text-center">

            {{-- Info pertemuan --}}
            <div class="flex-1 text-center">
                <p class="text-sm font-semibold text-gray-800" id="labelPertemuan">
                    {{ $pertemuanInfo['label'] }}
                </p>
                <p class="text-xs text-gray-500 mt-0.5" id="labelTanggal">
                    {{ \Carbon\Carbon::parse($pertemuanInfo['tanggal_mulai'])->translatedFormat('d M Y') }}
                    &mdash;
                    {{ \Carbon\Carbon::parse($pertemuanInfo['tanggal_selesai'])->translatedFormat('d M Y') }}
                </p>
            </div>

            {{-- Badge status pertemuan --}}
            <div id="badgeLibur"
                class="{{ $tidakAdaPertemuan ? '' : 'hidden' }}
                                    flex items-center gap-1.5 bg-yellow-50 border border-yellow-200
                                    rounded-lg px-3 py-1.5 flex-shrink-0">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                                                                                 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <span class="text-xs font-semibold text-yellow-700">Libur</span>
            </div>
        </div>

        @if ($fotoPertemuan?->foto_dokumentasi)
            <div class="mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Foto Dokumentasi:</p>
                <img src="{{ asset('storage/' . $fotoPertemuan->foto_dokumentasi) }}"
                    class="rounded-xl w-64 border shadow hover:scale-105 transition">
            </div>
        @endif

        {{-- ===== TABEL ABSENSI ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Banner libur --}}
            <div id="bannerLibur"
                class="{{ $tidakAdaPertemuan ? 'flex' : 'hidden' }}
                    items-center gap-3 bg-yellow-50 border-b border-yellow-200 px-5 py-3">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                                                                 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <span class="text-sm font-medium text-yellow-700">
                    Pertemuan ini ditandai <strong>Libur / Tidak Ada Pertemuan</strong>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 w-16">Select</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">nama murid</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">kelas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 w-36">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        @forelse ($siswaList as $i => $siswa)
                            @php
                                $ab = $absensiList[$siswa->id] ?? null;
                                $status = $ab?->status ?? 'tidak_hadir';
                                $libur = $ab?->status === 'libur';
                                $aktif = $status !== 'tidak_hadir' && !$libur;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 absen-row"
                                data-siswa-id="{{ $siswa->id }}">

                                <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>

                                {{-- Checkbox: klik untuk aktifkan edit --}}
                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        class="select-cb w-4 h-4 rounded border-gray-300
                                              text-blue-600 cursor-pointer"
                                        data-siswa="{{ $siswa->id }}" onchange="toggleEdit(this)"
                                        {{ $aktif ? 'checked' : '' }} {{ $tidakAdaPertemuan ? 'disabled' : '' }} />
                                </td>

                                <td class="px-4 py-3 font-medium text-gray-800">{{ $siswa->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $siswa->kelas?->nama_kelas ?? '-' }}</td>

                                {{-- Dropdown status --}}
                                <td class="px-4 py-3">
                                    <div class="relative">
                                        <select
                                            class="status-select appearance-none w-full border rounded-lg
                                                   px-3 py-1.5 pr-8 text-sm focus:outline-none
                                                   focus:ring-2 focus:ring-blue-500 transition-colors
                                                   {{ $aktif ? 'border-gray-300 bg-white text-gray-800' : 'border-gray-200 bg-gray-100 text-gray-400' }}"
                                            data-siswa="{{ $siswa->id }}" {{ !$aktif ? 'disabled' : '' }}>
                                            <option value="tidak_hadir" {{ $status === 'tidak_hadir' ? 'selected' : '' }}>
                                                Tidak hadir
                                            </option>
                                            <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir
                                            </option>
                                            <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit
                                            </option>
                                            <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin
                                            </option>
                                        </select>
                                        <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4
                                                text-gray-400 pointer-events-none"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                    Tidak ada siswa yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[9999] flex items-center gap-2 text-white text-sm font-medium
            px-4 py-3 rounded-xl shadow-lg opacity-0 -translate-y-2 pointer-events-none
            transition-all duration-300 bg-green-600">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg"></span>
    </div>

    <script>
        /* ── State ───────────────────────────────────────── */
        var BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        var pertemuan = {
            ke: {{ $pertemuanInfo['ke'] }},
            tahun: {{ $pertemuanInfo['tahun'] }},
            tanggal_mulai: '{{ $pertemuanInfo['tanggal_mulai'] }}',
            tanggal_selesai: '{{ $pertemuanInfo['tanggal_selesai'] }}',
            label: '{{ $pertemuanInfo['label'] }}',
        };
        var isLibur = {{ $tidakAdaPertemuan ? 'true' : 'false' }};

        /* ── Toggle edit per baris ───────────────────────── */
        function toggleEdit(cb) {
            var id = cb.dataset.siswa;
            var sel = document.querySelector('.status-select[data-siswa="' + id + '"]');
            if (!sel) return;

            if (cb.checked) {
                sel.disabled = false;
                sel.className = sel.className
                    .replace('bg-gray-100 text-gray-400 border-gray-200',
                        'bg-white text-gray-800 border-gray-300');
                sel.classList.remove('bg-gray-100', 'text-gray-400', 'border-gray-200');
                sel.classList.add('bg-white', 'text-gray-800', 'border-gray-300');
                // Default ke hadir saat pertama kali dicentang
                if (sel.value === 'tidak_hadir') sel.value = 'hadir';
            } else {
                sel.disabled = true;
                sel.value = 'tidak_hadir';
                sel.classList.remove('bg-white', 'text-gray-800', 'border-gray-300');
                sel.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
            }
        }

        /* ── Simpan absensi ──────────────────────────────── */
        function simpanAbsen() {
            if (isLibur) {
                tampilkanToast('Pertemuan ini sudah ditandai libur', 'red');
                return;
            }

            var absensi = [];
            document.querySelectorAll('.absen-row').forEach(function(row) {
                var id = row.dataset.siswaId;
                var sel = row.querySelector('.status-select');
                absensi.push({
                    siswa_id: parseInt(id),
                    status: sel ? sel.value : 'tidak_hadir',
                });
            });

            fetch('/guru/absensi-murid/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        absensi: absensi,
                        pertemuan_ke: pertemuan.ke,
                        tanggal_mulai: pertemuan.tanggal_mulai,
                        tanggal_selesai: pertemuan.tanggal_selesai,
                    }),
                })
                .then(r => r.json())
                .then(function(res) {
                    if (res.success) tampilkanToast('Absensi berhasil disimpan!', 'green');
                    else tampilkanToast('Gagal: ' + (res.message || ''), 'red');
                })
                .catch(function() {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        /* ── Tidak ada pertemuan ─────────────────────────── */
        function tandaiLibur() {

            // 🔴 JIKA SUDAH LIBUR → BATALKAN
            if (isLibur) {

                if (!confirm('Batalkan status libur?')) return;

                fetch('/guru/absensi-murid/batalkan-libur', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            pertemuan_ke: pertemuan.ke,
                            tanggal_mulai: pertemuan.tanggal_mulai,
                        }),
                    })
                    .then(r => r.json())
                    .then(function(res) {
                        if (res.success) {
                            isLibur = false;
                            tampilkanToast('Libur dibatalkan', 'green');
                            setLiburUI(false);
                            updateButton();
                        } else {
                            tampilkanToast('Gagal membatalkan', 'red');
                        }
                    })
                    .catch(function() {
                        tampilkanToast('Gagal koneksi server', 'red');
                    });

                return;
            }

            // 🟡 JIKA BELUM LIBUR → SET LIBUR
            if (!confirm('Tandai pertemuan ' + pertemuan.ke + ' sebagai libur?')) return;

            fetch('/guru/absensi-murid/tidak-ada-pertemuan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        pertemuan_ke: pertemuan.ke,
                        tanggal_mulai: pertemuan.tanggal_mulai,
                        tanggal_selesai: pertemuan.tanggal_selesai,
                    }),
                })
                .then(r => r.json())
                .then(function(res) {
                    if (res.success) {
                        isLibur = true;
                        tampilkanToast('Pertemuan ditandai libur', 'green');
                        setLiburUI(true);
                        updateButton();
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || ''), 'red');
                    }
                })
                .catch(function() {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        function updateButton() {
            var btn = document.getElementById('btnTidakAdaPertemuan');

            if (isLibur) {
                btn.textContent = 'Batalkan Libur';
                btn.classList.remove('bg-white');
                btn.classList.add('bg-red-600', 'text-white');
            } else {
                btn.textContent = 'Tidak ada pertemuan';
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('bg-white');
            }
        }

        function formatTanggal(str) {
            if (!str) return '';
            var d = new Date(str);
            return d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();
        }

        /* ── Render tabel dari data AJAX ─────────────────── */
        function renderTabel(data, libur) {
            if (!data || !data.length) {
                document.getElementById('tabelBody').innerHTML =
                    '<tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Tidak ada siswa</td></tr>';
                return;
            }

            document.getElementById('tabelBody').innerHTML = data.map(function(s, i) {
                var aktif = s.status !== 'tidak_hadir' && !libur;
                var checked = aktif ? 'checked' : '';
                var disCb = libur ? 'disabled' : '';
                var disSel = (!aktif || libur) ? 'disabled' : '';
                var selCls = aktif ?
                    'bg-white text-gray-800 border-gray-300' :
                    'bg-gray-100 text-gray-400 border-gray-200';

                var opts = ['tidak_hadir', 'hadir', 'sakit', 'izin'].map(function(v) {
                    var lbl = {
                        tidak_hadir: 'Tidak hadir',
                        hadir: 'Hadir',
                        sakit: 'Sakit',
                        izin: 'Izin'
                    } [v];
                    return '<option value="' + v + '"' + (s.status === v ? ' selected' : '') + '>' + lbl +
                        '</option>';
                }).join('');

                var kelasNama = (typeof s.kelas === 'object' && s.kelas !== null) ? s.kelas.nama_kelas : (s.kelas ||
                    '-');

                return '<tr class="border-b border-gray-100 hover:bg-gray-50 absen-row" data-siswa-id="' + s
                    .siswa_id + '">' +
                    '<td class="px-4 py-3 text-gray-500">' + (i + 1) + '</td>' +
                    '<td class="px-4 py-3"><input type="checkbox" class="select-cb w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer" ' +
                    'data-siswa="' + s.siswa_id + '" onchange="toggleEdit(this)" ' + checked + ' ' + disCb +
                    '/></td>' +
                    '<td class="px-4 py-3 font-medium text-gray-800">' + s.nama + '</td>' +
                    '<td class="px-4 py-3 text-gray-600">' + s.nisn + '</td>' +
                    '<td class="px-4 py-3 text-gray-600">' + kelasNama + '</td>' +
                    '<td class="px-4 py-3"><div class="relative">' +
                    '<select class="status-select appearance-none w-full border rounded-lg px-3 py-1.5 pr-8 text-sm ' +
                    'focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors ' + selCls + '" ' +
                    'data-siswa="' + s.siswa_id + '" ' + disSel + '>' + opts + '</select>' +
                    '<svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" ' +
                    'fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>' +
                    '</svg></div></td>' +
                    '</tr>';
            }).join('');
        }

        /* ── Update UI libur ─────────────────────────────── */
        function setLiburUI(libur) {
            var banner = document.getElementById('bannerLibur');
            var badge = document.getElementById('badgeLibur');

            if (libur) {
                banner.classList.remove('hidden');
                banner.classList.add('flex');
                badge.classList.remove('hidden');
                badge.classList.add('flex');
                // Disable semua interaksi
                document.querySelectorAll('.select-cb').forEach(function(cb) {
                    cb.disabled = true;
                    cb.checked = false;
                });
                document.querySelectorAll('.status-select').forEach(function(sel) {
                    sel.disabled = true;
                    sel.value = 'tidak_hadir';
                    sel.classList.remove('bg-white', 'text-gray-800', 'border-gray-300');
                    sel.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
                });
            } else {
                banner.classList.add('hidden');
                banner.classList.remove('flex');
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }
        }

        /* ── Toast ───────────────────────────────────────── */
        function tampilkanToast(pesan, warna) {
            var t = document.getElementById('toast');
            t.classList.remove('bg-green-600', 'bg-red-600');
            t.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
            document.getElementById('toastMsg').textContent = pesan;
            t.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
            t.classList.add('opacity-100', 'translate-y-0');
            setTimeout(function() {
                t.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
                t.classList.remove('opacity-100', 'translate-y-0');
            }, 3000);
        }

        function uploadFoto() {
            document.getElementById('fotoInput').click();
        }

        document.getElementById('fotoInput').addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;

            var formData = new FormData();
            formData.append('foto', file);
            formData.append('pertemuan_ke', pertemuan.ke);
            formData.append('tanggal_mulai', pertemuan.tanggal_mulai);
            formData.append('tanggal_selesai', pertemuan.tanggal_selesai);

            fetch('/guru/absensi-murid/upload-foto', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        tampilkanToast('Foto berhasil diupload', 'green');
                    } else {
                        tampilkanToast('Gagal upload', 'red');
                    }
                })
                .catch(() => tampilkanToast('Error upload', 'red'));
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateButton();
        });
    </script>

@endsection
