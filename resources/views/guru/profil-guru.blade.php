@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Profil Guru')

@section('content')

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .inline-edit {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            color: #1e293b;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 2px 4px;
            border-radius: 4px;
            transition: background 0.15s;
        }

        .inline-edit:hover {
            background: #f0f9ff;
        }

        .inline-edit:focus {
            background: #eff6ff;
            color: #1d4ed8;
        }

        #mapPreviewMap {
            width: 100%;
            height: 144px;
            pointer-events: none;
        }

        #mapFull {
            width: 100%;
            height: 380px;
        }
    </style>

    <div class="p-6 bg-gray-50 min-h-screen">

        <h2 class="text-xl font-bold text-gray-900 mb-5">Profil Guru</h2>

        {{-- ===== PROFILE CARD ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-5">
            <div class="flex flex-col sm:flex-row gap-5 items-start">

                {{-- Foto --}}
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div id="photoCircle"
                        class="w-24 h-24 rounded-full bg-gray-200 border-4 border-gray-300
                            flex items-center justify-center overflow-hidden">
                        @if (!empty($user->foto))
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Guru"
                                class="w-full h-full object-cover" />
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                    <input type="file" id="photoInput" accept="image/*" class="hidden" />
                    <button onclick="document.getElementById('photoInput').click()"
                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5
                               rounded-lg transition-colors">
                        Ubah Foto
                    </button>
                </div>

                {{-- Info rows --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">

                    {{-- Nama --}}
                    <div class="flex items-center border-b border-gray-100 py-2">
                        <span class="text-xs text-gray-500 w-20 flex-shrink-0">Nama</span>
                        <span class="text-xs text-gray-400 mx-2">:</span>
                        <input type="text" id="p_nama" value="{{ $user->name }}" class="inline-edit flex-1"
                            placeholder="Nama lengkap" />
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="flex items-center border-b border-gray-100 py-2">
                        <span class="text-xs text-gray-500 w-20 flex-shrink-0">Tempat Lahir</span>
                        <span class="text-xs text-gray-400 mx-2">:</span>
                        <input type="text" id="p_tempat_lahir" value="{{ $user->tempat_lahir ?? '' }}"
                            class="inline-edit flex-1" placeholder="Tempat lahir" />
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="flex items-center py-2">
                        <span class="text-xs text-gray-500 w-20 flex-shrink-0">Tanggal Lahir</span>
                        <span class="text-xs text-gray-400 mx-2">:</span>
                        <input type="date" id="p_tanggal_lahir"
                            value="{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '' }}"
                            class="inline-edit flex-1 text-xs" placeholder="Tanggal lahir" />
                    </div>

                    {{-- NIP --}}
                    <div class="flex items-center py-2">
                        <span class="text-xs text-gray-500 w-20 flex-shrink-0">NIP</span>
                        <span class="text-xs text-gray-400 mx-2">:</span>
                        <input type="text" id="p_nip" value="{{ $user->nip ?? '-' }}"
                            class="inline-edit flex-1 bg-gray-50 cursor-not-allowed" placeholder="NIP" readonly />
                    </div>

                    {{-- NIK --}}
                    <div class="flex items-center py-2">
                        <span class="text-xs text-gray-500 w-20 flex-shrink-0">NIK</span>
                        <span class="text-xs text-gray-400 mx-2">:</span>
                        <input type="text" id="p_nik" value="{{ $user->nik ?? '-' }}"
                            class="inline-edit flex-1 bg-gray-50 cursor-not-allowed" placeholder="NIK" readonly />
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== DATA KEPEGAWAIAN + INFO KONTAK ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            {{-- Data Kepegawaian --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Data kepegawaian</h3>
                <div class="space-y-3">

                    <div>
                        <input type="text" id="f_status_guru" value="{{ $guru->status_pegawai ?? '' }}"
                            placeholder="Status guru"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  hover:border-gray-300 transition-colors" />
                    </div>

                    <div>
                        <select id="f_gender"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                   hover:border-gray-300 transition-colors cursor-pointer bg-white">
                            <option value="" disabled {{ empty($user->gender) ? 'selected' : '' }}>Gender</option>
                            <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>

                    <div>
                        <input type="text" id="f_unit_kerja" value="{{ $guru->unit_kerja ?? '' }}"
                            placeholder="Unit kerja"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  hover:border-gray-300 transition-colors" />
                    </div>

                </div>
            </div>

            {{-- Info Kontak dan Keluarga --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Info kontak dan keluarga</h3>
                <div class="space-y-3">

                    <div>
                        <input type="tel" id="f_hp" value="{{ $user->no_telepon ?? '' }}"
                            placeholder="Nomor yang dapat dihubungi"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  hover:border-gray-300 transition-colors" />
                    </div>

                    <div>
                        <input type="email" id="f_email" value="{{ $user->email ?? '' }}" placeholder="Email"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  hover:border-gray-300 transition-colors" />
                    </div>

                    {{-- MAP PREVIEW --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi:</label>

                        <div id="mapPreviewWrapper" onclick="openMapModal()"
                            class="relative w-full rounded-lg border border-gray-200 overflow-hidden cursor-pointer group"
                            style="height: 144px;">
                            <div id="mapPreviewMap"></div>
                            <div
                                class="absolute inset-x-0 bottom-0 bg-black/50 group-hover:bg-black/65
                                    transition-colors px-3 py-2 flex items-center gap-2 z-10">
                                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01" />
                                </svg>
                                <span class="text-white text-xs font-medium">Klik Maps Untuk Ganti Lokasi</span>
                            </div>
                        </div>

                        <div id="addressDisplay"
                            class="mt-2 text-xs text-gray-600 bg-gray-50 border border-gray-100
                                rounded-lg px-3 py-2 min-h-[32px] leading-relaxed">
                            {{ $user->alamat ?? '' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== SIMPAN ===== --}}
        <div class="flex justify-end">
            <button onclick="simpanProfil()"
                class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50
                       text-sm font-medium text-gray-700 px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>
        </div>

    </div>{{-- end page --}}


    {{-- ===== MAP MODAL ===== --}}
    <div id="mapModal" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Pilih Lokasi Alamat</h3>
                <button onclick="closeMapModal()"
                    class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div id="mapFull" class="rounded-xl border border-gray-200 overflow-hidden"></div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 pb-5 pt-0">
                <div class="flex-1 min-w-0">
                    <p id="coordText" class="text-xs text-gray-500">Klik pada peta untuk memilih lokasi</p>
                    <p id="coordAddr" class="text-xs text-gray-700 mt-1 leading-relaxed"></p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Tombol Hapus Lokasi --}}
                    <button id="btnHapusLokasi" onclick="clearLocation()"
                        class="flex-shrink-0 bg-red-100 hover:bg-red-200 text-red-600 text-sm font-medium
                               px-4 py-2.5 rounded-lg transition-colors {{ empty($user->latitude) ? 'hidden' : '' }}">
                        Hapus Lokasi
                    </button>

                    {{-- Tombol Konfirmasi --}}
                    <button onclick="confirmLocation()"
                        class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                               px-5 py-2.5 rounded-lg transition-colors">
                        Konfirmasi Lokasi
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ===== TOAST ===== --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[99999] flex items-center gap-2 text-white text-sm font-medium
            px-4 py-3 rounded-xl shadow-lg opacity-0 -translate-y-2 pointer-events-none
            transition-all duration-300 bg-green-600">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg">Profil berhasil disimpan!</span>
    </div>


    {{-- ===== LEAFLET JS ===== --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* ── State peta ─────────────────────────────────────────── */
        var selLat = {{ $user->latitude ?? 5.5536 }};
        var selLng = {{ $user->longitude ?? 95.3177 }};
        var pendingLat = selLat;
        var pendingLng = selLng;

        var mapPreview = null;
        var markerPreview = null;
        var mapFull = null;
        var markerFull = null;
        var geocodeTimer = null;

        /* ── Preview map (read-only) ────────────────────────────── */
        function initPreviewMap() {
            var container = document.getElementById('mapPreviewMap');
            if (!container || typeof L === 'undefined') return;

            mapPreview = L.map(container, {
                zoomControl: false,
                attributionControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                touchZoom: false,
                keyboard: false,
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapPreview);
            markerPreview = L.marker([selLat, selLng]).addTo(mapPreview);
            mapPreview.setView([selLat, selLng], 15);
        }

        function updatePreviewMap(lat, lng) {
            if (!mapPreview) return;
            markerPreview.setLatLng([lat, lng]);
            mapPreview.setView([lat, lng], 15);
        }

        /* ── Modal map (interaktif) ─────────────────────────────── */
        function openMapModal() {
            var modal = document.getElementById('mapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            pendingLat = selLat;
            pendingLng = selLng;
            document.getElementById('coordText').textContent =
                'Koordinat: ' + selLat.toFixed(6) + ', ' + selLng.toFixed(6);
            document.getElementById('coordAddr').textContent = '';

            setTimeout(function() {
                if (!mapFull) {
                    mapFull = L.map(document.getElementById('mapFull')).setView([selLat, selLng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(mapFull);
                    markerFull = L.marker([selLat, selLng], {
                        draggable: true
                    }).addTo(mapFull);

                    markerFull.on('dragend', function(e) {
                        pendingLat = e.target.getLatLng().lat;
                        pendingLng = e.target.getLatLng().lng;
                        updateCoordDisplay(pendingLat, pendingLng);
                        reverseGeocode(pendingLat, pendingLng);
                    });
                    mapFull.on('click', function(e) {
                        pendingLat = e.latlng.lat;
                        pendingLng = e.latlng.lng;
                        markerFull.setLatLng([pendingLat, pendingLng]);
                        updateCoordDisplay(pendingLat, pendingLng);
                        reverseGeocode(pendingLat, pendingLng);
                    });
                } else {
                    mapFull.setView([selLat, selLng], 15);
                    markerFull.setLatLng([selLat, selLng]);
                    mapFull.invalidateSize();
                }
            }, 150);
        }

        function closeMapModal() {
            var modal = document.getElementById('mapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMapModal();
        });

        function updateCoordDisplay(lat, lng) {
            document.getElementById('coordText').textContent =
                'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
        }

        function reverseGeocode(lat, lng) {
            document.getElementById('coordAddr').textContent = 'Mencari alamat...';
            clearTimeout(geocodeTimer);
            geocodeTimer = setTimeout(function() {
                fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng +
                        '&format=json&accept-language=id')
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        var addr = data.display_name || (lat.toFixed(5) + ', ' + lng.toFixed(5));
                        var el = document.getElementById('coordAddr');
                        el.textContent = addr;
                        el.dataset.fullAddr = addr;
                    })
                    .catch(function() {
                        document.getElementById('coordAddr').textContent = lat.toFixed(5) + ', ' + lng.toFixed(
                            5);
                    });
            }, 600);
        }

        function confirmLocation() {
            selLat = pendingLat;
            selLng = pendingLng;
            updatePreviewMap(selLat, selLng);
            var addrEl = document.getElementById('coordAddr');
            var fullAddr = addrEl.dataset.fullAddr || addrEl.textContent;
            if (fullAddr && fullAddr !== 'Mencari alamat...') {
                document.getElementById('addressDisplay').textContent = fullAddr;
            }
            closeMapModal();
        }

        /* ── Photo upload ───────────────────────────────────────── */
        document.getElementById('photoInput').addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var circle = document.getElementById('photoCircle');
                circle.innerHTML = '<img src="' + e.target.result +
                    '" alt="Foto Guru" class="w-full h-full object-cover"/>';
            };
            reader.readAsDataURL(file);
        });

        function clearLocation() {
            if (confirm("Apakah Anda yakin ingin menghapus lokasi dari profil?")) {

                fetch('{{ route('guru.profil.delete-location') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(result) {
                        if (result.success) {
                            // 1. Reset data di variabel JS
                            selLat = 5.5536; // Default Banda Aceh
                            selLng = 95.3177;
                            pendingLat = selLat;
                            pendingLng = selLng;

                            // 2. Update tampilan teks
                            document.getElementById('addressDisplay').textContent = 'Lokasi belum diatur';
                            document.getElementById('coordText').textContent = 'Lokasi dihapus';
                            document.getElementById('coordAddr').textContent = '';

                            // 3. Hapus marker dari peta
                            if (markerFull) mapFull.removeLayer(markerFull);
                            if (markerPreview) mapPreview.removeLayer(markerPreview);
                            markerFull = null;
                            markerPreview = null;

                            closeMapModal();

                            // 4. Panggil fungsi toast kamu (Hijau)
                            tampilkanToast(result.message, 'green');
                        } else {
                            // 5. Panggil fungsi toast kamu (Merah)
                            tampilkanToast(result.message || 'Gagal menghapus lokasi', 'red');
                        }
                    })
                    .catch(function(err) {
                        console.error(err);
                        tampilkanToast('Terjadi kesalahan koneksi ke server.', 'red');
                    });
            }
        }

        /* ── Simpan profil ──────────────────────────────────────── */
        /* ── Simpan profil (SINKRON DENGAN SISTEM SISWA) ── */
        function simpanProfil() {
            var btn = event.currentTarget;
            var alamatTeks = document.getElementById('addressDisplay').innerText.trim();

            // 1. Validasi Alamat (Opsional, aktifkan jika wajib pilih lokasi)
            if (!alamatTeks || alamatTeks === "" || alamatTeks === "Lokasi belum diatur") {
                tampilkanToast("Silakan pilih lokasi di peta terlebih dahulu!", "red");
                return;
            }

            // 2. Gunakan FormData agar Foto bisa terkirim
            var formData = new FormData();
            formData.append('nama', document.getElementById('p_nama').value);
            formData.append('tempat_lahir', document.getElementById('p_tempat_lahir').value);
            formData.append('tanggal_lahir', document.getElementById('p_tanggal_lahir').value);
            formData.append('nip', document.getElementById('p_nip').value);
            formData.append('status_guru', document.getElementById('f_status_guru').value);
            formData.append('gender', document.getElementById('f_gender').value);
            formData.append('unit_kerja', document.getElementById('f_unit_kerja').value);
            formData.append('hp', document.getElementById('f_hp').value);
            formData.append('email', document.getElementById('f_email').value);
            formData.append('alamat', alamatTeks);
            formData.append('latitude', selLat);
            formData.append('longitude', selLng);

            // 3. Ambil File Foto
            var photoInput = document.getElementById('photoInput');
            if (photoInput.files[0]) {
                formData.append('foto', photoInput.files[0]);
            }

            // 4. Loading State pada Tombol
            btn.disabled = true;
            var originalText = btn.innerHTML;
            btn.innerText = 'Menyimpan...';

            // 5. Kirim menggunakan Fetch
            fetch('{{ route('guru.profil.save') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData, // Mengirim FormData langsung
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(result) {
                    if (result.success) {
                        tampilkanToast('Profil berhasil disimpan!', 'green');
                    } else {
                        tampilkanToast('Error: ' + (result.message || 'Terjadi kesalahan'), 'red');
                    }
                })
                .catch(function(err) {
                    console.error(err);
                    tampilkanToast('Gagal terhubung ke server.', 'red');
                })
                .finally(function() {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }

        /* ── Toast ──────────────────────────────────────────────── */
        function tampilkanToast(pesan, warna) {
            var toast = document.getElementById('toast');
            var msg = document.getElementById('toastMsg');
            toast.classList.remove('bg-green-600', 'bg-red-600');
            toast.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
            msg.textContent = pesan;
            toast.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(function() {
                toast.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2800);
        }

        /* ── Init ───────────────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initPreviewMap, 300);
        });
    </script>

@endsection
