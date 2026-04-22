<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - 7 KAIH</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-blue-200/50 w-full max-w-md p-8 transform hover:scale-[1.01] transition-transform duration-300">
        
        <!-- Logo/Icon Section -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl ring-4 ring-blue-100">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-2 bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">Lupa Password</h1>
        
        <p class="text-sm text-gray-600 text-center mb-8 leading-relaxed">
            Masukkan NISN/NIP/Username Anda untuk memverifikasi akun
        </p>
        
        <form class="space-y-5" method="POST" action="{{ route('forgot-password') }}">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    NISN / NIP / Username
                </label>
                <input
                    type="text"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    placeholder="Masukkan NISN, NIP, atau Username Anda"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30">
            </div>
            
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold py-3.5 rounded-xl hover:from-blue-700 hover:to-indigo-800 transform hover:scale-[1.01] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                Lanjutkan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </form>
        
        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke halaman login
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
