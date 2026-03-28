@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Profil Siswa')

@section('content')

    {{-- Leaflet CSS — hanya sekali --}}
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

        /* Preview map: pointer-events none agar tidak bisa diklik/drag */
        #mapPreviewMap {
            width: 100%;
            height: 144px;
            pointer-events: none;
        }

        /* Full map modal */
        #mapFull {
            width: 100%;
            height: 380px;
        }

        /* Pastikan Leaflet container di dalam modal punya z-index wajar */
        #mapFull .leaflet-container,
        #mapPreviewMap .leaflet-container {
            z-index: 1 !important;
        }
    </style>

    <h2 class="text-xl font-bold text-gray-900 mb-5">Profil</h2>

    {{-- ---- PROFILE CARD ---- --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-5">
        <div class="flex flex-col sm:flex-row gap-5 items-start">

            {{-- Photo --}}
            <div class="flex flex-col items-center gap-2 flex-shrink-0">
                <div id="photoCircle"
                    class="w-24 h-24 rounded-full bg-gray-100 border-4 border-gray-200
            flex items-center justify-center overflow-hidden">

                    {{-- GANTI profile_picture menjadi foto --}}
                    @if (!empty($user->foto))
                        <img id="profilePreview" src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->name }}"
                            class="w-full h-full object-cover" />
                    @else
                        <!-- Placeholder SVG jika foto kosong -->
                        <div id="placeholderSvg" class="flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <img id="profilePreview" class="hidden w-full h-full object-cover" />
                    @endif
                </div>

                <input type="file" id="photoInput" name="foto" accept="image/*" class="hidden" />

                <button type="button" onclick="document.getElementById('photoInput').click()"
                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition-colors">
                    Ubah Foto
                </button>
            </div>

        {{-- Info rows --}}
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
            <div class="flex items-center border-b border-gray-100 py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">Nama</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <input type="text" id="p_nama" value="{{ $user->name ?? '' }}"
                       class="inline-edit flex-1" placeholder="Nama lengkap"/>
            </div>
            <div class="flex items-center border-b border-gray-100 py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">Tempat Lahir</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <input type="text" id="p_tempat_lahir" value="{{ $user->tempat_lahir ?? '' }}"
                       class="inline-edit flex-1" placeholder="Tempat lahir"/>
            </div>
            <div class="flex items-center border-b border-gray-100 py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">Tanggal Lahir</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <input type="date" id="p_tanggal_lahir"
                       value="{{ $user->birth_date?->format('Y-m-d') ?? '' }}"
                       class="inline-edit flex-1"/>
            </div>
            <div class="flex items-center border-b border-gray-100 py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">Kelas</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <select id="p_kelas" class="inline-edit flex-1 cursor-pointer">
                    @foreach (['X RPL 1','X RPL 2','XI RPL 1','XI RPL 2','XII RPL 1','XII RPL 2'] as $k)
                        <option value="{{ $k }}" {{ ($user->kelas ?? '') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center border-b border-gray-100 py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">Jenis Kelamin</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <select id="p_jk" class="inline-edit flex-1 cursor-pointer">
                    <option value="Laki-laki" {{ ($user->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ ($user->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="flex items-center py-2">
                <span class="text-xs text-gray-500 w-28 flex-shrink-0">NISN</span>
                <span class="text-xs text-gray-400 mx-2">:</span>
                <input type="text" id="p_nisn" value="{{ $user->nisn ?? '' }}"
                       class="inline-edit flex-1 bg-gray-50 cursor-not-allowed"
                       placeholder="NISN" readonly/>
            </div>
        </div>
    </div>
</div>

    {{-- ---- HOBI + KONTAK GRID ---- --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

        {{-- Hobi dan Minat --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Hobi dan Minat</h3>
            <div class="space-y-3">
                @foreach ([['id' => 'f_hobi', 'label' => 'Hobi', 'val' => $user->hobi ?? '', 'ph' => 'Hobi kamu...'], ['id' => 'f_cita', 'label' => 'Cita-cita', 'val' => $user->cita_cita ?? '', 'ph' => 'Cita-cita kamu...'], ['id' => 'f_teman', 'label' => 'Teman terbaik', 'val' => $user->teman_terbaik ?? '', 'ph' => 'Nama teman terbaik...'], ['id' => 'f_makan', 'label' => 'Makanan kesukaan', 'val' => $user->makanan_kesukaan ?? '', 'ph' => 'Makanan favorit...'], ['id' => 'f_warna', 'label' => 'Warna kesukaan', 'val' => $user->warna_kesukaan ?? '', 'ph' => 'Warna favorit...']] as $field)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">{{ $field['label'] }}</label>
                        <input type="text" id="{{ $field['id'] }}" value="{{ $field['val'] }}"
                            placeholder="{{ $field['ph'] }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  hover:border-gray-300 transition-colors" />
                    </div>
                @endforeach
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Gender</label>
                    <select id="f_gender"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                               hover:border-gray-300 transition-colors cursor-pointer">
                        <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Info Kontak dan Keluarga --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Info kontak dan keluarga</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nomor yang dapat dihubungi</label>
                    <input type="tel" id="f_hp" value="{{ $user->no_telepon ?? '' }}"
                        placeholder="Nomor HP siswa..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                              hover:border-gray-300 transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nomor orang tua</label>
                    <input type="tel" id="f_ortu" value="{{ $user->no_ortu ?? '' }}"
                        placeholder="Nomor HP orang tua..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                              hover:border-gray-300 transition-colors" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                    <input type="email" id="f_email" value="{{ $user->email ?? '' }}" placeholder="Email..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                              hover:border-gray-300 transition-colors" />
                </div>

                {{-- MAP PREVIEW --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi:</label>

                    {{-- Wrapper: klik untuk buka modal pilih lokasi --}}
                    <div id="mapPreviewWrapper" onclick="openMapModal()"
                        class="relative w-full rounded-lg border border-gray-200 overflow-hidden cursor-pointer group"
                        style="height: 144px;">

                        {{-- Container peta preview (pointer-events:none via CSS) --}}
                        <div id="mapPreviewMap"></div>

                        {{-- Overlay hint di bagian bawah --}}
                        <div
                            class="absolute inset-x-0 bottom-0 bg-black/50 group-hover:bg-black/65
                                transition-colors px-3 py-2 flex items-center gap-2 z-10">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-white text-xs font-medium">Klik Maps Untuk Ganti Lokasi</span>
                        </div>
                    </div>

                    {{-- Alamat hasil reverse geocoding --}}
                    <div id="addressDisplay"
                        class="mt-2 text-xs text-gray-600 bg-gray-50 border border-gray-100
                            rounded-lg px-3 py-2 min-h-[32px] leading-relaxed">
                        {{ $user->alamat ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ---- SAVE BUTTON ---- --}}
    <div class="flex justify-end mb-6">
        <button onclick="saveProfile()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                   text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save
        </button>
    </div>


    {{-- ========== MAP MODAL ========== --}}
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
                <button onclick="confirmLocation()"
                    class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                           px-5 py-2.5 rounded-lg transition-colors">
                    Konfirmasi Lokasi
                </button>
            </div>
        </div>
    </div>


    {{-- ========== TOAST ========== --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[99999] flex items-center gap-2 bg-green-600 text-white
            text-sm font-medium px-4 py-3 rounded-xl shadow-lg
            opacity-0 -translate-y-2 pointer-events-none transition-all duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Profil berhasil disimpan!
    </div>


    {{-- ========== LEAFLET JS — hanya sekali, di bawah ========== --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* ── State ────────────────────────────────────────────────── */
        let selLat = {{ $user->latitude ?? 5.5489 }};
        let selLng = {{ $user->longitude ?? 95.3238 }};
        let pendingLat = selLat;
        let pendingLng = selLng;

        let mapPreview = null;
        let markerPreview = null;
        let mapFull = null;
        let markerFull = null;
        let geocodeTimer = null;

        /* ── Preview map (read-only, hanya tampilan) ─────────────── */
        function initPreviewMap() {
            const container = document.getElementById('mapPreviewMap');
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

        /* ── Modal map (interaktif, pilih lokasi) ────────────────── */
        function openMapModal() {
            const modal = document.getElementById('mapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            pendingLat = selLat;
            pendingLng = selLng;
            document.getElementById('coordText').textContent =
                'Koordinat: ' + selLat.toFixed(6) + ', ' + selLng.toFixed(6);
            document.getElementById('coordAddr').textContent = '';

            setTimeout(function() {
                if (!mapFull) {
                    const container = document.getElementById('mapFull');
                    mapFull = L.map(container).setView([selLat, selLng], 15);
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
            const modal = document.getElementById('mapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        /* Tutup modal saat klik backdrop */
        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });

        /* Tutup dengan ESC */
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

        /* ── Photo upload ────────────────────────────────────────── */
        document.getElementById('photoInput').addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;

            // Cek ukuran file (5MB = 5 * 1024 * 1024 bytes)
            var maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert("Ukuran foto terlalu besar! Maksimal adalah 5MB.");
                this.value = ""; // Reset input file agar file besar tidak tersimpan di input
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                var circle = document.getElementById('photoCircle');
                circle.innerHTML = '<img src="' + e.target.result +
                    '" alt="Foto Siswa" class="w-full h-full object-cover"/>';
            };
            reader.readAsDataURL(file);
        });

        function saveProfile() {
            // 1. Gunakan FormData, bukan object biasa {}
            var formData = new FormData();

            // 2. Masukkan semua data ke FormData
            formData.append('nama', document.getElementById('p_nama').value);
            formData.append('kelas', document.getElementById('p_kelas').value);
            formData.append('nisn', document.getElementById('p_nisn').value);
            formData.append('tempat_lahir', document.getElementById('p_tempat_lahir').value);
            formData.append('tanggal_lahir', document.getElementById('p_tanggal_lahir').value);
            formData.append('jk', document.getElementById('p_jk').value);
            formData.append('hobi', document.getElementById('f_hobi').value);
            formData.append('cita', document.getElementById('f_cita').value);
            formData.append('teman', document.getElementById('f_teman').value);
            formData.append('makan', document.getElementById('f_makan').value);
            formData.append('warna', document.getElementById('f_warna').value);
            formData.append('gender', document.getElementById('f_gender').value);
            formData.append('hp', document.getElementById('f_hp').value);
            formData.append('ortu', document.getElementById('f_ortu').value);
            formData.append('email', document.getElementById('f_email').value);
            formData.append('alamat', document.getElementById('addressDisplay').innerText.trim());
            formData.append('latitude', selLat);
            formData.append('longitude', selLng);

            // 3. AMBIL FILE FOTO (PENTING!)
            var photoInput = document.getElementById('photoInput');
            if (photoInput.files[0]) {
                formData.append('foto', photoInput.files[0]);
            }

            // 4. Kirim menggunakan Fetch
            fetch('{{ route('student.profile.save') }}', {
                    method: 'POST',
                    headers: {
                        // JANGAN gunakan 'Content-Type': 'application/json' jika mengirim FormData
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData, // Langsung kirim formData (tidak perlu JSON.stringify)
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(result) {
                    if (result.success) {
                        var toast = document.getElementById('toast');
                        toast.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
                        toast.classList.add('opacity-100', 'translate-y-0');
                        setTimeout(function() {
                            toast.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
                            toast.classList.remove('opacity-100', 'translate-y-0');
                        }, 2800);
                    } else {
                        alert(result.message || 'Terjadi kesalahan');
                    }
                })
                .catch(function(err) {
                    console.error('Save error:', err);
                    alert('Terjadi kesalahan saat menyimpan profil.');
                });
        }


        /* ── Init ────────────────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            // Tunggu sebentar agar container sudah ter-render dengan ukuran yang benar
            setTimeout(initPreviewMap, 300);
        });
    </script>

@endsection
