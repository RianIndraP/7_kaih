@extends('layouts.admin')



@section('title', 'Manajemen Guru Sekolah | Admin')

@section('page_title', 'Manajemen Guru Sekolah')



@section('content')

<div class="space-y-6">

    <!-- Session Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-green-800">{!! session('success') !!}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-800">{!! session('error') !!}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>

            <h2 class="text-xl font-bold text-gray-900">Daftar Guru</h2>

            <p class="text-sm text-gray-600 mt-1">Kelola data guru sekolah</p>

        </div>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('admin.guru.template') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-300 rounded-xl text-sm font-semibold text-green-700 hover:from-green-100 hover:to-emerald-100 hover:border-green-400 transition-all shadow-sm">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Template
            </a>

            <button onclick="openImportModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </button>

            <button onclick="openAddModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 border border-transparent rounded-xl text-sm font-semibold text-white hover:from-blue-700 hover:to-indigo-800 transition-all shadow-md">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>

                </svg>

                Tambah Guru

            </button>

        </div>

    </div>



    <!-- Filter Card -->

    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 shadow-md p-5">

        <div class="flex flex-wrap gap-4 items-end">

            <div class="flex-1 min-w-[280px]">

                <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Guru</label>

                <div class="relative">

                    <input type="text" id="searchInput" placeholder="Cari nama, NIP, atau NIK..."

                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">

                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>

                    </svg>

                </div>

            </div>

            <div class="flex gap-2">

                <button onclick="resetFilter()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Reset</button>

                <button onclick="applyFilter()" class="px-4 py-2 bg-gray-900 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-800 transition-colors">Terapkan</button>

            </div>

        </div>

    </div>



    <!-- Table -->

    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 shadow-md overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-blue-100/50 border-b border-blue-200">

                    <tr>

                        <th class="px-4 py-3 text-left font-semibold text-blue-800">NIP/NIK</th>

                        <th class="px-4 py-3 text-left font-semibold text-blue-800">Nama</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">JK</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">Tgl Lahir</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">Telepon</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">Status</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">Unit Kerja</th>

                        <th class="px-4 py-3 text-center font-semibold text-blue-800">Aksi</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-blue-100">

                    @forelse($guru as $g)

                    <tr class="hover:bg-blue-100/30 transition-colors">

                        <td class="px-4 py-3 font-medium text-gray-900">{{ $g->user->nip ?? $g->user->nik ?? '-' }}</td>

                        <td class="px-4 py-3 text-gray-700">{{ $g->user->name ?? '-' }}</td>

                        <td class="px-4 py-3 text-center">

                            @if($g->user->gender)

                                <span class="inline-flex px-2 py-1 {{ $g->user->gender == 'Laki-laki' ? 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800' : 'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800' }} rounded-lg text-xs font-semibold">{{ $g->user->gender }}</span>

                            @else

                                <span class="text-gray-400">-</span>

                            @endif

                        </td>

                        <td class="px-4 py-3 text-center text-gray-600">{{ $g->user->birth_date ? $g->user->birth_date->format('d/m/Y') : '-' }}</td>

                        <td class="px-4 py-3 text-center text-gray-600">{{ $g->user->no_telepon ?? '-' }}</td>

                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 rounded-lg text-xs font-semibold">{{ $g->status_pegawai ?? '-' }}</span></td>

                        <td class="px-4 py-3 text-center text-gray-600">{{ $g->unit_kerja ?? '-' }}</td>

                        <td class="px-4 py-3 text-center">

                            <div class="flex items-center justify-center gap-2">

                                <button onclick="openEditModal({{ $g->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>

                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">

                                    @csrf @method('DELETE')

                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500"><svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3v2m-6-2v2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p>Tidak ada data guru</p></td></tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($guru->hasPages())

        <div class="px-4 py-3 border-t border-blue-200 bg-blue-50/50">{{ $guru->links() }}</div>

        @endif

    </div>

</div>



<!-- Modal Tambah Guru -->

<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between p-5 border-b border-gray-200">

            <h3 class="text-lg font-semibold text-gray-900">Tambah Guru Baru</h3>

            <button onclick="closeAddModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>

        </div>

        <form action="{{ route('admin.guru.store') }}" method="POST" class="p-6">

            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label><input type="text" name="nip" placeholder="Kosongkan jika tidak ada" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">NIK</label><input type="text" name="nik" placeholder="Kosongkan jika tidak ada" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-gray-400">(opsional)</span></label><input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label><select name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"><option value="">Pilih</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label><input type="date" name="birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label><input type="text" name="no_telepon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Status Pegawai</label><input type="text" name="status_pegawai" placeholder="PNS, Honorer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1.5">Unit Kerja</label><input type="text" name="unit_kerja" placeholder="Matematika, Bahasa Indonesia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Password Default</label><div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg><span class="text-sm text-gray-600">guru</span></div><p class="text-xs text-gray-500 mt-1">Password default untuk login</p></div>

            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">

                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>

                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">Simpan</button>

            </div>

        </form>

    </div>

</div>



<!-- Modal Edit Guru -->

