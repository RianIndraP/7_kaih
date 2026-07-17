@extends('layouts.admin')

@section('title', 'Sistem & Administrasi | Admin')
@section('page_title', 'Sistem & Administrasi')

@section('content')
    <style>
        .dropdown-section {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .dropdown-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            cursor: pointer;
            background: white;
            transition: background-color 0.15s;
            user-select: none;
        }

        .dropdown-header:hover {
            background-color: #f9fafb;
        }

        .dropdown-content {
            display: none;
            border-top: 1px solid #e5e7eb;
        }

        .dropdown-content.open {
            display: block;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-icon {
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        .dropdown-header.active .dropdown-icon {
            transform: rotate(180deg);
        }
    </style>

    <div class="space-y-0">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800 flex gap-3">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Stat ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Siswa aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSiswaAktif) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Tinggal kelas (ditandai)</p>
                <p class="text-2xl font-bold text-amber-600">{{ $totalTinggalKelas }}</p>
            </div>
            <div class="bg-white rounded-xl border border-red-200 shadow-sm p-5">
                <p class="text-xs text-gray-500 mb-1">Dikeluarkan</p>
                <p class="text-2xl font-bold text-red-600">{{ $totalDikeluarkan }}</p>
            </div>
        </div>

        {{-- Tahun Ajaran --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('tahun-ajaran')">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Tahun ajaran</h3>
                    <p class="text-xs text-gray-500">Periode sistem yang sedang berjalan</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="tahun-ajaran" class="dropdown-content p-5">
                <form action="{{ route('admin.sistem.tahun-ajaran') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun ajaran aktif</label>
                            <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Mulai tahun ajaran baru</label>
                            <input type="date" name="mulai_tahun_baru" value="{{ $mulaiTahunBaru }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Simpan pengaturan tahun ajaran
                    </button>
                </form>
            </div>
        </div>

        {{-- Pengecualian Siswa --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('pengecualian-siswa')">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Pengecualian siswa</h3>
                    <p class="text-xs text-gray-500">Tandai siswa tinggal kelas, dikeluarkan, atau pindah sekolah</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="pengecualian-siswa" class="dropdown-content p-5">

                <form method="GET" class="flex flex-wrap gap-3 mb-4">
                    <div class="relative flex-1 min-w-[160px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                    </div>
                    <select name="kelas_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Semua kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Semua status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tinggal_kelas" {{ request('status') == 'tinggal_kelas' ? 'selected' : '' }}>Tinggal
                            kelas
                        </option>
                        <option value="dikeluarkan" {{ request('status') == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan
                        </option>
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800">Cari</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Nama</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Kelas</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-700">Status</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($siswaList as $s)
                                @php
                                    $statusBadge = match ($s->status_akademik) {
                                        'tinggal_kelas' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        'dikeluarkan' => 'bg-red-50 text-red-700 border border-red-200',
                                        'pindah_sekolah' => 'bg-gray-100 text-gray-600',
                                        default => 'bg-green-50 text-green-700',
                                    };
                                    $statusLabel = match ($s->status_akademik) {
                                        'tinggal_kelas' => 'Tinggal kelas',
                                        'dikeluarkan' => 'Dikeluarkan',
                                        'pindah_sekolah' => 'Pindah sekolah',
                                        default => 'Aktif',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $s->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $s->kelas?->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($s->status_akademik === 'aktif')
                                                <form action="{{ route('admin.sistem.tandai-pengecualian') }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                                    <input type="hidden" name="status" value="tinggal_kelas">
                                                    <button type="submit"
                                                        class="px-2.5 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-medium hover:bg-amber-100 transition-colors">
                                                        Tinggal kelas
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.sistem.tandai-pengecualian') }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Keluarkan {{ $s->name }} dari sekolah?')">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                                    <input type="hidden" name="status" value="dikeluarkan">
                                                    <button type="submit"
                                                        class="px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-medium hover:bg-red-100 transition-colors">
                                                        Keluarkan
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.sistem.batalkan-pengecualian') }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                                    <button type="submit"
                                                        class="px-2.5 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-200 transition-colors">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Tidak ada siswa ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($siswaList->hasPages())
                    <div class="mt-4">{{ $siswaList->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Proses Kenaikan Kelas --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('kenaikan-kelas')">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M12 19V5M5 12l7-7 7 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Proses kenaikan kelas</h3>
                    <p class="text-xs text-gray-500">Jalankan di akhir tahun ajaran — tidak bisa dibatalkan</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="kenaikan-kelas" class="dropdown-content p-5">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 text-sm text-red-800">
                    Proses ini akan memindahkan X → XI, XI → XII, dan menetapkan kelas XII sebagai alumni. Siswa bertanda
                    "tinggal kelas" akan tetap di kelas yang sama. Pastikan semua pengecualian sudah ditandai sebelum
                    menjalankan proses ini.
                </div>
                <form action="{{ route('admin.sistem.kenaikan-kelas') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menjalankan proses kenaikan kelas? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih kelas (opsional)</label>
                        <select name="kelas_id"
                            class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk memproses semua kelas sekaligus</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Ketik <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-red-600">NAIK KELAS</span>
                            untuk
                            konfirmasi
                        </label>
                        <input type="text" name="konfirmasi" required placeholder="NAIK KELAS"
                            class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Jalankan kenaikan kelas
                    </button>
                </form>
            </div>
        </div>

        {{-- Pindah Siswa Antar Guru --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('pindah-siswa')">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Pindah siswa antar guru wali</h3>
                    <p class="text-xs text-gray-500">Untuk guru yang keluar atau penggantian wali kelas</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="pindah-siswa" class="dropdown-content p-5">
                <form action="{{ route('admin.sistem.pindah-siswa') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Dari guru wali</label>
                            <select name="dari_guru_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">— Pilih guru asal —</option>
                                @foreach($guruList as $g)
                                    <option value="{{ $g->id }}">{{ $g->user->name ?? 'Guru ' . $g->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Ke guru wali</label>
                            <select name="ke_guru_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">— Pilih guru tujuan —</option>
                                @foreach($guruList as $g)
                                    <option value="{{ $g->id }}">{{ $g->user->name ?? 'Guru ' . $g->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors"
                        onclick="return confirm('Pindahkan semua siswa dari guru yang dipilih?')">
                        Pindah semua siswa
                    </button>
                </form>
            </div>
        </div>

        {{-- Ganti Kepala Sekolah --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('ganti-kepala-sekolah')">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Kepala sekolah aktif</h3>
                    <p class="text-xs text-gray-500">Mengganti guru yang menjabat kepala sekolah</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="ganti-kepala-sekolah" class="dropdown-content p-5">

                @if($kepalaSekolah)
                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ collect(explode(' ', $kepalaSekolah->user->name ?? ''))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->join('') }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $kepalaSekolah->getNamaLengkapAttribute() }}</p>
                            <p class="text-xs text-gray-500">Kepala Sekolah aktif &nbsp;·&nbsp; NIP
                                {{ $kepalaSekolah->user->nip ?? '-' }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4 text-sm text-amber-700">
                        Belum ada kepala sekolah yang ditetapkan.
                    </div>
                @endif

                <form action="{{ route('admin.sistem.ganti-kepala-sekolah') }}" method="POST"
                    onsubmit="return confirm('Ganti kepala sekolah aktif?')">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tetapkan sebagai kepala
                            sekolah</label>
                        <select name="guru_id" required
                            class="w-full sm:w-80 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">— Pilih guru —</option>
                            @foreach($guruList as $g)
                                <option value="{{ $g->id }}">{{ $g->user->name ?? 'Guru ' . $g->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Ganti kepala sekolah
                    </button>
                </form>
            </div>
        </div>

        {{-- Mode Pengisian Ulang Data --}}
        <div class="dropdown-section">
            <div class="dropdown-header" onclick="toggleDropdown('pengisian-data')">
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900">Mode pengisian ulang data</h3>
                    <p class="text-xs text-gray-500">Izinkan guru atau siswa mengisi/memperbaiki data profil mereka</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div id="pengisian-data" class="dropdown-content p-5">

                {{-- Status aktif saat ini --}}
                @php
                    $modeGuru = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru', '0');
                    $modeSiswa = \App\Models\PengaturanSistem::getValue('mode_isi_data_siswa', '0');
                    $deadlineGuru = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru_deadline');
                    $deadlineSiswa = \App\Models\PengaturanSistem::getValue('mode_isi_data_siswa_deadline');
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                    <div
                        class="flex items-center gap-3 px-4 py-3 rounded-xl border {{ $modeGuru ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                        <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $modeGuru ? 'bg-green-500' : 'bg-gray-300' }}">
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $modeGuru ? 'text-green-800' : 'text-gray-600' }}">Guru</p>
                            <p class="text-xs {{ $modeGuru ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $modeGuru ? 'Aktif' . ($deadlineGuru ? ' · Tutup ' . \Carbon\Carbon::parse($deadlineGuru)->translatedFormat('d M Y') : '') : 'Tidak aktif' }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 px-4 py-3 rounded-xl border {{ $modeSiswa ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                        <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $modeSiswa ? 'bg-green-500' : 'bg-gray-300' }}">
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $modeSiswa ? 'text-green-800' : 'text-gray-600' }}">Siswa</p>
                            <p class="text-xs {{ $modeSiswa ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $modeSiswa ? 'Aktif' . ($deadlineSiswa ? ' · Tutup ' . \Carbon\Carbon::parse($deadlineSiswa)->translatedFormat('d M Y') : '') : 'Tidak aktif' }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.sistem.mode-isi-data') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Guru --}}
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Guru</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <!-- <input type="hidden" name="mode_guru" value="0"> -->
                                <input type="checkbox" name="mode_guru" value="1" class="sr-only peer" {{ $modeGuru ? 'checked' : '' }}>
                                <div
                                    class="w-10 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                                </div>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Batas waktu pengisian
                                    (opsional)</label>
                                <input type="date" name="deadline_guru" value="{{ $deadlineGuru }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan agar tidak ada batas waktu</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Data yang boleh diubah
                                    guru</label>
                                <div class="space-y-1.5 mt-1">
                                    @php
                                        $fieldGuru = json_decode(\App\Models\PengaturanSistem::getValue('mode_isi_data_guru_fields', '[]'), true) ?? [];
                                    @endphp
                                    @foreach([  'nama_lengkap'   => 'Nama lengkap',
                                                'tanggal_lahir'  => 'Tanggal lahir',
                                                'nik'            => 'NIK',
                                                'tempat_lahir'   => 'Tempat lahir',
                                                'nip'            => 'NIP',
                                                'status_pegawai' => 'Status pegawai',] as $val => $lbl)
                                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                            <input type="checkbox" name="fields_guru[]" value="{{ $val }}"
                                                class="rounded border-gray-300 text-purple-600" {{ in_array($val, $fieldGuru) ? 'checked' : '' }}>
                                            {{ $lbl }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Siswa --}}
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Siswa</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <!-- <input type="hidden" name="mode_siswa" value="0"> -->
                                <input type="checkbox" name="mode_siswa" value="1" class="sr-only peer" {{ $modeSiswa ? 'checked' : '' }}>
                                <div
                                    class="w-10 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                                </div>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Batas waktu pengisian
                                    (opsional)</label>
                                <input type="date" name="deadline_siswa" value="{{ $deadlineSiswa }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan agar tidak ada batas waktu</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Data yang boleh diubah
                                    siswa</label>
                                <div class="space-y-1.5 mt-1">
                                    @php
                                        $fieldSiswa = json_decode(\App\Models\PengaturanSistem::getValue('mode_isi_data_siswa_fields', '[]'), true) ?? [];
                                    @endphp
                                    @foreach([  'name'        => 'Nama lengkap',
                                                'birth_date'  => 'Tanggal lahir',
                                                'kelas_id'    => 'Kelas',
                                                'tempat_lahir'=> 'Tempat lahir',
                                                'gender'      => 'Jenis kelamin',
                                                'nisn'        => 'NISN',] as $val => $lbl)
                                        <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer">
                                            <input type="checkbox" name="fields_siswa[]" value="{{ $val }}"
                                                class="rounded border-gray-300 text-purple-600" {{ in_array($val, $fieldSiswa) ? 'checked' : '' }}>
                                            {{ $lbl }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pindah Siswa Antar Kelas --}}
<div class="dropdown-section">
    <div class="dropdown-header" onclick="toggleDropdown('pindah-kelas')">
        <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path d="M3 7h18M3 12h18M3 17h18"/><path d="M8 3l-5 4 5 4"/><path d="M16 21l5-4-5-4"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-gray-900">Pindah siswa antar kelas</h3>
            <p class="text-xs text-gray-500">Pindahkan seluruh kelas, beberapa, atau satu siswa ke kelas lain</p>
        </div>
        <svg class="w-5 h-5 text-gray-400 dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
    <div id="pindah-kelas" class="dropdown-content p-5">

        {{-- Mode pilihan --}}
        <div class="flex gap-2 mb-5 flex-wrap">
            <button type="button" onclick="setPindahMode('kelas')"
                    id="modeBtn-kelas"
                    class="mode-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-indigo-600 text-white border-indigo-600">
                Seluruh kelas
            </button>
            <button type="button" onclick="setPindahMode('beberapa')"
                    id="modeBtn-beberapa"
                    class="mode-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-white text-gray-600 border-gray-300 hover:border-indigo-400 hover:text-indigo-600">
                Beberapa siswa
            </button>
            <button type="button" onclick="setPindahMode('satu')"
                    id="modeBtn-satu"
                    class="mode-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-white text-gray-600 border-gray-300 hover:border-indigo-400 hover:text-indigo-600">
                Satu siswa
            </button>
        </div>

        {{-- Mode: Seluruh Kelas --}}
        <div id="panel-kelas" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Dari kelas</label>
                    <select id="pk_dari_kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="loadSiswaPreview()">
                        <option value="">— Pilih kelas asal —</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ke kelas</label>
                    <select id="pk_ke_kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Pilih kelas tujuan —</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Preview siswa dari kelas --}}
            <div id="pk_preview" class="hidden">
                <p class="text-xs text-gray-500 mb-2">Siswa yang akan dipindahkan:</p>
                <div id="pk_preview_list"
                     class="border border-gray-200 rounded-lg overflow-hidden max-h-48 overflow-y-auto text-sm divide-y divide-gray-100">
                </div>
                <p id="pk_preview_count" class="text-xs text-indigo-600 font-medium mt-2"></p>
            </div>

            <button type="button" onclick="submitPindahKelas()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Pindahkan seluruh kelas
            </button>
        </div>

        {{-- Mode: Beberapa Siswa --}}
        <div id="panel-beberapa" class="space-y-4 hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Filter dari kelas (opsional)</label>
                    <select id="pb_filter_kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="loadCheckboxSiswa()">
                        <option value="">Semua kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ke kelas</label>
                    <select id="pb_ke_kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Pilih kelas tujuan —</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Pilih siswa</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="checkAll(true)"
                                class="text-xs text-indigo-600 hover:underline font-medium">Pilih semua</button>
                        <span class="text-gray-300 text-xs">|</span>
                        <button type="button" onclick="checkAll(false)"
                                class="text-xs text-gray-500 hover:underline">Batal semua</button>
                    </div>
                </div>
                <div class="relative mb-2">
                    <input type="text" id="pb_search" placeholder="Cari nama siswa..."
                           oninput="filterCheckboxSiswa()"
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <div id="pb_siswa_list"
                     class="border border-gray-200 rounded-lg overflow-hidden max-h-56 overflow-y-auto divide-y divide-gray-100">
                    <div class="px-4 py-6 text-center text-sm text-gray-400">
                        Pilih kelas untuk melihat daftar siswa
                    </div>
                </div>
                <p id="pb_selected_count" class="text-xs text-indigo-600 font-medium mt-2"></p>
            </div>

            <button type="button" onclick="submitPindahBeberapa()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Pindahkan siswa terpilih
            </button>
        </div>

        {{-- Mode: Satu Siswa --}}
        <div id="panel-satu" class="space-y-4 hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari siswa</label>
                    <div class="relative">
                        <input type="text" id="ps_search" placeholder="Ketik nama atau NISN..."
                               oninput="searchSatuSiswa()"
                               autocomplete="off"
                               class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <div id="ps_dropdown"
                             class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-20 hidden max-h-48 overflow-y-auto">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ke kelas</label>
                    <select id="ps_ke_kelas"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Pilih kelas tujuan —</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Card siswa terpilih --}}
            <div id="ps_selected_card" class="hidden bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0" id="ps_avatar"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900" id="ps_nama"></p>
                    <p class="text-xs text-gray-500" id="ps_info"></p>
                </div>
                <button type="button" onclick="clearSelectedSiswa()"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <input type="hidden" id="ps_siswa_id">

            <button type="button" onclick="submitPindahSatu()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Pindahkan siswa ini
            </button>
        </div>

    </div>
</div>

    </div>

    <script>
        function toggleDropdown(id) {
            const content = document.getElementById(id);
            const header = content.previousElementSibling;

            const isOpen = content.classList.contains('open');

            // Tutup semua dulu (opsional — hapus 3 baris ini kalau mau bisa buka banyak sekaligus)
            document.querySelectorAll('.dropdown-content').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.dropdown-header').forEach(el => el.classList.remove('active'));

            // Toggle yang diklik
            if (!isOpen) {
                content.classList.add('open');
                header.classList.add('active');
            }
        }

        // ── Pindah Antar Kelas ────────────────────────────────────────────────────

let currentMode = 'kelas';
let allSiswaData = [];
let selectedSiswaId = null;

function setPindahMode(mode) {
    currentMode = mode;
    ['kelas', 'beberapa', 'satu'].forEach(m => {
        document.getElementById('panel-' + m).classList.toggle('hidden', m !== mode);
        const btn = document.getElementById('modeBtn-' + m);
        if (m === mode) {
            btn.className = 'mode-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-indigo-600 text-white border-indigo-600';
        } else {
            btn.className = 'mode-btn px-4 py-2 rounded-lg text-sm font-medium border transition-colors bg-white text-gray-600 border-gray-300 hover:border-indigo-400 hover:text-indigo-600';
        }
    });
}

// ── Mode: Seluruh Kelas ──────────────────────────────────────────────────

function loadSiswaPreview() {
    const kelasId = document.getElementById('pk_dari_kelas').value;
    const preview = document.getElementById('pk_preview');
    const list    = document.getElementById('pk_preview_list');
    const count   = document.getElementById('pk_preview_count');

    if (!kelasId) { preview.classList.add('hidden'); return; }

    list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Memuat...</div>';
    preview.classList.remove('hidden');

    fetch(`{{ url('/admin/sistem/siswa-kelas') }}/${kelasId}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.length) {
            list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ada siswa di kelas ini</div>';
            count.textContent = '';
            return;
        }
        list.innerHTML = data.map(s => `
            <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50">
                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                    ${s.name.split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase()}
                </div>
                <span class="text-sm text-gray-800">${s.name}</span>
                <span class="ml-auto text-xs text-gray-400">${s.nisn ?? '-'}</span>
            </div>`).join('');
        count.textContent = `${data.length} siswa akan dipindahkan`;
    })
    .catch(() => {
        list.innerHTML = '<div class="px-4 py-3 text-sm text-red-400 text-center">Gagal memuat data</div>';
    });
}

function submitPindahKelas() {
    const dari = document.getElementById('pk_dari_kelas').value;
    const ke   = document.getElementById('pk_ke_kelas').value;

    if (!dari) { alert('Pilih kelas asal terlebih dahulu.'); return; }
    if (!ke)   { alert('Pilih kelas tujuan terlebih dahulu.'); return; }
    if (dari === ke) { alert('Kelas asal dan tujuan tidak boleh sama.'); return; }

    if (!confirm('Pindahkan seluruh siswa dari kelas ini ke kelas tujuan?')) return;

    submitPindahRequest({ mode: 'kelas', dari_kelas_id: dari, ke_kelas_id: ke });
}

// ── Mode: Beberapa Siswa ─────────────────────────────────────────────────

function loadCheckboxSiswa() {
    const kelasId = document.getElementById('pb_filter_kelas').value;
    const list    = document.getElementById('pb_siswa_list');
    allSiswaData  = [];

    const url = kelasId
        ? `{{ url('/admin/sistem/siswa-kelas') }}/${kelasId}`
        : '{{ route("admin.sistem.search-siswa") }}?q=&semua=1';

    list.innerHTML = '<div class="px-4 py-6 text-center text-sm text-gray-400">Memuat...</div>';

    fetch(url, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        allSiswaData = data;
        renderCheckboxList(data);
    })
    .catch(() => {
        list.innerHTML = '<div class="px-4 py-3 text-sm text-red-400 text-center">Gagal memuat</div>';
    });
}

function renderCheckboxList(data) {
    const list = document.getElementById('pb_siswa_list');
    if (!data.length) {
        list.innerHTML = '<div class="px-4 py-6 text-center text-sm text-gray-400">Tidak ada siswa</div>';
        return;
    }
    list.innerHTML = data.map(s => `
        <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer siswa-checkbox-row">
            <input type="checkbox" value="${s.id}" class="siswa-checkbox rounded border-gray-300 text-indigo-600"
                   onchange="updateSelectedCount()">
            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                ${s.name.split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase()}
            </div>
            <span class="text-sm text-gray-800 flex-1">${s.name}</span>
            <span class="text-xs text-gray-400">${s.kelas?.nama_kelas ?? '-'}</span>
        </label>`).join('');
    updateSelectedCount();
}

function filterCheckboxSiswa() {
    const q = document.getElementById('pb_search').value.toLowerCase();
    const filtered = allSiswaData.filter(s => s.name.toLowerCase().includes(q) || (s.nisn ?? '').includes(q));
    renderCheckboxList(filtered);
}

function checkAll(state) {
    document.querySelectorAll('.siswa-checkbox').forEach(cb => cb.checked = state);
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.siswa-checkbox:checked').length;
    document.getElementById('pb_selected_count').textContent =
        count > 0 ? `${count} siswa dipilih` : '';
}

function submitPindahBeberapa() {
    const ke       = document.getElementById('pb_ke_kelas').value;
    const checked  = [...document.querySelectorAll('.siswa-checkbox:checked')].map(cb => cb.value);

    if (!checked.length) { alert('Pilih minimal satu siswa.'); return; }
    if (!ke) { alert('Pilih kelas tujuan terlebih dahulu.'); return; }
    if (!confirm(`Pindahkan ${checked.length} siswa ke kelas tujuan?`)) return;

    submitPindahRequest({ mode: 'beberapa', siswa_ids: checked, ke_kelas_id: ke });
}

// ── Mode: Satu Siswa ─────────────────────────────────────────────────────

let searchTimer = null;

function searchSatuSiswa() {
    const q = document.getElementById('ps_search').value.trim();
    const dd = document.getElementById('ps_dropdown');

    clearTimeout(searchTimer);

    if (q.length < 2) { dd.classList.add('hidden'); return; }

    searchTimer = setTimeout(() => {
        fetch(`{{ route('admin.sistem.search-siswa') }}?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                dd.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Tidak ditemukan</div>';
            } else {
                dd.innerHTML = data.map(s => `
                    <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-indigo-50 cursor-pointer transition-colors"
                         onclick="selectSatuSiswa(${s.id}, '${s.name.replace(/'/g,"\\'")}', '${s.nisn ?? ''}', '${s.kelas?.nama_kelas ?? '-'}')">
                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                            ${s.name.split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase()}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">${s.name}</p>
                            <p class="text-xs text-gray-400">${s.nisn ?? '-'} · ${s.kelas?.nama_kelas ?? '-'}</p>
                        </div>
                    </div>`).join('');
            }
            dd.classList.remove('hidden');
        })
        .catch(() => dd.classList.add('hidden'));
    }, 350);
}

function selectSatuSiswa(id, nama, nisn, kelas) {
    selectedSiswaId = id;
    document.getElementById('ps_siswa_id').value = id;
    document.getElementById('ps_search').value   = nama;
    document.getElementById('ps_dropdown').classList.add('hidden');

    const initials = nama.split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase();
    document.getElementById('ps_avatar').textContent = initials;
    document.getElementById('ps_nama').textContent   = nama;
    document.getElementById('ps_info').textContent   = `NISN: ${nisn} · Kelas: ${kelas}`;
    document.getElementById('ps_selected_card').classList.remove('hidden');
}

function clearSelectedSiswa() {
    selectedSiswaId = null;
    document.getElementById('ps_siswa_id').value = '';
    document.getElementById('ps_search').value   = '';
    document.getElementById('ps_selected_card').classList.add('hidden');
}

document.addEventListener('click', e => {
    if (!e.target.closest('#panel-satu')) {
        document.getElementById('ps_dropdown')?.classList.add('hidden');
    }
});

function submitPindahSatu() {
    const siswaId = document.getElementById('ps_siswa_id').value;
    const ke      = document.getElementById('ps_ke_kelas').value;

    if (!siswaId) { alert('Pilih siswa terlebih dahulu.'); return; }
    if (!ke)      { alert('Pilih kelas tujuan terlebih dahulu.'); return; }

    const nama = document.getElementById('ps_nama').textContent;
    if (!confirm(`Pindahkan ${nama} ke kelas tujuan?`)) return;

    submitPindahRequest({ mode: 'satu', siswa_id: siswaId, ke_kelas_id: ke });
}

// ── Submit bersama ───────────────────────────────────────────────────────

function submitPindahRequest(payload) {
    fetch('{{ route("admin.sistem.pindah-kelas") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            // Reset semua field
            document.getElementById('pk_dari_kelas').value = '';
            document.getElementById('pk_ke_kelas').value   = '';
            document.getElementById('pk_preview').classList.add('hidden');
            document.getElementById('pb_ke_kelas').value   = '';
            document.getElementById('pb_siswa_list').innerHTML = '<div class="px-4 py-6 text-center text-sm text-gray-400">Pilih kelas untuk melihat daftar siswa</div>';
            document.getElementById('pb_selected_count').textContent = '';
            clearSelectedSiswa();

            // Tampilkan notif sukses dengan reload
            const el = document.createElement('div');
            el.className = 'bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800 flex gap-3 mt-4';
            el.innerHTML = `<svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> ${res.message}`;
            document.getElementById('pindah-kelas').prepend(el);
            setTimeout(() => window.location.reload(), 1800);
        } else {
            alert(res.message || 'Terjadi kesalahan.');
        }
    })
    .catch(() => alert('Gagal terhubung ke server.'));
}
    </script>
@endsection