@extends('layouts.admin')

@section('title', 'Sistem & Administrasi | Admin')
@section('page_title', 'Sistem & Administrasi')

@section('content')
    <div class="space-y-6">

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
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Tahun ajaran</h3>
                    <p class="text-xs text-gray-500">Periode sistem yang sedang berjalan</p>
                </div>
            </div>
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

        {{-- Pengecualian Siswa --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Pengecualian siswa</h3>
                    <p class="text-xs text-gray-500">Tandai siswa tinggal kelas, dikeluarkan, atau pindah sekolah sebelum
                        proses kenaikan kelas</p>
                </div>
            </div>

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
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Semua status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tinggal_kelas" {{ request('status') == 'tinggal_kelas' ? 'selected' : '' }}>Tinggal kelas
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
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge }}">
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
                                                class="inline" onsubmit="return confirm('Keluarkan {{ $s->name }} dari sekolah?')">
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

        {{-- Proses Kenaikan Kelas --}}
        <div class="bg-white rounded-xl border border-red-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-red-100">
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M12 19V5M5 12l7-7 7 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Proses kenaikan kelas</h3>
                    <p class="text-xs text-gray-500">Jalankan di akhir tahun ajaran — tidak bisa dibatalkan</p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 text-sm text-red-800">
                Proses ini akan memindahkan X → XI, XI → XII, dan menetapkan kelas XII sebagai alumni. Siswa bertanda
                "tinggal kelas" akan tetap di kelas yang sama. Pastikan semua pengecualian sudah ditandai sebelum
                menjalankan proses ini.
            </div>
            <form action="{{ route('admin.sistem.kenaikan-kelas') }}" method="POST"
                onsubmit="return confirm('Yakin ingin menjalankan proses kenaikan kelas? Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ketik <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-red-600">NAIK KELAS</span> untuk
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

        {{-- Pindah Siswa Antar Guru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Pindah siswa antar guru wali</h3>
                    <p class="text-xs text-gray-500">Untuk guru yang keluar atau penggantian wali kelas</p>
                </div>
            </div>
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

        {{-- Ganti Kepala Sekolah --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Kepala sekolah aktif</h3>
                    <p class="text-xs text-gray-500">Mengganti guru yang menjabat kepala sekolah</p>
                </div>
            </div>

            @if($kepalaSekolah)
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                        {{ collect(explode(' ', $kepalaSekolah->user->name ?? ''))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->join('') }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $kepalaSekolah->getNamaLengkapAttribute() }}</p>
                        <p class="text-xs text-gray-500">Kepala Sekolah aktif &nbsp;·&nbsp; NIP
                            {{ $kepalaSekolah->user->nip ?? '-' }}</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tetapkan sebagai kepala sekolah</label>
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
@endsection