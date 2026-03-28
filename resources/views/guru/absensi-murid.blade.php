@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Absensi Murid')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

    {{-- ===== AREA INSTRUKSI + TOMBOL ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">
        <div class="flex flex-col sm:flex-row items-start gap-4">

            {{-- Teks instruksi --}}
            <div class="flex-1 text-sm text-gray-600 leading-relaxed border border-gray-200
                        rounded-lg p-4 bg-gray-50">
                Mohon dipilih status untuk siswa yang datang. Biarkan saja jika tidak hadir,
                absensi akan direfresh tiap 2 minggu setelah disimpan. Mohon di isi hati-hati.
                Jika tidak ada pertemuan mohon tekan <strong>Tidak ada pertemuan</strong>.
            </div>

            {{-- Tombol aksi --}}
            <div class="flex flex-col gap-2 flex-shrink-0">
                <button id="btnTidakAdaPertemuan" onclick="tandaiLibur()"
                        class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50
                               text-sm font-medium text-gray-700 rounded-lg transition-colors
                               shadow-sm whitespace-nowrap">
                    Tidak ada pertemuan
                </button>
                <button onclick="simpanAbsen()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-sm font-medium
                               text-white rounded-lg transition-colors shadow-sm whitespace-nowrap">
                    Simpan Absen
                </button>
            </div>
        </div>
    </div>

    {{-- ===== INFO PERTEMUAN + NAVIGASI ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4 mb-5
                flex flex-col sm:flex-row sm:items-center gap-3">

        {{-- Navigasi prev --}}
        <button onclick="navigasi(-1)"
                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50
                       transition-colors flex-shrink-0 w-9 h-9 flex items-center justify-center">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

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

        {{-- Navigasi next --}}
        <button onclick="navigasi(1)"
                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50
                       transition-colors flex-shrink-0 w-9 h-9 flex items-center justify-center">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Badge status pertemuan --}}
        <div id="badgeLibur" class="{{ $tidakAdaPertemuan ? '' : 'hidden' }}
                                    flex items-center gap-1.5 bg-yellow-50 border border-yellow-200
                                    rounded-lg px-3 py-1.5 flex-shrink-0">
            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <span class="text-xs font-semibold text-yellow-700">Libur</span>
        </div>
    </div>

    {{-- ===== TABEL ABSENSI ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Banner libur --}}
        <div id="bannerLibur"
             class="{{ $tidakAdaPertemuan ? 'flex' : 'hidden' }}
                    items-center gap-3 bg-yellow-50 border-b border-yellow-200 px-5 py-3">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                         001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
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
                            $ab     = $absensiList[$siswa->id] ?? null;
                            $status = $ab?->status ?? 'tidak_hadir';
                            $libur  = $ab?->tidak_ada_pertemuan ?? false;
                            $aktif  = $status !== 'tidak_hadir' && !$libur;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 absen-row"
                            data-siswa-id="{{ $siswa->id }}">

                            <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>

                            {{-- Checkbox: klik untuk aktifkan edit --}}
                            <td class="px-4 py-3">
                                <input type="checkbox"
                                       class="select-cb w-4 h-4 rounded border-gray-300
                                              text-blue-600 cursor-pointer"
                                       data-siswa="{{ $siswa->id }}"
                                       onchange="toggleEdit(this)"
                                       {{ $aktif ? 'checked' : '' }}
                                       {{ $tidakAdaPertemuan ? 'disabled' : '' }}/>
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-800">{{ $siswa->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $siswa->nisn ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $siswa->kelas?->nama_kelas ?? '-' }}</td>

                            {{-- Dropdown status --}}
                            <td class="px-4 py-3">
                                <div class="relative">
                                    <select class="status-select appearance-none w-full border rounded-lg
                                                   px-3 py-1.5 pr-8 text-sm focus:outline-none
                                                   focus:ring-2 focus:ring-blue-500 transition-colors
                                                   {{ $aktif ? 'border-gray-300 bg-white text-gray-800' : 'border-gray-200 bg-gray-100 text-gray-400' }}"
                                            data-siswa="{{ $siswa->id }}"
                                            {{ !$aktif ? 'disabled' : '' }}>
                                        <option value="tidak_hadir"
                                                {{ $status === 'tidak_hadir' ? 'selected' : '' }}>
                                            Tidak hadir
                                        </option>
                                        <option value="hadir"
                                                {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="sakit"
                                                {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="izin"
                                                {{ $status === 'izin'  ? 'selected' : '' }}>Izin</option>
                                    </select>
                                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4
                                                text-gray-400 pointer-events-none"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
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
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="toastMsg"></span>
</div>

<script>
/* ── State ───────────────────────────────────────── */
var BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

var pertemuan = {
    ke             : {{ $pertemuanInfo['ke'] }},
    tahun          : {{ $pertemuanInfo['tahun'] }},
    tanggal_mulai  : '{{ $pertemuanInfo['tanggal_mulai'] }}',
    tanggal_selesai: '{{ $pertemuanInfo['tanggal_selesai'] }}',
    label          : '{{ $pertemuanInfo['label'] }}',
};
var isLibur = {{ $tidakAdaPertemuan ? 'true' : 'false' }};

/* ── Toggle edit per baris ───────────────────────── */
function toggleEdit(cb) {
    var id  = cb.dataset.siswa;
    var sel = document.querySelector('.status-select[data-siswa="' + id + '"]');
    if (!sel) return;

    if (cb.checked) {
        sel.disabled = false;
        sel.className = sel.className
            .replace('bg-gray-100 text-gray-400 border-gray-200',
                     'bg-white text-gray-800 border-gray-300');
        sel.classList.remove('bg-gray-100','text-gray-400','border-gray-200');
        sel.classList.add('bg-white','text-gray-800','border-gray-300');
        // Default ke hadir saat pertama kali dicentang
        if (sel.value === 'tidak_hadir') sel.value = 'hadir';
    } else {
        sel.disabled = true;
        sel.value    = 'tidak_hadir';
        sel.classList.remove('bg-white','text-gray-800','border-gray-300');
        sel.classList.add('bg-gray-100','text-gray-400','border-gray-200');
    }
}

/* ── Simpan absensi ──────────────────────────────── */
function simpanAbsen() {
    if (isLibur) {
        tampilkanToast('Pertemuan ini sudah ditandai libur', 'red');
        return;
    }

    var absensi = [];
    document.querySelectorAll('.absen-row').forEach(function (row) {
        var id  = row.dataset.siswaId;
        var sel = row.querySelector('.status-select');
        absensi.push({
            siswa_id : parseInt(id),
            status   : sel ? sel.value : 'tidak_hadir',
        });
    });

    fetch('/guru/absensi-murid/store', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'Accept'       : 'application/json',
            'X-CSRF-TOKEN' : '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            absensi         : absensi,
            pertemuan_ke    : pertemuan.ke,
            tanggal_mulai   : pertemuan.tanggal_mulai,
            tanggal_selesai : pertemuan.tanggal_selesai,
        }),
    })
    .then(r => r.json())
    .then(function (res) {
        if (res.success) tampilkanToast('Absensi berhasil disimpan!', 'green');
        else tampilkanToast('Gagal: ' + (res.message || ''), 'red');
    })
    .catch(function () { tampilkanToast('Gagal terhubung ke server', 'red'); });
}

/* ── Tidak ada pertemuan ─────────────────────────── */
function tandaiLibur() {
    if (isLibur) {
        tampilkanToast('Pertemuan ini sudah ditandai libur', 'red');
        return;
    }
    if (!confirm('Tandai pertemuan ' + pertemuan.ke + ' sebagai libur?')) return;

    fetch('/guru/absensi-murid/tidak-ada-pertemuan', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'Accept'       : 'application/json',
            'X-CSRF-TOKEN' : '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            pertemuan_ke    : pertemuan.ke,
            tanggal_mulai   : pertemuan.tanggal_mulai,
            tanggal_selesai : pertemuan.tanggal_selesai,
        }),
    })
    .then(r => r.json())
    .then(function (res) {
        if (res.success) {
            isLibur = true;
            tampilkanToast('Pertemuan ditandai libur', 'green');
            setLiburUI(true);
        } else {
            tampilkanToast('Gagal: ' + (res.message || ''), 'red');
        }
    })
    .catch(function () { tampilkanToast('Gagal terhubung ke server', 'red'); });
}

