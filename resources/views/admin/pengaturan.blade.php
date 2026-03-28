@extends('layouts.admin')

@section('title', 'Pengaturan | SMK N 5 Telkom Banda Aceh')

@section('content')

<div class="p-6">
    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.dashboard') }}"
       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali Ke Dashboard
    </a>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Kiri: Form Ganti Password --}}
        <div class="flex-1">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.pengaturan.password') }}">
                    @csrf

                    {{-- Username (read-only) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
                        <input type="text" value="{{ $user->username ?? '-' }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm">
                    </div>

                    {{-- Password Lama --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama:</label>
                        <input type="password" name="password_lama" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password_lama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru:</label>
                        <input type="password" name="password_baru" required minlength="6"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password:</label>
                        <input type="password" name="password_baru_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password_baru')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Ganti Password --}}
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                            Ganti Password
                        </button>
                    </div>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Kanan: Data Admin --}}
        <div class="w-full lg:w-80">
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-800">Data Admin</span>
                </div>

                {{-- Data --}}
                <div class="p-4 space-y-4">
                    {{-- Username --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Username :</label>
                        <input type="text" value="{{ $user->username ?? '-' }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-sm">
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama :</label>
                        <input type="text" value="{{ $user->name ?? '-' }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-sm">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir :</label>
                        <div class="relative">
                            <input type="text" value="{{ $user->birth_date?->format('d/m/Y') ?? '-' }}" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-sm pr-10">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
