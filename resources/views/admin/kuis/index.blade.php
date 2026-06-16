@extends('layouts.admin')

@section('title', 'Manajemen Kuis | Admin')
@section('page_title', 'Manajemen Kuis')

@section('content')
    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Kuis</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola kuis literasi dan numerasi</p>
            </div>
            <button onclick="openAddModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kuis
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kuisList as $kuis)
                @php
                    $now = now();
                    $selesai = $kuis->waktu_mulai->copy()->addMinutes($kuis->durasi_menit);
                    $status = $now < $kuis->waktu_mulai ? ['Terjadwal', 'bg-amber-50 text-amber-700']
                        : ($now <= $selesai ? ['Aktif', 'bg-green-50 text-green-700'] : ['Selesai', 'bg-gray-100 text-gray-500']);
                    $catColor = $kuis->kategori === 'literasi' ? 'bg-teal-50 text-teal-700' : 'bg-purple-50 text-purple-700';
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-start justify-between mb-2 gap-2">
                        <div>
                            <span
                                class="inline-block text-xs font-medium px-2 py-0.5 rounded {{ $catColor }} mb-1">{{ ucfirst($kuis->kategori) }}</span>
                            <p class="font-semibold text-gray-900">{{ $kuis->judul }}</p>
                        </div>
                        <span
                            class="text-xs font-medium px-2.5 py-1 rounded-full {{ $status[1] }} whitespace-nowrap">{{ $status[0] }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-3">{{ $kuis->tema }}</p>
                    <div class="text-xs text-gray-500 border-t border-gray-100 pt-3 space-y-1.5">
                        <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            </svg> {{ basename($kuis->file_pdf) }}</p>
                        <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg> {{ $kuis->waktu_mulai->format('d M Y, H:i') }}</p>
                        <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg> Durasi {{ $kuis->durasi_menit }} menit</p>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button onclick="openEditModal({{ $kuis->id }})"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </button>
                        <form action="{{ route('admin.kuis.destroy', $kuis->id) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('Hapus kuis ini? Semua jawaban siswa juga akan terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-400">Belum ada kuis.</div>
            @endforelse
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Kuis Baru</h3>
                <button onclick="closeAddModal()" class="p-2 text-gray-400 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.kuis.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                @csrf
                @include('admin.kuis._form')
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Kuis</h3>
                <button onclick="closeEditModal()" class="p-2 text-gray-400 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf @method('POST')
                @include('admin.kuis._form', ['edit' => true])
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
            document.getElementById('addModal').classList.remove('hidden');
        }
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('addModal').classList.add('hidden');
        }
        function openEditModal(id) {
            fetch(`/admin/kuis/${id}/data`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('editForm').action = `/admin/kuis/${id}`;
                    document.getElementById('edit_judul').value = data.judul;
                    document.getElementById('edit_kategori').value = data.kategori;
                    document.getElementById('edit_tema').value = data.tema;
                    document.getElementById('edit_soal').value = data.soal;
                    document.getElementById('edit_waktu_mulai').value = data.waktu_mulai;
                    document.getElementById('edit_durasi_menit').value = data.durasi_menit;
                    document.getElementById('editModal').style.display = 'flex';
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editModal').classList.add('hidden');
        }
        window.onclick = function (e) {
            if (e.target.id === 'addModal') closeAddModal();
            if (e.target.id === 'editModal') closeEditModal();
        }

        @if($errors->any() && !old('_method'))
            openAddModal();
        @elseif($errors->any() && old('_method'))
            // Untuk edit modal, kita butuh ID kuis yang sedang diedit. 
            // Sebagai alternatif, kita tampilkan error di atas saja.
        @endif
    </script>
@endsection