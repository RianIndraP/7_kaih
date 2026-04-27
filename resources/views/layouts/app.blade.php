<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMK N 5 Telkom Banda Aceh | Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                  shadow-[4px_0_24px_rgba(37,99,235,0.18)] print:hidden">
        @include('includes.sidebar')
    </aside>

    {{-- ── MAIN WRAPPER ── --}}
    <div class="lg:ml-60 flex flex-col min-h-screen transition-[margin] duration-300 ease-in-out print:ml-0">

        @include('includes.header')

        <main class="flex-1 p-5 lg:p-8 xl:p-10 2xl:p-12">
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