<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between p-5 border-b border-gray-200">

            <h3 class="text-lg font-semibold text-gray-900">Edit Guru</h3>

            <button onclick="closeEditModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>

        </div>

        <form id="editForm" method="POST" class="p-6">

            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label><input type="text" name="nip" id="editNip" placeholder="Kosongkan jika tidak ada" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">NIK</label><input type="text" name="nik" id="editNik" placeholder="Kosongkan jika tidak ada" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label><input type="text" name="no_telepon" id="editNoTelepon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label><select name="gender" id="editGender" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"><option value="">Pilih</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label><input type="date" name="birth_date" id="editBirthDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Status Pegawai</label><input type="text" name="status_pegawai" id="editStatusPegawai" placeholder="PNS, Honorer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1.5">Unit Kerja</label><input type="text" name="unit_kerja" id="editUnitKerja" placeholder="Matematika, Bahasa Indonesia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

                <div class="sm:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru (kosongkan jika tidak diubah)</label><input type="password" name="password" id="editPassword" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></div>

            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">

                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>

                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">Simpan Perubahan</button>

            </div>

        </form>

    </div>

</div>



<!-- Modal Import -->

<div id="importModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">

        <div class="flex items-center justify-between p-5 border-b border-gray-200">

            <h3 class="text-lg font-semibold text-gray-900">Import Data Guru</h3>

            <button onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>

        </div>

        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data" class="p-6">

            @csrf

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors" id="dropZone">

                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>

                <p class="text-gray-600 mb-4">Drop file Excel atau klik untuk pilih file</p>

                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" class="hidden" required>

                <button type="button" onclick="document.getElementById('fileInput').click()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Pilih File</button>

                <p id="fileName" class="mt-3 text-sm text-gray-600 font-medium"></p>

            </div>

            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs font-medium text-amber-800 mb-2">Format Excel yang dibutuhkan (baris 1-2 dilewati, baris 3 header):</p>
                <div class="grid grid-cols-2 gap-2 text-xs text-amber-700">
                    <span>Kolom A: NIP</span>
                    <span>Kolom B: NIK</span>
                    <span>Kolom C: Nama Lengkap *</span>
                    <span>Kolom D: Jenis Kelamin (Laki-laki/Perempuan)</span>
                    <span>Kolom E: Tanggal Lahir (dd/mm/yyyy)</span>
                    <span>Kolom F: Status Pegawai</span>
                    <span>Kolom G: Unit Kerja</span>
                </div>
                <p class="text-xs text-amber-600 mt-2">* Kolom wajib diisi</p>
            </div>

            <p class="text-xs text-gray-500 mt-3">Format file: .xlsx, .xls, .csv</p>

            <div class="flex justify-end gap-3 mt-6">

                <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>

                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">Import</button>

            </div>

        </form>

    </div>

</div>



<script>

function applyFilter() {

    const search = document.getElementById('searchInput').value;

    let url = new URL(window.location.href);

    if (search) url.searchParams.set('search', search);

    else url.searchParams.delete('search');

    window.location.href = url.toString();

}

function resetFilter() { window.location.href = '{{ route("admin.guru") }}'; }

function openAddModal() { document.getElementById('addModal').style.display = 'flex'; document.getElementById('addModal').classList.remove('hidden'); }

function closeAddModal() { document.getElementById('addModal').style.display = 'none'; document.getElementById('addModal').classList.add('hidden'); }

function openEditModal(id) {

    fetch(`/admin/guru/${id}/data`).then(r => r.json()).then(data => {

        document.getElementById('editForm').action = `/admin/guru/${id}`;

        document.getElementById('editNip').value = data.nip ?? '';

        document.getElementById('editNik').value = data.nik ?? '';

        document.getElementById('editName').value = data.name;

        document.getElementById('editGender').value = data.gender;

        document.getElementById('editBirthDate').value = data.birth_date ?? '';

        document.getElementById('editNoTelepon').value = data.no_telepon ?? '';

        document.getElementById('editStatusPegawai').value = data.status_pegawai ?? '';

        document.getElementById('editUnitKerja').value = data.unit_kerja ?? '';

        document.getElementById('editPassword').value = '';

        document.getElementById('editModal').style.display = 'flex';

        document.getElementById('editModal').classList.remove('hidden');

    });

}

function closeEditModal() { document.getElementById('editModal').style.display = 'none'; document.getElementById('editModal').classList.add('hidden'); }

function openImportModal() { document.getElementById('importModal').style.display = 'flex'; document.getElementById('importModal').classList.remove('hidden'); }

function closeImportModal() { document.getElementById('importModal').style.display = 'none'; document.getElementById('importModal').classList.add('hidden'); }

document.getElementById('fileInput')?.addEventListener('change', function() { if (this.files.length > 0) document.getElementById('fileName').textContent = this.files[0].name; });

window.onclick = function(event) { if (event.target.id === 'addModal') closeAddModal(); if (event.target.id === 'editModal') closeEditModal(); if (event.target.id === 'importModal') closeImportModal(); }

</script>

@endsection
