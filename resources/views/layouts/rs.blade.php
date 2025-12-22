<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Darah RS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome will be loaded as JS to render icons as inline SVG -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'rs-blue': '#2563eb',
                        'rs-dark': '#1e40af',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-rs-dark text-white">
            <div class="p-6">
                <h1 class="text-xl font-bold mb-6">
                    <i class="fas fa-hospital mr-2"></i>RS Blood System
                </h1>
                <div class="mb-6 p-4 bg-blue-900 rounded-lg">
                    <div class="text-sm">Staff Rumah Sakit</div>
                    <div class="font-bold">{{ auth()->user()->name }}</div>
                    @if(auth()->user()->hospital)
                        <div class="text-sm mt-1">{{ auth()->user()->hospital->name }}</div>
                    @endif
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('rs.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-home mr-3"></i> Home
                    </a>
                    <a href="{{ route('rs.create-request') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.create-request') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-plus-circle mr-3"></i> Buat Request
                    </a>
                    <a href="{{ route('rs.requests') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.requests') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-list mr-3"></i> Daftar Request
                    </a>
                    <a href="{{ route('rs.blood-receipt') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.blood-receipt') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-clipboard-check mr-3"></i> Penerimaan
                    </a>
                    <a href="{{ route('rs.history') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.history') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-history mr-3"></i> Riwayat
                    </a>
                    <a href="{{ route('rs.profile') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-800 {{ request()->routeIs('rs.profile') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-user mr-3"></i> Profil RS
                    </a>
                </nav>
            </div>
            
            <div class="absolute bottom-0 w-64 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full p-3 bg-blue-700 rounded-lg hover:bg-blue-800">
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
        // Auto-hide alerts setelah 5 detik
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
    <!-- Font Awesome JS to ensure icons render as inline SVG and stay visible -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    <script>
        try {
            if (window.FontAwesome && FontAwesome.dom && typeof FontAwesome.dom.watch === 'function') {
                FontAwesome.dom.watch();
            }
        } catch (e) {
            console.warn('FontAwesome watch failed', e);
        }

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
</body>
</html>