<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Sedang Maintenance - 7 Kebiasaan Anak Indonesia Hebat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .7; }
        }
        /* Rich text content styling - RED THEME */
        .rich-content {
            font-size: 14px;
            line-height: 1.6;
            color: #fff;
        }
        /* Remove all white backgrounds */
        .rich-content * {
            background: transparent !important;
            color: inherit !important;
        }
        .rich-content h1, .rich-content h2, .rich-content h3, 
        .rich-content h4, .rich-content h5, .rich-content h6 {
            font-weight: 700;
            margin: 12px 0 8px 0;
            color: #fecaca !important;
        }
        .rich-content h1 { font-size: 1.4rem; }
        .rich-content h2 { font-size: 1.2rem; }
        .rich-content h3 { font-size: 1.1rem; }
        .rich-content p { 
            margin-bottom: 10px; 
        }
        .rich-content ul, .rich-content ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .rich-content ul { 
            list-style-type: disc !important; 
        }
        .rich-content ol { 
            list-style-type: decimal !important; 
        }
        .rich-content li { 
            margin-bottom: 4px; 
        }
        .rich-content strong { 
            font-weight: 700; 
            color: #fecaca !important;
        }
        .rich-content em { font-style: italic; }
        .rich-content u { text-decoration: underline; }
        .rich-content s { text-decoration: line-through; }
        .rich-content a { 
            color: #fcd34d !important; 
            text-decoration: underline;
            font-weight: 500;
        }
        .rich-content blockquote {
            border-left: 3px solid #ef4444;
            margin: 10px 0;
            font-style: italic;
            background: rgba(239, 68, 68, 0.1) !important;
            padding: 10px 14px;
            border-radius: 0 6px 6px 0;
        }
        .rich-content img { max-width: 100%; border-radius: 6px; margin: 8px 0; }
        
        /* Responsive design */
        @media (max-width: 640px) {
            body { padding: 1rem !important; }
            .max-w-md { max-width: 100% !important; }
            .rich-content { font-size: 13px; line-height: 1.5; }
            .rich-content h1 { font-size: 1.2rem; }
            .rich-content h2 { font-size: 1.1rem; }
            .rich-content h3 { font-size: 1rem; }
            .p-6 { padding: 1rem !important; }
            .p-5 { padding: 0.875rem !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-red-900 via-red-800 to-orange-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">SMK Negeri 5</h1>
            <p class="text-white/60 text-sm">Telkom Banda Aceh</p>
        </div>

        <!-- Maintenance Card -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-6 md:p-8 shadow-2xl">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-red-500/30 to-orange-500/30 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse-slow border border-red-400/40">
                    <svg class="w-10 h-10 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">INFORMASI WEBSITE</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-transparent via-red-400 to-transparent mx-auto"></div>
            </div>

            <div class="bg-gradient-to-br from-red-600/20 via-red-500/15 to-orange-500/20 border border-red-400/40 rounded-xl p-4 mb-6 backdrop-blur-sm max-h-[50vh] overflow-y-auto">
                <div class="text-white leading-relaxed rich-content">
                    {!! $message !!}
                </div>
            </div>

            <div class="text-center">
                <p class="text-white/50 text-sm mb-4">Mohon maaf atas ketidaknyamanannya</p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/40 text-xs">
                7 Kebiasaan Anak Indonesia Hebat
            </p>
        </div>
    </div>
</body>
</html>
