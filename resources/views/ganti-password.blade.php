@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Ganti Password')

@section('content')

<div class="p-6 min-h-screen">

    {{-- Tombol kembali --}}
    <div class="mb-5">
        <a href="{{ route('student.dashboard') }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50
                  text-sm font-medium text-gray-700 px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali Ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ---- FORM GANTI PASSWORD ---- --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-5">Ganti Password</h3>

            {{-- Alert error --}}
            @if (session('error'))
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <p class="text-xs text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-4">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-xs text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('student.ganti-password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- NISN (read-only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NISN:</label>
                    <input type="text"
                           value="{{ auth()->user()->nisn ?? '' }}"
                           readonly
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600
                                  bg-gray-50 cursor-not-allowed focus:outline-none"/>
                </div>

                {{-- Password Lama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama:</label>
                    <input type="password"
                           name="password_lama"
                           placeholder="Masukkan password lama..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  @error('password_lama') border-red-400 @enderror"/>
                    @error('password_lama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru:</label>
                    <input type="password"
                           name="password_baru"
                           placeholder="Masukkan password baru..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                  @error('password_baru') border-red-400 @enderror"/>
                    @error('password_baru')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password:</label>
                    <input type="password"
                           name="password_baru_confirmation"
                           placeholder="Ulangi password baru..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400"/>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm
                                   font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

        {{-- ---- DATA SISWA (read-only) ---- --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-5">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-800">Data Siswa</h3>
            </div>

            <div class="space-y-4">
                @php $user = auth()->user(); @endphp

                @foreach ([
                    ['label' => 'Nisn :', 'value' => $user->nisn ?? '-'],
                    ['label' => 'Nama :', 'value' => $user->name ?? '-'],
                    ['label' => 'Kelas :', 'value' => $user->kelas ?? '-'],
                ] as $row)
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">{{ $row['label'] }}</label>
                        <div class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                                    bg-gray-50">
                            {{ $row['value'] }}
                        </div>
                    </div>
                @endforeach

                {{-- Tanggal Lahir dengan ikon kalender --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir :</label>
                    <div class="flex items-center gap-2 w-full border border-gray-300 rounded-lg px-3 py-2
                                bg-gray-50">
                        <span class="text-sm text-gray-700 flex-1">
                            {{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5
                                     a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                {{-- Nama Guru Wali --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama Guru Wali :</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                                bg-gray-50">
                        {{ $user->guruWali->name ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection