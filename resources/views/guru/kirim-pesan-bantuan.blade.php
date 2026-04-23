@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Kirim Pesan Bantuan')

@section('content')

<div class="p-6 min-h-screen">

    {{-- Tombol kembali --}}
    <div class="mb-5">
        <a href="{{ route('guru.dashboard') }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50
                  text-sm font-medium text-gray-700 px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali Ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 max-w-3xl">

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Kirim Pesan Bantuan</h2>
            <p class="text-sm text-gray-600 mt-1">Silakan isi form berikut untuk mengirim pesan bantuan ke admin.</p>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-5">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Alert error --}}
        @if (session('error'))
            <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-5">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('guru.kirim-pesan-bantuan.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Kiri --}}
                <div class="space-y-4">
                    {{-- Nama Pengirim --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim</label>
                        <input type="text"
                               name="nama_pengirim"
                               value="{{ old('nama_pengirim', auth()->user()->name ?? '') }}"
                               placeholder="Masukkan Nama Anda..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                      @error('nama_pengirim') border-red-400 @enderror"/>
                        @error('nama_pengirim')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori Pesan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pesan</label>
                        <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                       bg-white cursor-pointer
                                       @error('kategori') border-red-400 @enderror">
                            <option value="">Pilih Kategori</option>
                            <option value="teknis" {{ old('kategori') == 'teknis' ? 'selected' : '' }}>Masalah Teknis</option>
                            <option value="akun" {{ old('kategori') == 'akun' ? 'selected' : '' }}>Masalah Akun</option>
                            <option value="siswa" {{ old('kategori') == 'siswa' ? 'selected' : '' }}>Masalah Siswa</option>
                            <option value="pelaporan" {{ old('kategori') == 'pelaporan' ? 'selected' : '' }}>Masalah Pelaporan</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-4">
                    {{-- Judul Pesan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pesan</label>
                        <input type="text"
                               name="judul"
                               value="{{ old('judul') }}"
                               placeholder="Masukkan Judul Pesan..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                      @error('judul') border-red-400 @enderror"/>
                        @error('judul')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Isi Pesan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                        <textarea name="isi"
                                  rows="5"
                                  placeholder="Tuliskan pesan bantuan Anda di sini..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                         resize-none @error('isi') border-red-400 @enderror">{{ old('isi') }}</textarea>
                        @error('isi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-center pt-2">
                <button type="submit"
                        class="px-8 py-2 bg-blue-600 border border-transparent hover:bg-blue-700 text-sm
                               font-medium text-white rounded-lg transition-colors shadow-sm">
                    Kirim Pesan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
