<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Data - 7 KAIH</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-100 w-full max-w-md p-8 transform hover:scale-[1.02] transition-transform duration-300">
        
        <!-- Logo/Icon Section -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Verifikasi Data</h1>
        
        <!-- User Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Nama:</span>
                <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
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
                <span class="text-sm text-gray-600">{{ $identifierLabel }}:</span>
                <span class="text-sm font-medium text-gray-800">{{ $identifier }}</span>
            </div>
        </div>
        
        <p class="text-sm text-gray-600 text-center mb-6 leading-relaxed">
            Jawab pertanyaan berikut untuk verifikasi akun Anda
        </p>
        
        <form class="space-y-5" method="POST" action="{{ route('verify-data') }}">
            @csrf
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Tanggal Lahir
                </label>
                <input
                    type="text"
                    name="birth_date"
                    value="{{ old('birth_date') }}"
                    placeholder="Bulan/Tanggal/Tahun"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                <p class="text-xs text-gray-500">Format: Bulan/Tanggal/Tahun</p>
            </div>
            
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                Verifikasi
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </form>
        
        <!-- Back -->
        <div class="mt-6 text-center">
            <a href="{{ route('forgot-password') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline transition-colors flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                © 2024 SMK Negeri 5 Telkom Banda Aceh<br>
                Sistem Pelaporan 7 KAIH
            </p>
        </div>
        
    </div>

</body>
</html>
