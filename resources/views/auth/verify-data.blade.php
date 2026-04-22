<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Data - 7 KAIH</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-blue-200/50 w-full max-w-md p-8 transform hover:scale-[1.01] transition-transform duration-300">
        
        <!-- Logo/Icon Section -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl ring-4 ring-blue-100">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6 bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">Verifikasi Data</h1>
        
        <!-- User Info -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 space-y-2 border border-blue-200">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-600">Nama:</span>
                <span class="text-sm font-bold text-gray-800">{{ $user->name }}</span>
            </div>
            @php
                $identifier = '';
                $identifierLabel = '';
                if (!empty($user->nisn)) {
                    $identifier = $user->nisn;
                    $identifierLabel = 'NISN';
                } elseif (!empty($user->nip)) {
                    $identifier = $user->nip;
                    $identifierLabel = 'NIP';
                } elseif (!empty($user->nik)) {
                    $identifier = $user->nik;
                    $identifierLabel = 'NIK';
                } elseif (!empty($user->username)) {
                    $identifier = $user->username;
                    $identifierLabel = 'Username';
                } else {
                    $identifier = $user->email ?? '-';
                    $identifierLabel = 'Email';
                }
            @endphp
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-600">{{ $identifierLabel }}:</span>
                <span class="text-sm font-bold text-gray-800">{{ $identifier }}</span>
            </div>
        </div>
        
        <p class="text-sm text-gray-600 text-center mb-6 leading-relaxed font-medium">
            Jawab Pertanyaan Berikut Untuk Verifikasi Akun Anda
        </p>
        
        <form class="space-y-5" method="POST" action="{{ route('verify-data') }}">
            @csrf
            @if ($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
            
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Tanggal Lahir
                </label>
                <input
                    type="text"
                    name="birth_date"
                    value="{{ old('birth_date') }}"
                    placeholder="Bulan/Tanggal/Tahun"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30">
                <p class="text-xs text-gray-500 font-medium">Format: Bulan/Tanggal/Tahun</p>
            </div>
            
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-800 transform hover:scale-[1.01] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                Verifikasi
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </form>
        
        <!-- Back -->
        <div class="mt-6 text-center">
            <a href="{{ route('forgot-password') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-800 hover:underline transition-colors flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-blue-200 text-center">
            <p class="text-xs text-gray-500 font-medium">
                © 2026 SMK Negeri 5 Telkom Banda Aceh<br>
                Sistem Pelaporan 7 KAIH
            </p>
        </div>
        
    </div>

</body>
</html>
