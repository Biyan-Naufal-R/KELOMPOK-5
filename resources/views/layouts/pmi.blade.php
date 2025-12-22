<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Darah PMI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome will be loaded as JS to render icons as inline SVG -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pmi-red': '#dc2626',
                        'pmi-dark': '#1f2937',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-pmi-dark text-white">
            <div class="p-6">
                <h1 class="text-xl font-bold mb-6">
                    <i class="fas fa-hospital mr-2"></i>PMI Blood System
                </h1>
                <div class="mb-6 p-4 bg-red-900 rounded-lg">
                    <div class="text-sm">Staff PMI</div>
                    <div class="font-bold">{{ auth()->user()->name }}</div>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('pmi.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.dashboard') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-home mr-3"></i> Home
                    </a>
                    <a href="{{ route('pmi.blood-stock') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.blood-stock') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-tint mr-3"></i> Stok Darah
                    </a>
                    <a href="{{ route('pmi.blood-requests') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.blood-requests') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-hand-paper mr-3"></i> Permintaan RS
                    </a>
                    <a href="{{ route('pmi.distribution') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.distribution') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-truck mr-3"></i> Distribusi
                    </a>
                    <a href="{{ route('pmi.hospitals') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.hospitals') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-hospital-alt mr-3"></i> Data RS
                    </a>
                    <!-- Laporan removed -->
                    <a href="{{ route('pmi.settings') }}" class="flex items-center p-3 rounded-lg hover:bg-red-800 {{ request()->routeIs('pmi.settings') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-users-cog mr-3"></i> Kelola Pengguna
                    </a>
                </nav>
            </div>
            
            <div class="absolute bottom-0 w-64 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full p-3 bg-red-700 rounded-lg hover:bg-red-800">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('header', 'Dashboard')
                    </h2>
                    <div class="text-sm text-gray-600">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            <!-- Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-"]');
            alerts.forEach(alert => {
                if (alert.classList.contains('bg-green-100') || alert.classList.contains('bg-red-100')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>
    <!-- Font Awesome JS to ensure icons render as inline SVG and stay visible -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    <script>
        // Ensure Font Awesome watches DOM changes and re-renders icons when needed
        try {
            if (window.FontAwesome && FontAwesome.dom && typeof FontAwesome.dom.watch === 'function') {
                FontAwesome.dom.watch();
            }
        } catch (e) {
            console.warn('FontAwesome watch failed', e);
        }

        // Re-run watch on user interactions that previously caused icons to disappear
        ['scroll', 'click', 'resize'].forEach(evt => {
            window.addEventListener(evt, () => {
                try {
                    if (window.FontAwesome && FontAwesome.dom && typeof FontAwesome.dom.i2svg === 'function') {
                        FontAwesome.dom.i2svg();
                    }
                } catch (e) {
                    // ignore
                }
            }, { passive: true });
        });
    </script>
</html>