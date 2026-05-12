@extends('layouts.admin')

@section('title', 'Manajemen Website | Admin')
@section('page_title', 'Manajemen Website')

@section('head')
    {{-- Quill.js Free Rich Text Editor --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
    <style>
        .ql-editor { min-height: 150px; font-size: 14px; }
        .ql-toolbar { border-radius: 8px 8px 0 0; }
        .ql-container { border-radius: 0 0 8px 8px; }
        .quill-editor {
            background: white;
            border-radius: 8px;
            max-width: 100%;
            overflow: hidden;
        }
        .ql-toolbar .ql-formats {
            margin-right: 8px !important;
        }
        .ql-toolbar button {
            width: 28px !important;
            height: 28px !important;
        }
        .ql-toolbar .ql-picker {
            height: 28px !important;
        }
        .ql-toolbar .ql-picker-label {
            padding: 2px 4px !important;
        }
        /* Make toolbar wrap on small screens */
        .ql-toolbar {
            display: flex;
            flex-wrap: wrap;
            padding: 4px 8px !important;
        }
        .ql-formats {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }
        /* Ensure Quill editor content styling works */
        .ql-editor h1 { font-size: 2em !important; font-weight: bold !important; }
        .ql-editor h2 { font-size: 1.5em !important; font-weight: bold !important; }
        .ql-editor h3 { font-size: 1.17em !important; font-weight: bold !important; }
        .ql-editor .ql-size-small { font-size: 0.75em !important; }
        .ql-editor .ql-size-large { font-size: 1.5em !important; }
        .ql-editor .ql-size-huge { font-size: 2em !important; }
        .ql-editor strong { font-weight: bold !important; }
        .ql-editor em { font-style: italic !important; }
        .ql-editor u { text-decoration: underline !important; }
        .ql-editor s { text-decoration: line-through !important; }
    </style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Website</h2>
            <p class="text-sm text-gray-600 mt-1">Pengaturan penguncian website dan pesan update fitur</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.manajemen-website.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Penguncian Website --}}
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl border border-red-200 shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-red-100/50 border-b border-red-200">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-red-800">Penguncian Website Masa Perbaiki</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <!-- Toggle Aktif/Nonaktif -->
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Status Penguncian</label>
                        <p class="text-xs text-gray-500 mt-0.5">Jika aktif, semua user tidak bisa login</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_locked" value="1" {{ $settings->is_locked ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        <span class="ml-3 text-sm font-medium {{ $settings->is_locked ? 'text-red-600' : 'text-gray-600' }}">
                            {{ $settings->is_locked ? '🔒 Aktif' : '🔓 Nonaktif' }}
                        </span>
                    </label>
                </div>

                <!-- Pesan Lock -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan Informasi Website</label>
                    <div id="editor-lock" class="quill-editor border border-gray-300 rounded-lg">{!! $settings->lock_message !!}</div>
                    <input type="hidden" name="lock_message" id="input-lock">
                    <p class="text-xs text-gray-500 mt-1.5">Pesan ini akan ditampilkan saat user mencoba login ketika website terkunci</p>
                </div>
            </div>
        </div>

        {{-- Pesan Update Fitur --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-blue-100/50 border-b border-blue-200">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-blue-800">Pesan Update Fitur Website</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <!-- Tanggal Expiry -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tampilkan Pesan Sampai Tanggal</label>
                    <input type="date" name="update_message_expiry_date" value="{{ $settings->update_message_expiry_date ? $settings->update_message_expiry_date->format('Y-m-d') : '' }}"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm">
                    <p class="text-xs text-gray-500 mt-1.5">Pesan akan ditampilkan sampai tanggal ini. Kosongkan jika tidak ingin menampilkan pesan.</p>
                </div>

                <div class="space-y-4 pt-2">
                    <!-- Pesan Siswa -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-sm">🎓</span>
                            </div>
                            <label class="text-sm font-semibold text-gray-700">Pesan untuk Siswa</label>
                        </div>
                        <div id="editor-siswa" class="quill-editor border border-gray-300 rounded-lg">{!! $settings->update_message_siswa !!}</div>
                        <input type="hidden" name="update_message_siswa" id="input-siswa">
                    </div>

                    <!-- Pesan Guru -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm">👨‍🏫</span>
                            </div>
                            <label class="text-sm font-semibold text-gray-700">Pesan untuk Guru</label>
                        </div>
                        <div id="editor-guru" class="quill-editor border border-gray-300 rounded-lg">{!! $settings->update_message_guru !!}</div>
                        <input type="hidden" name="update_message_guru" id="input-guru">
                    </div>

                    <!-- Pesan Kepala Sekolah -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-sm">👑</span>
                            </div>
                            <label class="text-sm font-semibold text-gray-700">Pesan untuk Kepala Sekolah</label>
                        </div>
                        <div id="editor-kepala" class="quill-editor border border-gray-300 rounded-lg">{!! $settings->update_message_kepala_sekolah !!}</div>
                        <input type="hidden" name="update_message_kepala_sekolah" id="input-kepala">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
    // Initialize Quill editors
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        ['link', 'image'],
        ['clean']
    ];

    // Initialize each editor
    const quillLock = new Quill('#editor-lock', {
        theme: 'snow',
        placeholder: 'Contoh: Website sedang dalam masa perbaikan...',
        modules: { toolbar: toolbarOptions }
    });

    const quillSiswa = new Quill('#editor-siswa', {
        theme: 'snow',
        placeholder: 'Pesan update untuk dashboard siswa...',
        modules: { toolbar: toolbarOptions }
    });

    const quillGuru = new Quill('#editor-guru', {
        theme: 'snow',
        placeholder: 'Pesan update untuk dashboard guru...',
        modules: { toolbar: toolbarOptions }
    });

    const quillKepala = new Quill('#editor-kepala', {
        theme: 'snow',
        placeholder: 'Pesan update untuk dashboard kepala sekolah...',
        modules: { toolbar: toolbarOptions }
    });

    // Sync Quill content to hidden inputs before form submit
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('input-lock').value = quillLock.root.innerHTML;
        document.getElementById('input-siswa').value = quillSiswa.root.innerHTML;
        document.getElementById('input-guru').value = quillGuru.root.innerHTML;
        document.getElementById('input-kepala').value = quillKepala.root.innerHTML;
    });
</script>
@endsection
