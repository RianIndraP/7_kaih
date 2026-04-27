@extends('layouts.kepala-sekolah')

@section('title', 'SMK N 5 Telkom Banda Aceh | Profil Kepala Sekolah')

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
            background: #faf5ff;
        }

        .inline-edit:focus {
            background: #f3e8ff;
            color: #7c3aed;
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

        /* Blue/indigo theme overrides (matching student sidebar) */
        .greeting-gradient {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 100%);
        }
    </style>

    <div class="min-h-screen page-bg">
        <div class="px-3 sm:px-4 md:px-6 py-4 max-w-5xl lg:max-w-6xl xl:max-w-7xl mx-auto">
            {{-- Header yang compact --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6 shadow-md text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Profil Kepala Sekolah</h1>
                        <p class="text-blue-100 text-xs mt-1">Kelola informasi pribadi dan profesional</p>
                    </div>
                </div>
            </div>

            {{-- ===== PROFILE CARD ===== --}}
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-3 sm:p-4 md:p-5 mb-4 sm:mb-5 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row gap-4 sm:gap-5 items-center lg:items-start">

                {{-- Foto Profile yang proporsional --}}
                <div class="flex flex-col items-center gap-2 sm:gap-3 flex-shrink-0">
                    <div class="relative">
                        <div id="photoCircle"
                            class="w-20 h-20 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 border-3 border-white shadow-lg overflow-hidden group hover:scale-105 transition-transform">
                            @if (!empty($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Kepala Sekolah"
                                    class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center shadow-md">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <input type="file" id="photoInput" accept="image/*" class="hidden" />
                    <button onclick="document.getElementById('photoInput').click()"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all shadow-sm hover:shadow-md">
                        Ubah Foto
                    </button>
                </div>

                {{-- Info Personal yang proporsional --}}
                <div class="flex-1 w-full">
                    <h3 class="text-base font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center gap-2">
                        <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                        Informasi Personal
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        
                        {{-- Nama --}}
                        <div class="bg-gray-50 rounded-md p-2 sm:p-3 hover:bg-gray-100 transition-colors">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">Nama Lengkap</label>
                            <input type="text" id="p_nama" value="{{ $user->name }}" 
                                class="w-full bg-transparent border-none text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1 text-sm"
                                placeholder="Nama lengkap" />
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="bg-gray-50 rounded-md p-2 sm:p-3 hover:bg-gray-100 transition-colors">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">Tempat Lahir</label>
                            <input type="text" id="p_tempat_lahir" value="{{ $user->tempat_lahir ?? '' }}"
                                class="w-full bg-transparent border-none text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1 text-sm"
                                placeholder="Tempat lahir" />
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="bg-gray-50 rounded-md p-2 sm:p-3 hover:bg-gray-100 transition-colors">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">Tanggal Lahir</label>
                            <input type="date" id="p_tanggal_lahir"
                                value="{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '' }}"
                                class="w-full bg-transparent border-none text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1 text-sm"
                                placeholder="Tanggal lahir" />
                        </div>

                        {{-- NIP --}}
                        <div class="bg-gray-50 rounded-md p-2">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">NIP</label>
                            <input type="text" id="p_nip" value="{{ $user->nip ?? '-' }}"
                                class="w-full bg-transparent border-none text-gray-500 font-medium cursor-not-allowed rounded px-2 py-1 text-sm"
                                placeholder="NIP" readonly />
                        </div>

                        {{-- NIK --}}
                        <div class="bg-gray-50 rounded-md p-2">
                            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">NIK</label>
                            <input type="text" id="p_nik" value="{{ $user->nik ?? '-' }}"
                                class="w-full bg-transparent border-none text-gray-500 font-medium cursor-not-allowed rounded px-2 py-1 text-sm"
                                placeholder="NIK" readonly />
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DATA KEPEGAWAIAN + INFO KONTAK ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-3 mb-4 sm:mb-5">

            {{-- Data Kepegawaian --}}
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-2 sm:p-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2 mb-2 sm:mb-3">
                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Data Kepegawaian</h3>
                        <p class="text-xs text-gray-500">Informasi profesional</p>
                    </div>
                </div>
                <div class="space-y-1.5 sm:space-y-2">

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Status Pegawai</label>
                        <input type="text" id="f_status_kepala_sekolah" value="{{ $guru->status_pegawai ?? '' }}"
                            placeholder="Status kepala sekolah" readonly
                            class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-500
                                  cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-400 focus:bg-white
                                  hover:border-gray-300 transition-all" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                        <select id="f_gender"
                            class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-400 focus:bg-white
                                   hover:border-gray-300 transition-all cursor-pointer">
                            <option value="" disabled {{ empty($user->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Unit Kerja</label>
                        <input type="text" id="f_unit_kerja" value="{{ $guru->unit_kerja ?? '' }}"
                            placeholder="Unit kerja"
                            class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-400 focus:bg-white
                                  hover:border-gray-300 transition-all" />
                    </div>

                </div>
            </div>

            {{-- Info Kontak --}}
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-2 sm:p-3 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2 mb-2 sm:mb-3">
                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Info Kontak</h3>
                        <p class="text-xs text-gray-500">Cara menghubungi Anda</p>
                    </div>
                </div>
                <div class="space-y-1.5 sm:space-y-2">

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Nomor Telepon</label>
                        <input type="tel" id="f_hp" value="{{ $user->no_telepon ?? '' }}"
                            placeholder="Nomor yang dapat dihubungi"
                            class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400 focus:bg-white
                                  hover:border-gray-300 transition-all" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" id="f_email" value="{{ $user->email ?? '' }}" placeholder="Email"
                            class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400 focus:bg-white
                                  hover:border-gray-300 transition-all" />
                    </div>

                    {{-- MAP PREVIEW --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Lokasi Alamat</label>

                        <div id="mapPreviewWrapper" onclick="openMapModal()"
                            class="relative w-full rounded-md border border-gray-200 overflow-hidden cursor-pointer group shadow-sm hover:shadow-md transition-all"
                            style="height: 90px;">
                            <div id="mapPreviewMap"></div>
                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent group-hover:from-black/80
                                    transition-all duration-300 px-2 py-1.5 sm:px-3 sm:py-2 flex items-center gap-2 z-10">
                                <div class="w-5 h-5 bg-white/20 backdrop-blur-sm rounded-md flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-white text-xs font-medium">Klik untuk mengatur lokasi</span>
                            </div>
                        </div>

                        <div id="addressDisplay"
                            class="mt-2 text-xs text-gray-700 bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200
                                rounded-md px-2 py-1.5 sm:px-3 sm:py-2 min-h-[30px] sm:min-h-[32px] leading-relaxed">
                            {{ $user->alamat ?? 'Belum ada alamat yang diatur' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== SIMPAN ===== --}}
        <div class="flex justify-end">
            <button onclick="simpanProfil()"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-4 py-1.5 sm:px-5 sm:py-2 rounded-md transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Perubahan
            </button>
        </div>

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
                    '" alt="Foto Kepala Sekolah" class="w-full h-full object-cover"/>';
            };
            reader.readAsDataURL(file);
        });

        function clearLocation() {
            if (confirm("Apakah Anda yakin ingin menghapus lokasi dari profil?")) {

                fetch('/kepala-sekolah/delete-location', {
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
            formData.append('status_kepala_sekolah', document.getElementById('f_status_kepala_sekolah').value);
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
            fetch('/kepala-sekolah/save-profil', {
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