/* ── Navigasi prev/next pertemuan ────────────────── */
function navigasi(arah) {
    var ke = pertemuan.ke + arah;
    if (ke < 1) return;

    fetch('/guru/absensi-murid/by-pertemuan?pertemuan_ke=' + ke + '&tahun=' + pertemuan.tahun, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(function (res) {
        if (!res.success) { tampilkanToast('Gagal memuat data', 'red'); return; }

        pertemuan = res.pertemuan;
        isLibur   = res.tidak_ada_pertemuan;

        // Update label
        var p = res.pertemuan;
        document.getElementById('labelPertemuan').textContent = p.label;
        document.getElementById('labelTanggal').textContent   = formatTanggal(p.tanggal_mulai)
            + ' — ' + formatTanggal(p.tanggal_selesai);

        // Update tabel
        renderTabel(res.data, isLibur);
        setLiburUI(isLibur);
    })
    .catch(function () { tampilkanToast('Gagal terhubung ke server', 'red'); });
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

    document.getElementById('tabelBody').innerHTML = data.map(function (s, i) {
        var aktif   = s.status !== 'tidak_hadir' && !libur;
        var checked = aktif ? 'checked' : '';
        var disCb   = libur ? 'disabled' : '';
        var disSel  = (!aktif || libur) ? 'disabled' : '';
        var selCls  = aktif
            ? 'bg-white text-gray-800 border-gray-300'
            : 'bg-gray-100 text-gray-400 border-gray-200';

        var opts = ['tidak_hadir','hadir','sakit','izin'].map(function (v) {
            var lbl = {tidak_hadir:'Tidak hadir',hadir:'Hadir',sakit:'Sakit',izin:'Izin'}[v];
            return '<option value="' + v + '"' + (s.status === v ? ' selected' : '') + '>' + lbl + '</option>';
        }).join('');

        var kelasNama = (typeof s.kelas === 'object' && s.kelas !== null) ? s.kelas.nama_kelas : (s.kelas || '-');

        return '<tr class="border-b border-gray-100 hover:bg-gray-50 absen-row" data-siswa-id="' + s.siswa_id + '">' +
            '<td class="px-4 py-3 text-gray-500">' + (i+1) + '</td>' +
            '<td class="px-4 py-3"><input type="checkbox" class="select-cb w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer" ' +
                'data-siswa="' + s.siswa_id + '" onchange="toggleEdit(this)" ' + checked + ' ' + disCb + '/></td>' +
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
    var badge  = document.getElementById('badgeLibur');

    if (libur) {
        banner.classList.remove('hidden'); banner.classList.add('flex');
        badge.classList.remove('hidden');  badge.classList.add('flex');
        // Disable semua interaksi
        document.querySelectorAll('.select-cb').forEach(function (cb) {
            cb.disabled = true; cb.checked = false;
        });
        document.querySelectorAll('.status-select').forEach(function (sel) {
            sel.disabled = true; sel.value = 'tidak_hadir';
            sel.classList.remove('bg-white','text-gray-800','border-gray-300');
            sel.classList.add('bg-gray-100','text-gray-400','border-gray-200');
        });
    } else {
        banner.classList.add('hidden'); banner.classList.remove('flex');
        badge.classList.add('hidden');  badge.classList.remove('flex');
    }
}

/* ── Toast ───────────────────────────────────────── */
function tampilkanToast(pesan, warna) {
    var t = document.getElementById('toast');
    t.classList.remove('bg-green-600','bg-red-600');
    t.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
    document.getElementById('toastMsg').textContent = pesan;
    t.classList.remove('opacity-0','-translate-y-2','pointer-events-none');
    t.classList.add('opacity-100','translate-y-0');
    setTimeout(function () {
        t.classList.add('opacity-0','-translate-y-2','pointer-events-none');
        t.classList.remove('opacity-100','translate-y-0');
    }, 3000);
}
</script>

@endsection