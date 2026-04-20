@extends('layouts.admin')

@section('title', 'Manajemen Kelas | Admin')
@section('page_title', 'Manajemen Kelas')

@section('content')
    <div class="space-y-6">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex gap-3">
                <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex gap-3">
                <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex gap-3">
                <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm text-red-800 font-medium">Gagal menyimpan data:</p>
                    <ul class="list-disc list-inside text-xs text-red-700 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Kelas</h2>
                <p class="text-sm text-gray-500 mt-1">Total {{ $kelasList->count() }} kelas aktif</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" id="searchKelas" placeholder="Cari kelas..."
                        class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-52">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button onclick="openAddModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kelas
                </button>
            </div>
        </div>

        {{-- Grid Kelas --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="kelasGrid">
            @forelse($kelasList as $kelas)
                @php
                    $colors = [
                        0 => ['from' => '#3b82f6', 'to' => '#1d4ed8'],
                        1 => ['from' => '#8b5cf6', 'to' => '#6d28d9'],
                        2 => ['from' => '#ec4899', 'to' => '#be185d'],
                        3 => ['from' => '#f97316', 'to' => '#c2410c'],
                        4 => ['from' => '#10b981', 'to' => '#047857'],
                        5 => ['from' => '#06b6d4', 'to' => '#0e7490'],
                        6 => ['from' => '#f43f5e', 'to' => '#be123c'],
                        7 => ['from' => '#84cc16', 'to' => '#4d7c0f'],
                        8 => ['from' => '#a78bfa', 'to' => '#7c3aed'],
                        9 => ['from' => '#fb923c', 'to' => '#c2410c'],
                        10 => ['from' => '#34d399', 'to' => '#059669'],
                        11 => ['from' => '#38bdf8', 'to' => '#0284c7'],
                    ];
                    $c = $colors[($kelas->color_index ?? 0) % count($colors)];
                @endphp
                <div class="kelas-card rounded-2xl overflow-hidden border border-black/5 hover:-translate-y-1 hover:shadow-lg transition-all duration-200"
                    data-nama="{{ strtolower($kelas->nama_kelas) }}">
                    {{-- Top colored section --}}
                    <div class="p-5 flex flex-col items-center gap-3 text-white"
                        style="background: linear-gradient(135deg, {{ $c['from'] }}, {{ $c['to'] }})">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">

                            @php
                                $n = strtoupper($kelas->nama_kelas);
                            @endphp

                            {{-- Jurusan Perangkat Lunak (RPL untuk kls 11-12, PPLG untuk kls 10) --}}
                            @if (str_contains($n, 'PPLG') || str_contains($n, 'RPL'))
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <polyline points="16,18 22,12 16,6" />
                                    <polyline points="8,6 2,12 8,18" />
                                </svg>

                                {{-- Jurusan Jaringan & Telko (TKJ/TJA untuk kls 11-12, TJKT untuk kls 10) --}}
                            @elseif(str_contains($n, 'TKJ') || str_contains($n, 'TJA') || str_contains($n, 'TJKT'))
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>

                                {{-- Jurusan Perfilman (PF untuk kls 10-12) --}}
                            @elseif(str_contains($n, 'PF'))
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <polygon points="23,7 16,12 23,17" />
                                    <rect x="1" y="5" width="15" height="14" rx="2" />
                                </svg>

                                {{-- Default untuk nama kelas lainnya --}}
                            @else
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            @endif
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-base leading-tight">{{ $kelas->nama_kelas }}</p>
                            <span class="text-xs text-white/70 bg-white/20 rounded-full px-2.5 py-0.5 mt-1.5 inline-block">
                                {{ $kelas->siswa_count ?? 0 }} siswa
                            </span>
                        </div>
                    </div>
                    {{-- Bottom actions --}}
                    <div class="bg-white px-3 py-2.5 flex items-center justify-between border-t border-black/5">

                        {{-- Tombol Lihat (Sekarang di kiri dengan ikon mata) --}}
                        {{-- <a href="{{ route('admin.kelas.show', $kelas->id) }}" --}}
                        <a href="#"
                            class="flex items-center gap-1 px-2 py-1.5 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg text-xs font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Lihat</span>
                        </a>

                        {{-- Grup Tombol Kanan (Edit & Hapus) --}}
                        <div class="flex items-center gap-2">
                            <button
                                onclick="openEditModal({{ $kelas->id }}, '{{ $kelas->nama_kelas }}', {{ $kelas->color_index ?? 0 }})"
                                class="flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit
                            </button>

                            <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus kelas {{ $kelas->nama_kelas }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm">Belum ada kelas</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal Tambah Kelas --}}
    <div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Tambah Kelas Baru</h3>
                <button onclick="closeAddModal()"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kelas <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" required placeholder="Contoh: X PPLG 1"
                        class="w-full px-3 py-2 border @error('nama_kelas') border-red-500 @else border-gray-300 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('nama_kelas')
                        <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>



    @php
        $colorList = [
            '#3b82f6',
            '#8b5cf6',
            '#ec4899',
            '#f97316',
            '#10b981',
            '#06b6d4',
            '#f43f5e',
            '#84cc16',
            '#a78bfa',
            '#fb923c',
            '#34d399',
            '#38bdf8',
        ];
    @endphp

    {{-- Modal Edit Kelas --}}
    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Edit Kelas</h3>
                <button onclick="closeEditModal()"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kelas <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_kelas" id="editNamaKelas" required
                        class="w-full px-3 py-2 border @error('nama_kelas') border-red-500 @else border-gray-300 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('nama_kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Warna Kartu</label>
                    <div class="grid grid-cols-6 gap-2" id="editColorGrid">
                        @foreach ($colorList as $ci => $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color_index" value="{{ $ci }}"
                                    class="sr-only color-radio-edit">
                                <div class="w-9 h-9 rounded-lg ring-2 ring-transparent ring-offset-2 transition-all color-swatch-edit"
                                    style="background:{{ $color }}" data-idx="{{ $ci }}"></div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 1. Fungsi Modal Tambah (Definisikan Dulu)
        function openAddModal() {
            const modal = document.getElementById('addModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAddModal() {
            const modal = document.getElementById('addModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // 2. Fungsi Modal Edit
        function openEditModal(id, nama, colorIdx) {
            document.getElementById('editForm').action = `/admin/kelas/${id}`;
            document.getElementById('editNamaKelas').value = nama;
            document.querySelectorAll('#editColorGrid .color-swatch-edit').forEach(s => s.style.outline = 'none');

            const target = document.querySelector(`#editColorGrid .color-swatch-edit[data-idx="${colorIdx}"]`);
            if (target) {
                target.style.outline = '3px solid #1d4ed8';
                target.style.outlineOffset = '2px';
            }

            const radio = document.querySelector(`#editColorGrid input[value="${colorIdx}"]`);
            if (radio) radio.checked = true;

            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // 3. Fitur Pencarian
        document.getElementById('searchKelas').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#kelasGrid .kelas-card').forEach(card => {
                const nama = card.dataset.nama || '';
                card.style.display = nama.includes(q) ? '' : 'none';
            });
        });

        // 4. Logika Pilih Warna Modal Edit
        document.querySelectorAll('#editColorGrid .color-swatch-edit').forEach(s => {
            s.addEventListener('click', () => {
                document.querySelectorAll('#editColorGrid .color-swatch-edit').forEach(x => x.style
                    .outline = 'none');
                s.style.outline = '3px solid #1d4ed8';
                s.style.outlineOffset = '2px';
                document.querySelector(`#editColorGrid input[value="${s.dataset.idx}"]`).checked = true;
            });
        });

        // 5. Tutup Modal via Backdrop
        window.addEventListener('click', e => {
            if (e.target.id === 'addModal') closeAddModal();
            if (e.target.id === 'editModal') closeEditModal();
        });

        // 6. TARUH DI PALING BAWAH: Trigger jika ada error
        @if ($errors->any())
            // Karena fungsi openAddModal sudah dibuat di atas, memanggilnya di sini aman.
            openAddModal();
        @endif
    </script>
@endsection
