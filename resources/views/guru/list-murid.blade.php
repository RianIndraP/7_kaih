@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Data Siswa')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.6.0/jspdf.plugin.autotable.min.js"></script>

    <style>
        #profilMap {
            height: 256px !important;
            /* sesuai class h-64 */
            width: 100%;
            display: block;
        }
    </style>
    <div class="p-6 min-h-screen">

        {{-- ===== FILTER PENCARIAN ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-5">

            {{-- Baris 1: Search + Info --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="search"
                        class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-full text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01" />
                    </svg>
                    Pilih Periode dan Bulan dari laporan yang ingin dilihat
                </div>
            </div>

            {{-- Baris 2: Periode + Filter Dinamis + Tombol --}}
            <div class="flex flex-wrap items-center gap-3">

                {{-- Dropdown Periode --}}
                <div class="relative">
                    <select id="selectPeriode"
                        class="appearance-none border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm
                               text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="">Periode</option>
                        <option value="harian">Per Hari</option>
                        <option value="mingguan">Per Minggu</option>
                        <option value="pertemuan">Per Pertemuan</option>
                        <option value="bulanan">Per Bulan</option>
                    </select>
                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Filter Dinamis: muncul sesuai periode --}}

                {{-- Harian: input tanggal --}}
                <div id="filter_harian" class="hidden">
                    <input type="date" id="input_tanggal"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                              focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                {{-- Mingguan: 1 dropdown, tahun otomatis dari JS --}}
                <div id="filter_mingguan" class="hidden relative">
                    <select id="select_minggu"
                        class="appearance-none border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm
                               text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer min-w-[220px]">
                        <option value="">-- Pilih Minggu --</option>
                    </select>
                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Per Pertemuan: 1 dropdown, tahun otomatis dari JS --}}
                <div id="filter_pertemuan" class="hidden relative">
                    <select id="select_pertemuan"
                        class="appearance-none border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm
                               text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer min-w-[240px]">
                        <option value="">-- Pilih Pertemuan --</option>
                    </select>
                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Bulanan: 1 dropdown, tahun otomatis dari JS --}}
                <div id="filter_bulanan" class="hidden relative">
                    <select id="select_bulan"
                        class="appearance-none border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm
                               text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer min-w-[180px]">
                        <option value="">-- Pilih Bulan --</option>
                    </select>
                    <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Tombol Cari & Download --}}
                <button onclick="cariData()"
                    class="px-5 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium
                           text-gray-700 rounded-lg transition-colors shadow-sm">
                    Cari
                </button>
                <button onclick="downloadPDF()"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white
                           rounded-lg transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>

        {{-- ===== TABEL MURID ===== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tabelMurid">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 w-10">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Kelas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Penyelesaian Form</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Umpan balik</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        {{-- Diisi oleh JS setelah search --}}
                        <tr id="emptyRow">
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                Pilih periode dan klik <strong>Cari</strong> untuk menampilkan data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== MODAL PROFIL SISWA ===== --}}
    <div id="modalProfil" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Profil Siswa</h3>
                <button onclick="tutupModal('modalProfil')"
                    class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Foto Profil --}}
                    <div class="md:col-span-1">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-32 h-32 rounded-full bg-gray-200 overflow-hidden mb-3 flex items-center justify-center">
                                <img id="profilFoto" src="" alt="Foto Siswa"
                                    class="w-full h-full object-cover hidden"
                                    onerror="this.classList.add('hidden'); document.getElementById('profilFotoPlaceholder').classList.remove('hidden');">
                                <div id="profilFotoPlaceholder" class="hidden">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                </div>
                            </div>
                            <h4 id="profilNama" class="text-lg font-semibold text-gray-900 text-center">-</h4>
                            <p id="profilNisn" class="text-sm text-gray-500 text-center">-</p>
                        </div>
                    </div>

                    {{-- Data Siswa --}}
                    <div class="md:col-span-2 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                <p id="profilGender" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Kelas</p>
                                <p id="profilKelas" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                <p id="profilTtl" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tahun Masuk</p>
                                <p id="profilAngkatan" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Hobi</p>
                                <p id="profilHobi" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Cita-Cita</p>
                                <p id="profilCitaCita" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Teman Terbaik</p>
                                <p id="profilTemanTerbaik" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Makanan Kesukaan</p>
                                <p id="profilMakanan" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Warna Kesukaan</p>
                                <p id="profilWarna" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No Telepon</p>
                                <p id="profilNoTelepon" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No Orang Tua</p>
                                <p id="profilNoOrtu" class="text-sm font-medium text-gray-800">-</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p id="profilAlamat" class="text-sm font-medium text-gray-800">-</p>
                        </div>
                    </div>
                </div>

                {{-- Maps --}}
                <div class="mt-6">
                    <p class="text-xs text-gray-500 mb-2">Lokasi</p>
                    <div id="profilMap" class="w-full h-64 rounded-xl border border-gray-200"></div>
                </div>
            </div>

            <div class="flex justify-end px-6 pb-5 gap-3">

                <button id="btnDapatkanRute" onclick="bukaGoogleMapsRute()"
                    class="hidden px-5 py-2 bg-blue-600 hover:bg-blue-700 text-sm font-medium
               text-white rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Dapatkan Rute
                </button>
                <button onclick="tutupModal('modalProfil')"
                    class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-sm font-medium
                           text-gray-700 rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL DETAIL MURID ===== --}}
    <div id="modalDetail" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Detail Info Murid</h3>
                <button onclick="tutupModal('modalDetail')"
                    class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Penyelesaian Form --}}
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Penyelesaian Form</h4>
                        <p class="text-xs font-medium text-gray-600 mb-3" id="detailAspekLabel">Aspek Harian</p>
                        <div class="space-y-2.5" id="detailAspekList">
                            {{-- diisi JS --}}
                        </div>
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-700 mb-1.5">Total Rata-rata</p>
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div id="detailProgressBar"
                                    class="h-4 bg-blue-600 rounded-full transition-all duration-500 flex items-center justify-end pr-2">
                                    <span id="detailProgressLabel" class="text-[10px] text-white font-semibold">0%</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 text-right mt-1" id="detailProgressText">0% Selesai</p>
                        </div>
                    </div>

                    {{-- Informasi Identitas + Umpan Balik --}}
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Informasi Identitas</h4>
                            <div class="space-y-1.5 text-sm text-gray-700">
                                <p class="text-xs text-gray-500">Nama Murid:</p>
                                <p class="font-semibold text-gray-900" id="detailNama">-</p>
                                <p id="detailKelas" class="text-sm text-gray-700">-</p>
                                <p id="detailNisn" class="text-sm text-gray-700">-</p>
                                <p id="detailTanggal" class="text-sm text-gray-700">-</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-700 mb-1.5">Umpan balik:</p>
                            <div id="detailUmpanBalik"
                                class="text-xs text-gray-600 border border-gray-200 rounded-xl p-3
                                    bg-gray-50 max-h-32 overflow-y-auto leading-relaxed">
                                -
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end px-6 pb-5">
                <button onclick="tutupModal('modalDetail')"
                    class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-sm font-medium
                           text-gray-700 rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL KIRIM PESAN ===== --}}
    <div id="modalPesan" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">

            {{-- Tab Kirim / History --}}
            <div class="flex border-b border-gray-200">
                <button id="tabKirim" onclick="switchTabPesan('kirim')"
                    class="flex-1 py-3 text-sm font-medium border-b-2 border-blue-600
                           text-blue-700 bg-blue-50 transition-colors">
                    Kirim Pesan
                </button>
                <button id="tabHistory" onclick="switchTabPesan('history')"
                    class="flex-1 py-3 text-sm font-medium border-b-2 border-transparent
                           text-gray-500 hover:text-gray-700 transition-colors">
                    History Pesan
                </button>
            </div>

            {{-- Panel Kirim --}}
            <div id="panelKirim" class="p-5">
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-3 mb-4 bg-gray-50">
                    <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7
                                                                                     a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold text-gray-800">Kirim Pesan</span>
                    <span class="ml-auto text-xs text-gray-400" id="pesanTanggal"></span>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Murid</label>
                        <div
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                bg-gray-50 flex items-center justify-between">
                            <span id="pesanNamaMurid" class="font-medium">-</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Judul Pesan</label>
                        <div
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                bg-gray-50 flex items-center justify-between">
                            <span id="pesanJudul" class="font-medium text-gray-700">-</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <input type="hidden" id="pesanSiswaId" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Isi Pesan</label>
                        <textarea id="pesanIsi" rows="6"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Tuliskan umpan balik untuk siswa ini..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="kirimPesan()"
                        class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-sm font-medium
                               text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim Pesan
                    </button>
                </div>
            </div>

            {{-- Panel History --}}
            <div id="panelHistory" class="hidden p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Nama Siswa</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Judul</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Isi Pesan</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="historyBody">
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-gray-400">
                                    Memuat History...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end mt-4">
                    <button onclick="tutupModal('modalPesan')"
                        class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-sm font-medium
                               text-gray-700 rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
    <div id="modalHapus" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-6" onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5
                                                                                     4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Hapus Umpan Balik</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Apakah kamu yakin ingin menghapus semua umpan balik untuk siswa
                        ini?</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 mb-4">
                Nama siswa: <span id="hapusNamaSiswa" class="font-semibold">-</span>
            </p>
            <div class="flex gap-3 justify-end">
                <button onclick="tutupModal('modalHapus')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300
                           rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button onclick="konfirmasiHapus()"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700
                           rounded-lg transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[99999] flex items-center gap-2 text-white text-sm font-medium
            px-4 py-3 rounded-xl shadow-lg opacity-0 -translate-y-2 pointer-events-none
            transition-all duration-300 bg-green-600">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg"></span>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        /* ── Data dari PHP ─────────────────────────────────────── */
        var semuaSiswa = @json($siswaList);
        var currentPeriode = '';
        var currentFilter = '';
        var hapusSiswaId = null;
        var hapusUmpanBalikId = null;
        var pesanSiswaData = null;

        var currentSiswaLat = null;
        var currentSiswaLng = null;

        /* ── Konstanta nama bulan ─────────────────────────────── */
        var NAMA_BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        /* ── Periode: tampilkan filter sesuai pilihan ─────────── */
        document.getElementById('selectPeriode').addEventListener('change', function() {
            currentPeriode = this.value;

            ['harian', 'mingguan', 'pertemuan', 'bulanan'].forEach(function(p) {
                document.getElementById('filter_' + p).classList.add('hidden');
            });

            if (!currentPeriode) return;
            document.getElementById('filter_' + currentPeriode).classList.remove('hidden');

            // Setiap kali periode dipilih, generate ulang opsi pakai tahun saat ini
            // Sehingga otomatis berubah ketika tahun baru — tanpa ubah kode apapun
            if (currentPeriode === 'mingguan') generateMinggu();
            if (currentPeriode === 'pertemuan') generatePertemuan();
            if (currentPeriode === 'bulanan') generateBulan();
        });

        /* ── Generate opsi minggu ─────────────────────────────────────────────────────
           Selalu pakai new Date().getFullYear() sehingga otomatis tahun berjalan.
           Contoh: buka di 2027 → tampil "Minggu 1 - 1 Januari 2027" dst.
        ────────────────────────────────────────────────────────────────────────────── */
        function generateMinggu() {
            var year = new Date().getFullYear(); // ← otomatis tahun sekarang
            var sel = document.getElementById('select_minggu');
            sel.innerHTML = '<option value="">-- Pilih Minggu --</option>';

            for (var m = 0; m < 12; m++) {
                var d = new Date(year, m, 1);
                var w = 1;
                while (d.getMonth() === m) {
                    var tgl = d.getDate() + ' ' + NAMA_BULAN[m] + ' ' + year;
                    var val = year + '-' + String(m + 1).padStart(2, '0') + '-W' + w;
                    var opt = document.createElement('option');
                    opt.value = val;
                    opt.textContent = 'Minggu ' + w + ' - ' + tgl;
                    sel.appendChild(opt);
                    d.setDate(d.getDate() + 7);
                    w++;
                }
            }
        }

        /* ── Generate opsi pertemuan (tiap 2 minggu) ──────────────────────────────────
           Selalu pakai new Date().getFullYear() sehingga otomatis tahun berjalan.
        ────────────────────────────────────────────────────────────────────────────── */
        function generatePertemuan() {
            var year = new Date().getFullYear(); // ← otomatis tahun sekarang
            var sel = document.getElementById('select_pertemuan');
            sel.innerHTML = '<option value="">-- Pilih Pertemuan --</option>';

            var d = new Date(year, 0, 1);
            var p = 1;
            while (d.getFullYear() === year) {
                var tgl = d.getDate() + ' ' + NAMA_BULAN[d.getMonth()] + ' ' + year;
                var val = year + '-P' + p;
                var opt = document.createElement('option');
                opt.value = val;
                opt.textContent = 'Pertemuan ' + p + ' - ' + tgl;
                sel.appendChild(opt);
                d.setDate(d.getDate() + 14);
                p++;
            }
        }

        /* ── Generate opsi bulan ──────────────────────────────────────────────────────
           Selalu pakai new Date().getFullYear() sehingga otomatis tahun berjalan.
           Contoh: buka di 2027 → tampil "Januari 2027", "Februari 2027", dst.
           Value format "bulan|tahun" agar controller tahu bulan & tahun yang dipilih.
        ────────────────────────────────────────────────────────────────────────────── */
        function generateBulan() {
            var year = new Date().getFullYear(); // ← otomatis tahun sekarang
            var sel = document.getElementById('select_bulan');
            sel.innerHTML = '<option value="">-- Pilih Bulan --</option>';

            NAMA_BULAN.forEach(function(nama, i) {
                var opt = document.createElement('option');
                opt.value = (i + 1) + '|' + year; // "3|2027" → bulan Maret 2027
                opt.textContent = nama + ' ' + year; // "Maret 2027"
                sel.appendChild(opt);
            });
        }

        /* ── Cari data ────────────────────────────────────────── */
        function cariData() {
            var periode = document.getElementById('selectPeriode').value;
            var filter = '';

            if (periode === 'harian') filter = document.getElementById('input_tanggal').value;
            if (periode === 'mingguan') filter = document.getElementById('select_minggu').value;
            if (periode === 'pertemuan') filter = document.getElementById('select_pertemuan').value;
            if (periode === 'bulanan') {
                var raw = document.getElementById('select_bulan').value; // "3|2026"
                if (raw) {
                    var parts = raw.split('|');
                    filter = parts[0] + '|' + (parts[1] || new Date().getFullYear());
                }
            }

            currentFilter = filter;

            var url = '/guru/list-murid/get-siswa';
            if (periode) {
                url += '?periode=' + periode + '&filter=' + encodeURIComponent(filter);
            }

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (res.success) {
                        if (res._debug) console.log('[DEBUG]', res._debug);
                        if (!res.data || res.data.length === 0) {
                            document.getElementById('tabelBody').innerHTML =
                                '<tr><td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">' +
                                'Tidak ada siswa yang ditemukan untuk periode ini</td></tr>';
                        } else {
                            renderTabel(res.data, periode, res.no_periode);
                        }
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || 'Terjadi kesalahan'), 'red');
                    }
                })
                .catch(function(err) {
                    console.error('Fetch error:', err);
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        function periodeLabel(p) {
            return {
                harian: 'tanggal',
                mingguan: 'minggu',
                pertemuan: 'pertemuan',
                bulanan: 'bulan'
            } [p] || 'filter';
        }

        /* ── Render tabel ─────────────────────────────────────── */
        function renderTabel(data, periode, noPeriode) {
            var tbody = document.getElementById('tabelBody');
            var keyword = document.getElementById('searchInput').value.toLowerCase();

            var filtered = data.filter(function(s) {
                return !keyword || s.nama.toLowerCase().includes(keyword) || s.nisn.includes(keyword);
            });

            if (!filtered.length) {
                tbody.innerHTML =
                    '<tr><td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada data siswa</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(function(s, i) {
                var persen = s.persen || 0;
                var barColor = persen >= 80 ? 'bg-green-500' : persen >= 50 ? 'bg-blue-500' : 'bg-yellow-400';

                // Umpan balik: jika no_periode, tampilkan "Silahkan Pilih Periode"
                var umpan = noPeriode ?
                    'Silahkan Pilih Periode' :
                    (s.umpan_balik ? (s.umpan_balik.substring(0, 18) + (s.umpan_balik.length > 18 ? '...' : '')) :
                        '-');

                // Aksi: jika no_periode, hanya tombol lihat profil
                var aksiHtml = noPeriode ?
                    '<button onclick="bukaProfil(' + s.id + ')" title="Lihat Profil" ' +
                    'class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>' +
                    '</svg></button>' :
                    '<div class="flex items-center justify-center gap-2">' +
                    '<button onclick="bukaProfil(' + s.id + ')" title="Lihat Profil" ' +
                    'class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>' +
                    '</svg></button>' +
                    '<button onclick="bukaDetail(' + JSON.stringify(s).replace(/"/g, '&quot;') + ',\'' + periode +
                    '\')" title="Detail" ' +
                    'class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<circle cx="12" cy="12" r="10" stroke-width="2"/>' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>' +
                    '</svg></button>' +
                    '<button onclick="bukaPesan(' + JSON.stringify(s).replace(/"/g, '&quot;') + ',\'' + periode +
                    '\')" title="Kirim Pesan" ' +
                    'class="p-1.5 text-purple-500 hover:bg-purple-50 rounded-lg transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" ' +
                    'd="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>' +
                    '</svg></button>' +
                    '<button onclick="bukaHapus(' + s.id + ',\'' + s.nama + '\')" title="Hapus Umpan Balik" ' +
                    'class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" ' +
                    'd="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>' +
                    '</svg></button>' +
                    '</div>';

                return '<tr class="border-b border-gray-100 hover:bg-gray-50">' +
                    '<td class="px-4 py-3 text-gray-600">' + (i + 1) + '</td>' +
                    '<td class="px-4 py-3 font-medium text-gray-800">' + s.nama + '</td>' +
                    '<td class="px-4 py-3 text-gray-600">' + s.kelas + '</td>' +
                    '<td class="px-4 py-3 text-gray-600">' + s.nisn + '</td>' +
                    '<td class="px-4 py-3 w-32">' +
                    '<div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">' +
                    '<div class="h-3 rounded-full ' + barColor + '" style="width:' + persen + '%"></div>' +
                    '</div>' +
                    '<span class="text-xs text-gray-500 mt-0.5 block">' + persen + '%</span>' +
                    '</td>' +
                    '<td class="px-4 py-3 text-xs ' + (noPeriode ? 'text-gray-400 italic' : 'text-gray-600') +
                    ' max-w-[140px] truncate">' + umpan + '</td>' +
                    '<td class="px-4 py-3">' + aksiHtml + '</td>' +
                    '</tr>';
            }).join('');
        }

        /* ── Search filter realtime ───────────────────────────── */
        document.getElementById('searchInput').addEventListener('input', function() {
            if (document.getElementById('tabelBody').querySelector('[colspan]')) return;
            cariData();
        });

        /* ── Detail Modal ─────────────────────────────────────── */
        function bukaDetail(siswa, periode) {
            document.getElementById('detailNama').textContent = siswa.nama;
            document.getElementById('detailKelas').textContent = 'Kelas: ' + siswa.kelas;
            document.getElementById('detailNisn').textContent = 'NISN: ' + siswa.nisn;
            document.getElementById('detailTanggal').textContent = 'Tanggal: ' + (siswa.tanggal_lahir || '-');

            var aspekLabels = {
                harian: 'Aspek Harian',
                mingguan: 'Aspek Mingguan',
                pertemuan: 'Aspek Per Pertemuan',
                bulanan: 'Aspek Bulanan',
            };
            document.getElementById('detailAspekLabel').textContent = aspekLabels[periode] || 'Aspek';

            var aspek = [{
                    label: 'Bangun Pagi',
                    icon: '☀',
                    val: siswa.detail?.bangun_pagi ?? 0
                },
                {
                    label: 'Beribadah',
                    icon: '🤲',
                    val: siswa.detail?.beribadah ?? 0
                },
                {
                    label: 'Berolahraga',
                    icon: '🏃',
                    val: siswa.detail?.berolahraga ?? 0
                },
                {
                    label: 'Makan Sehat',
                    icon: '🍽',
                    val: siswa.detail?.makan_sehat ?? 0
                },
                {
                    label: 'Gemar Belajar',
                    icon: '📖',
                    val: siswa.detail?.gemar_belajar ?? 0
                },
                {
                    label: 'Bermasyarakat',
                    icon: '👥',
                    val: siswa.detail?.bermasyarakat ?? 0
                },
                {
                    label: 'Tidur Cepat',
                    icon: '🌙',
                    val: siswa.detail?.tidur_cepat ?? 0
                },
            ];

            var cols = [aspek.slice(0, 3), aspek.slice(3, 6), [aspek[6]]];
            var html = '<div class="grid grid-cols-2 gap-2">';
            aspek.forEach(function(a, i) {
                var color = a.val >= 80 ? 'bg-blue-600' : a.val >= 50 ? 'bg-blue-400' : 'bg-gray-300';
                html += '<div>' +
                    '<p class="text-xs text-gray-600 mb-1">' + (i + 1) + '. ' + a.label + ': ' + a.icon + '</p>' +
                    '<div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">' +
                    '<div class="h-2.5 rounded-full ' + color + '" style="width:' + a.val + '%"></div>' +
                    '</div></div>';
            });
            html += '</div>';
            document.getElementById('detailAspekList').innerHTML = html;

            var persen = siswa.persen || 0;
            document.getElementById('detailProgressBar').style.width = persen + '%';
            document.getElementById('detailProgressLabel').textContent = persen + '%';
            document.getElementById('detailProgressText').textContent = persen + '% Selesai';

            document.getElementById('detailUmpanBalik').textContent = siswa.umpan_balik || '-';

            bukaModal('modalDetail');
        }

        /* ── Pesan Modal ──────────────────────────────────────── */
        var judulMap = {
            harian: 'Umpan Balik Dari Guru Wali Perhari',
            mingguan: 'Umpan Balik Dari Guru Wali Perminggu',
            pertemuan: 'Umpan Balik Dari Guru Wali Perpertemuan',
            bulanan: 'Umpan Balik Dari Guru Wali Perbulan',
        };

        function bukaPesan(siswa, periode) {
            pesanSiswaData = siswa;
            document.getElementById('pesanNamaMurid').textContent = siswa.nama;
            document.getElementById('pesanJudul').textContent = judulMap[periode] || 'Umpan Balik Dari Guru Wali';
            document.getElementById('pesanSiswaId').value = siswa.id;
            document.getElementById('pesanIsi').value = '';
            var now = new Date();
            document.getElementById('pesanTanggal').textContent =
                now.getDate() + ' ' + ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ][now.getMonth()] + ' ' + now.getFullYear();

            switchTabPesan('kirim');
            bukaModal('modalPesan');
        }

        function switchTabPesan(tab) {
            var isKirim = tab === 'kirim';
            document.getElementById('panelKirim').classList.toggle('hidden', !isKirim);
            document.getElementById('panelHistory').classList.toggle('hidden', isKirim);
            document.getElementById('tabKirim').className =
                'flex-1 py-3 text-sm font-medium border-b-2 transition-colors ' +
                (isKirim ? 'border-blue-600 text-blue-700 bg-blue-50' :
                    'border-transparent text-gray-500 hover:text-gray-700');
            document.getElementById('tabHistory').className =
                'flex-1 py-3 text-sm font-medium border-b-2 transition-colors ' +
                (!isKirim ? 'border-blue-600 text-blue-700 bg-blue-50' :
                    'border-transparent text-gray-500 hover:text-gray-700');

            if (!isKirim && pesanSiswaData) loadHistory(pesanSiswaData.id);
        }

        function loadHistory(siswaId) {
            document.getElementById('historyBody').innerHTML =
                '<tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Memuat...</td></tr>';

            fetch('/guru/list-murid/pesan-history/' + siswaId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (!res.data || !res.data.length) {
                        document.getElementById('historyBody').innerHTML =
                            '<tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">Belum ada history pesan</td></tr>';
                        return;
                    }
                    document.getElementById('historyBody').innerHTML = res.data.map(function(p) {
                        return '<tr class="border-b border-gray-100">' +
                            '<td class="px-3 py-2 font-medium text-gray-800">' + p.nama_siswa + '</td>' +
                            '<td class="px-3 py-2 text-gray-700">' + p.judul + '</td>' +
                            '<td class="px-3 py-2 text-gray-600 max-w-[160px] truncate">' + p.isi + '</td>' +
                            '<td class="px-3 py-2 text-gray-500 whitespace-nowrap">' + p.tanggal + '</td>' +
                            '</tr>';
                    }).join('');
                })
                .catch(function() {
                    document.getElementById('historyBody').innerHTML =
                        '<tr><td colspan="4" class="px-3 py-4 text-center text-red-400">Gagal memuat history</td></tr>';
                });
        }

        function kirimPesan() {
            var siswaId = document.getElementById('pesanSiswaId').value;
            var judul = document.getElementById('pesanJudul').textContent;
            var isi = document.getElementById('pesanIsi').value.trim();

            if (!isi) {
                tampilkanToast('Isi pesan tidak boleh kosong', 'red');
                return;
            }

            fetch('/guru/list-murid/kirim-pesan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        siswa_id: siswaId,
                        judul: judul,
                        isi: isi,
                        periode: currentPeriode,
                        filter: currentFilter
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (res.success) {
                        tampilkanToast('Pesan berhasil dikirim!', 'green');
                        document.getElementById('pesanIsi').value = '';
                        tutupModal('modalPesan');
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || 'Terjadi kesalahan'), 'red');
                    }
                })
                .catch(function() {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        /* ── Hapus umpan balik ────────────────────────────────── */
        function bukaHapus(id, nama, umpanId) {
            hapusSiswaId = id;
            hapusUmpanBalikId = umpanId || null;
            document.getElementById('hapusNamaSiswa').textContent = nama;
            bukaModal('modalHapus');
        }

        function konfirmasiHapus() {
            if (!hapusSiswaId) return;

            // Kirim periode + filter agar controller bisa hapus berdasarkan periode aktif
            // jika umpan_balik_id tidak tersedia
            var payload = {
                siswa_id: hapusSiswaId,
                periode: currentPeriode,
                filter: currentFilter,
            };
            // Jika ada id spesifik, sertakan juga
            if (hapusUmpanBalikId) {
                payload.umpan_balik_id = hapusUmpanBalikId;
            }

            fetch('/guru/list-murid/hapus-umpan-balik', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    tutupModal('modalHapus');
                    if (res.success) {
                        tampilkanToast(res.message || 'Umpan balik berhasil dihapus', 'green');
                        cariData();
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || ''), 'red');
                    }
                })
                .catch(function() {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        /* ── Download PDF ─────────────────────────────────────── */
        function downloadPDF() {
            var rows = document.querySelectorAll('#tabelBody tr:not([id])');
            if (!rows.length) {
                tampilkanToast('Tidak ada data untuk didownload', 'red');
                return;
            }

            var {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();

            doc.setFontSize(14);
            doc.text('List Murid Guru Wali', 14, 15);
            doc.setFontSize(9);
            doc.text('Periode: ' + (document.getElementById('selectPeriode').value || '-') +
                '  Filter: ' + currentFilter, 14, 22);

            var tableData = [];
            rows.forEach(function(row, i) {
                var cells = row.querySelectorAll('td');
                var persen = row.querySelector('.h-3.rounded-full + span');
                tableData.push([
                    cells[0]?.textContent.trim() || '',
                    cells[1]?.textContent.trim() || '',
                    cells[2]?.textContent.trim() || '',
                    cells[3]?.textContent.trim() || '',
                    (persen?.textContent.trim() || '0%'),
                    cells[5]?.textContent.trim() || '-',
                ]);
            });

            doc.autoTable({
                startY: 28,
                head: [
                    ['No', 'Nama', 'Kelas', 'NISN', 'Penyelesaian Form', 'Umpan Balik']
                ],
                body: tableData,
                styles: {
                    fontSize: 8,
                    cellPadding: 3
                },
                headStyles: {
                    fillColor: [37, 99, 235]
                },
            });

            doc.save('list-murid-' + (currentFilter || 'semua') + '.pdf');
        }

        /* ── Modal helpers ────────────────────────────────────── */
        function bukaModal(id) {
            var el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function tutupModal(id) {
            var el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
        document.querySelectorAll('#modalDetail,#modalPesan,#modalHapus,#modalProfil').forEach(function(m) {
            m.addEventListener('click', function(e) {
                if (e.target === this) tutupModal(this.id);
            });
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape')['modalDetail', 'modalPesan', 'modalHapus', 'modalProfil'].forEach(tutupModal);
        });

        /* ── Profil Siswa Popup ───────────────────────────────── */
        var mapInstance = null;
        var mapMarker = null;

        function bukaProfil(siswaId) {
            // Fetch student profile data
            fetch('/guru/list-murid/siswa-profile/' + siswaId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (!res.success) {
                        tampilkanToast(res.message || 'Gagal memuat profil siswa', 'red');
                        return;
                    }

                    var data = res.data;

                    // Fill profile data
                    document.getElementById('profilNama').textContent = data.nama;
                    document.getElementById('profilNisn').textContent = 'NISN: ' + data.nisn;
                    document.getElementById('profilGender').textContent = data.gender;
                    document.getElementById('profilKelas').textContent = data.kelas;
                    document.getElementById('profilTtl').textContent = data.tempat_lahir + ', ' + data.tanggal_lahir;
                    document.getElementById('profilAngkatan').textContent = data.angkatan;
                    document.getElementById('profilHobi').textContent = data.hobi;
                    document.getElementById('profilCitaCita').textContent = data.cita_cita;
                    document.getElementById('profilTemanTerbaik').textContent = data.teman_terbaik;
                    document.getElementById('profilMakanan').textContent = data.makanan_kesukaan;
                    document.getElementById('profilWarna').textContent = data.warna_kesukaan;
                    document.getElementById('profilNoTelepon').textContent = data.no_telepon;
                    document.getElementById('profilNoOrtu').textContent = data.no_ortu;
                    document.getElementById('profilAlamat').textContent = data.alamat;

                    // Set foto
                    var fotoEl = document.getElementById('profilFoto');
                    var placeholderEl = document.getElementById('profilFotoPlaceholder');
                    if (data.foto) {
                        fotoEl.src = '/storage/' + data.foto;
                        fotoEl.classList.remove('hidden');
                        placeholderEl.classList.add('hidden');
                    } else {
                        fotoEl.classList.add('hidden');
                        placeholderEl.classList.remove('hidden');
                    }

                    // Simpan koordinat ke variabel global
                    currentSiswaLat = data.latitude;
                    currentSiswaLng = data.longitude;

                    // Tampilkan tombol rute jika koordinat tersedia
                    var btnRute = document.getElementById('btnDapatkanRute');
                    if (currentSiswaLat && currentSiswaLng && currentSiswaLat !== '0') {
                        btnRute.classList.remove('hidden');
                    } else {
                        btnRute.classList.add('hidden');
                    }

                    // Open modal
                    bukaModal('modalProfil');

                    // Initialize map after modal is visible
                    setTimeout(function() {
                        initMap(data.latitude, data.longitude);
                        if (typeof mapInstance !== 'undefined' && mapInstance !== null) {
                            mapInstance.invalidateSize();
                        }
                    }, 400);


                })
                .catch(function(err) {
                    console.error('Error fetching profile:', err);
                    tampilkanToast('Gagal memuat profil siswa', 'red');
                });
        }

        function bukaGoogleMapsRute() {
            // Pastikan variabel global sudah terisi angka koordinat
            if (currentSiswaLat && currentSiswaLng && currentSiswaLat !== '0') {

                // FORMAT URL YANG BENAR:
                // 'dir' untuk rute (direction), 'api=1' untuk mode modern, 'destination' untuk tujuan
                var url = "https://www.google.com/maps/search/?api=1&query=" + currentSiswaLat + "," + currentSiswaLng;

                // Membuka tab baru
                window.open(url, '_blank');

            } else {
                alert("Koordinat lokasi tidak ditemukan atau tidak valid.");
            }
        }

        function initMap(latitude, longitude) {
            var mapEl = document.getElementById('profilMap');

            mapEl.innerHTML = '';

            // Clear previous map instance if exists
            if (mapInstance) {
                mapInstance.remove();
                mapInstance = null;
            }

            var hasLocation = (latitude && longitude && latitude !== '0');
            // Default coordinates (Indonesia center) if no location data
            var lat = latitude ? parseFloat(latitude) : 5.5536;
            var lng = longitude ? parseFloat(longitude) : 95.3177;

            // Initialize Leaflet map
            mapInstance = L.map('profilMap').setView([lat, lng], hasLocation ? 15 : 15);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapInstance);

            // Add marker if location exists
            if (hasLocation) {
                L.marker([lat, lng]).addTo(mapInstance).bindPopup('Lokasi Rumah Siswa').openPopup();
            } else {
                L.popup()
                    .setLatLng([lat, lng])
                    .setContent('<p class="text-gray-500 text-xs">Lokasi rumah belum diatur oleh siswa.</p>')
                    .openOn(mapInstance);
            }
            // SOLUSI UTAMA: Paksa Leaflet menghitung ulang ukuran setelah modal terbuka
            setTimeout(function() {
                if (mapInstance) {
                    mapInstance.invalidateSize();
                }
            }, 400); // Jeda 400ms memastikan animasi modal selesai
        }

        /* ── Toast ────────────────────────────────────────────── */
        function tampilkanToast(pesan, warna) {
            var toast = document.getElementById('toast');
            toast.classList.remove('bg-green-600', 'bg-red-600');
            toast.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
            document.getElementById('toastMsg').textContent = pesan;
            toast.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(function() {
                toast.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 3000);
        }

        /* ── Load all students on page load ───────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            // Load all students without period filter
            cariData();
        });
    </script>

@endsection
