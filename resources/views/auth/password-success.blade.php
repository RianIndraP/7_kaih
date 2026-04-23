<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diperbarui - 7 KAIH</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-4">

    <!-- Login Card -->
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-blue-200/50 w-full max-w-md p-8 transform hover:scale-[1.01] transition-transform duration-300">
        
        <!-- Success Icon Section -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl ring-4 ring-green-100">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-2 bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Password Berhasil Diperbarui</h1>
        
        <p class="text-sm text-gray-600 text-center mb-8 leading-relaxed font-medium">
            Password Anda telah berhasil diperbarui. Silakan login kembali dengan password baru Anda.
        </p>
        
        <!-- Success Animation -->
        <div class="flex justify-center mb-8">
            <div class="flex space-x-2">
                <div class="w-2 h-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full animate-bounce shadow-md"></div>
                <div class="w-2 h-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full animate-bounce shadow-md" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full animate-bounce shadow-md" style="animation-delay: 0.2s"></div>
            </div>
        </div>
        
        <form class="space-y-5" method="GET" action="{{ route('login') }}">
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold py-3.5 rounded-xl hover:from-green-700 hover:to-emerald-700 transform hover:scale-[1.01] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                Masuk Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </form>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 font-medium">
                Jika Anda tidak merubah password, silakan hubungi administrator
            </p>
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
