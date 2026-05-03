<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin | SMK N 5 Telkom Banda Aceh')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Custom styles for admin - blue/indigo theme (matching student/kepala sekolah sidebar) */
        .sidebar-gradient {
            background: linear-gradient(180deg, #1d4ed8 0%, #4f46e5 60%, #4338ca 100%);
        }
        
        .header-gradient {
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 60%, #2563eb 100%);
        }
        
        .page-bg {
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 40%, #eff6ff 100%);
        }
        
        .nav-active-accent {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.3), rgba(79, 70, 229, 0.3));
        }
        
        .shadow-[4px_0_24px_rgba(124,58,237,0.18)] {
            box-shadow: 4px 0 24px rgba(37, 99, 235, 0.18);
        }
        
        .shadow-[0_2px_18px_rgba(124,58,237,0.22)] {
            box-shadow: 0 2px 18px rgba(37, 99, 235, 0.22);
        }
    </style>
</head>

<body class="page-bg min-h-screen font-sans">

    {{-- Mobile overlay --}}
    <div id="mobileOverlay" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-39 lg:hidden"
        onclick="closeSidebar()">
    </div>

    {{-- ── SIDEBAR ── --}}
    <aside id="sidebar"
        class="sidebar-gradient fixed top-0 left-0 w-60 min-h-screen flex flex-col z-40
                  -translate-x-full lg:translate-x-0
                  transition-transform duration-300 ease-in-out
                  shadow-[4px_0_24px_rgba(124,58,237,0.18)] print:hidden">
        @include('includes.sidebar-admin')
    </aside>

    {{-- ── MAIN WRAPPER ── --}}
    <div class="lg:ml-60 flex flex-col min-h-screen transition-[margin] duration-300 ease-in-out print:ml-0">

        @include('includes.header-admin')

        <main class="flex-1 p-5 lg:p-6">
            @yield('content')
        </main>

    </div>

    {{-- Hidden logout form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const hamburger = document.getElementById('hamburgerBtn');
        const sbClose = document.getElementById('sbCloseBtn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (hamburger) hamburger.addEventListener('click', openSidebar);
        if (sbClose) sbClose.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
