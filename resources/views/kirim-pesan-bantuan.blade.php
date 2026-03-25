@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Kirim Pesan Bantuan')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 max-w-3xl">

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-5">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-xs text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('student.kirim-pesan-bantuan.store') }}" class="space-y-5">
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
                            <option value="">Kategori Pesan</option>
                            <option value="teknis"       {{ old('kategori') == 'teknis'       ? 'selected' : '' }}>Masalah Teknis</option>
                            <option value="akun"         {{ old('kategori') == 'akun'         ? 'selected' : '' }}>Masalah Akun</option>
                            <option value="kebiasaan"    {{ old('kategori') == 'kebiasaan'    ? 'selected' : '' }}>Pertanyaan Kebiasaan</option>
                            <option value="profil"       {{ old('kategori') == 'profil'       ? 'selected' : '' }}>Masalah Profil</option>
                            <option value="lainnya"      {{ old('kategori') == 'lainnya'      ? 'selected' : '' }}>Lainnya</option>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul pesan</label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi pesan</label>
                        <textarea name="isi"
                                  rows="5"
                                  placeholder="Tuliskan pesan bantuan kamu di sini..."
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
                        class="px-8 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm
                               font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

@endsection