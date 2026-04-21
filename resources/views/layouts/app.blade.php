<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    @if (str_contains(request()->header('host'), 'ngrok-free.dev') || str_contains(request()->header('host'), 'ngrok.io'))
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMK N 5 Telkom Banda Aceh | Dashboard')</title>
    @vite('resources/css/app.css')
    <style>
        /* Custom styles to match exactly */
        .sidebar-logo {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
        }

        .nav-active {
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        .card-shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
        }

        /* Sidebar transition */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        /* Inline editable fields */
        .inline-edit {
            background: transparent;
            border: none;
            outline: none;
            transition: all 0.2s ease;
        }

        .inline-edit:hover {
            background: #f3f4f6;
            border-radius: 4px;
            padding: 2px 4px;
        }

        .inline-edit:focus {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 2px 4px;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        /* Map preview */
        .map-preview {
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }

        .map-preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .map-preview:hover .map-preview-overlay {
            opacity: 1;
        }

        /* Leaflet z-index fix */
        .leaflet-container {
            z-index: 999;
        }

        /* App background - luxurious light gradient */
        .app-bg {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 40%, #f5f3ff 100%);
        }

        /* Sidebar gradient - luxurious deep purple-blue */
        .sidebar-gradient {
            background: linear-gradient(180deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%);
        }

        /* Header gradient - matching luxurious gradient */
        .header-gradient {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #6366f1 100%);
        }
    </style>
</head>

<body class="app-bg min-h-screen">
    <div class="flex relative">
        @include('includes.sidebar')

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0 min-w-0">
            @include('includes.header')

            <!-- Page Content -->
            <main class="p-6 app-bg min-h-screen">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebar);
        if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeSidebar);
        if (mobileOverlay) mobileOverlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                mobileOverlay.classList.add('hidden');
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
