@extends('layouts.admin')

@section('title', 'Manajemen Siswa Sekolah | Admin')
@section('page_title', 'Manajemen Siswa Sekolah')

@section('content')
<div class="space-y-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{!! session('success') !!}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{!! session('error') !!}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Daftar Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data siswa sekolah</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.siswa.template') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-300 rounded-lg text-sm font-medium text-green-700 hover:bg-green-100 hover:border-green-400 transition-all shadow-sm">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Template
            </a>
            <button onclick="openImportModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </button>
            <button onclick="openAddModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <!-- Kelas Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                <select name="kelas" id="filterKelas" 
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Angkatan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Angkatan</label>
                <select name="angkatan" id="filterAngkatan" 
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">
                    <option value="">Semua Tahun</option>
                    @foreach($angkatanList as $a)
                        <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari</label>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                           placeholder="Nama atau NISN..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <button type="button" onclick="resetFilter()" 
                        class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Reset
                </button>
                <button type="button" onclick="applyFilter()" 
                        class="flex-1 px-4 py-2 bg-gray-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-800 transition-colors">
                    Terapkan
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">NISN</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Nama Lengkap</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">JK</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Kelas</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Guru Wali</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Angkatan</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Tanggal Lahir</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $s->nisn }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $s->name }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($s->gender)
                                <span class="inline-flex px-2 py-1 {{ $s->gender == 'Laki-laki' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }} rounded text-xs font-medium">
                                    {{ $s->gender == 'Laki-laki' ? 'L' : 'P' }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-medium">
                                {{ $s->kelas?->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">
                            {{ $s->waliKelas?->user?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $s->angkatan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $s->birth_date ? $s->birth_date->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $s->id }})" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p>Tidak ada data siswa</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($siswa->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $siswa->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Siswa Baru</h3>
            <button onclick="closeAddModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NISN <span class="text-red-500">*</span></label>
                    <input type="text" name="nisn" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Guru Wali <span class="text-red-500">*</span></label>
                    <select name="guru_wali_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Guru Wali</option>
                        @foreach($guruWaliList as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->user->name ?? 'Guru ' . $guru->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <select name="kelas_id" id="addKelasSelect" required 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openAddKelasModal('add')" 
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors" title="Tambah Kelas">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Angkatan <span class="text-red-500">*</span></label>
                    <select name="angkatan" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Tahun</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Default</label>
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-sm text-gray-600">siswa</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Password default untuk login</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Edit Siswa</h3>
            <button onclick="closeEditModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NISN <span class="text-red-500">*</span></label>
                    <input type="text" name="nisn" id="editNisn" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" id="editBirthDate" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" id="editGender" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Guru Wali <span class="text-red-500">*</span></label>
                    <select name="guru_wali_id" id="editGuruWali" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Guru Wali</option>
                        @foreach($guruWaliList as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->user->name ?? 'Guru ' . $guru->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <select name="kelas_id" id="editKelasSelect" required 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openAddKelasModal('edit')" 
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Angkatan <span class="text-red-500">*</span></label>
                    <select name="angkatan" id="editAngkatan" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Pilih Tahun</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" id="editPassword" placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div id="addKelasModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Kelas Baru</h3>
            <button onclick="closeAddKelasModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.siswa.kelas.store') }}" method="POST" class="p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kelas" required placeholder="Contoh: XII RPL 3" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <p class="text-xs text-gray-500 mt-1">Format: [Kelas] [Jurusan] [Nomor]</p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeAddKelasModal()" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel -->
<div id="importModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Import Data Siswa</h3>
            <button onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors" id="dropZone">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <p class="text-gray-600 mb-4">Drop file Excel atau klik untuk pilih file</p>
                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv,.txt" class="hidden" required>
                <button type="button" onclick="document.getElementById('fileInput').click()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Pilih File
                </button>
                <p id="fileName" class="mt-3 text-sm text-gray-600 font-medium"></p>
            </div>
            <p class="text-xs text-gray-500 mt-4">Format yang didukung: .xlsx, .xls, .csv</p>

            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs font-medium text-amber-800 mb-2">Format Excel yang dibutuhkan (baris 1-2 dilewati, baris 3 header):</p>
                <div class="grid grid-cols-2 gap-2 text-xs text-amber-700">
                    <span>Kolom A: NISN</span>
                    <span>Kolom B: Nama Siswa</span>
                    <span>Kolom C: Kelas</span>
                    <span>Kolom D: Tahun Masuk</span>
                    <span>Kolom E: Tanggal Lahir (dd/mm/yyyy)</span>
                    <span>Kolom F: Jenis Kelam  (Laki laki/Perempuan)</span>
                    <span>Kolom G: Guru Wali</span>
                </div>

                <p class="text-xs text-amber-600 mt-2">* Kolom wajib diisi</p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeImportModal()" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Filter functions
function applyFilter() {
    const kelas = document.getElementById('filterKelas').value;
    const angkatan = document.getElementById('filterAngkatan').value;
    const search = document.getElementById('searchInput').value;
    
    let url = new URL(window.location.href);
    if (kelas) url.searchParams.set('kelas', kelas);
    else url.searchParams.delete('kelas');
    if (angkatan) url.searchParams.set('angkatan', angkatan);
    else url.searchParams.delete('angkatan');
    if (search) url.searchParams.set('search', search);
    else url.searchParams.delete('search');
    
    window.location.href = url.toString();
}

function resetFilter() {
    window.location.href = '{{ route("admin.siswa") }}';
}

// Modal functions
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('addModal').classList.add('hidden');
}

function openEditModal(id) {
    fetch(`/admin/siswa/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editForm').action = `/admin/siswa/${id}`;
            document.getElementById('editNisn').value = data.nisn;
            document.getElementById('editName').value = data.name;
            document.getElementById('editKelasSelect').value = data.kelas_id;
            document.getElementById('editAngkatan').value = data.angkatan;
            document.getElementById('editBirthDate').value = data.birth_date ?? '';
            document.getElementById('editGuruWali').value = data.guru_wali_id;
            document.getElementById('editGender').value = data.gender;
            document.getElementById('editPassword').value = '';
            document.getElementById('editModal').style.display = 'flex';
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editModal').classList.add('hidden');
}

let kelasTarget = 'add';

function openAddKelasModal(target) {
    kelasTarget = target;
    document.getElementById('addKelasModal').style.display = 'flex';
    document.getElementById('addKelasModal').classList.remove('hidden');
}

function closeAddKelasModal() {
    document.getElementById('addKelasModal').style.display = 'none';
    document.getElementById('addKelasModal').classList.add('hidden');
}

function openImportModal() {
    document.getElementById('importModal').style.display = 'flex';
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
    document.getElementById('importModal').classList.add('hidden');
}

// File input
document.getElementById('fileInput')?.addEventListener('change', function() {
    if (this.files.length > 0) {
        document.getElementById('fileName').textContent = this.files[0].name;
    }
});

// Close modal on outside click
window.onclick = function(event) {
    if (event.target.id === 'addModal') closeAddModal();
    if (event.target.id === 'editModal') closeEditModal();
    if (event.target.id === 'addKelasModal') closeAddKelasModal();
    if (event.target.id === 'importModal') closeImportModal();
}
</script>
@endsection
