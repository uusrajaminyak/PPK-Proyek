<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistem Reservasi Fasilitas Kampus') }} @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">FasilitasKampus</span>
                    </a>
                </div>

                <!-- Nav Links (Desktop) -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Beranda</a>
                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('reports.index') }}" 
                        class="{{ request()->routeIs('reports.index') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Riwayat Laporan
                    </a>

                    <a href="{{ route('reports.create') }}" 
                        class="{{ request()->routeIs('reports.create') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Lapor Kerusakan
                    </a>

                    <a href="{{ route('officer.reports.index') }}" 
                        class="{{ request()->routeIs('officer.reports.*') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Antrean Petugas
                    </a>

                    <a href="{{ route('officer.facilities.index') }}" 
                        class="{{ request()->routeIs('officer.facilities.*') ? 'text-indigo-600 font-semibold' : 'text-slate-600 hover:text-indigo-600' }} flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Status Fasilitas
                    </a>
                </nav>

                <!-- User Info Badge / Auth Simulation -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('reservations.index') }}" class="text-gray-600 hover:text-blue-600 font-medium transition flex items-center gap-1.5">
                            Cek Reservasi
                        </a>
                    @endauth
                    <a href="#tentang" class="text-gray-600 hover:text-blue-600 font-medium transition">Tentang</a>
                    <a href="#kontak" class="text-gray-600 hover:text-blue-600 font-medium transition">Kontak</a>
                </div>

                <!-- Auth Actions -->
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition">Masuk</a>
                    @else
                        <!-- User Dropdown -->
                        <div class="relative" id="user-menu">
                            <button 
                                onclick="toggleDropdown()" 
                                class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition"
                                aria-expanded="false" aria-haspopup="true"
                            >
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-700 font-semibold text-sm">{{ Str::upper(Auth::user()->name[0]) }}</span>
                                </div>
                                <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div 
                                id="dropdown-menu" 
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50"
                                role="menu"
                            >
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST" class="p-1">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition flex items-center gap-2"
                                        role="menuitem"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button 
                    class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" 
                    onclick="toggleMobileMenu()"
                    aria-label="Toggle menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Nav (Hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-gray-100">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded">Beranda</a>
                    @auth
                        <a href="{{ route('reservations.index') }}" class="px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded font-medium flex items-center gap-2">
                            Cek Reservasi
                        </a>
                    @endauth
                    <a href="#tentang" class="px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded">Tentang</a>
                    <a href="#kontak" class="px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded">Kontak</a>
                    @guest
                        <a href="{{ route('login') }}" class="px-3 py-2 text-blue-600 hover:bg-blue-50 rounded font-medium mt-2">Masuk</a>
                    @else
                        <form action="{{ route('logout') }}" method="POST" class="mt-2 pt-2 border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 text-red-600 hover:bg-red-50 rounded font-medium text-left flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar ({{ Auth::user()->name }})
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4" id="flash-success">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('errors'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4" id="flash-error">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                <ul class="list-disc list-inside space-y-1">
                    @foreach(session('errors')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-64px)]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 mt-auto" id="kontak">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">FasilitasKampus</h3>
                    <p class="text-sm">Sistem Reservasi & Pelaporan Fasilitas Kampus</p>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-3">Link Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-white transition">Tentang</a></li>
                        @auth
                            <li><a href="{{ route('logout') }}" class="hover:text-white transition">Keluar</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Email: fasilitas@kampus.ac.id</li>
                        <li>Telp: (021) 1234-5678</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                &copy; {{ date('Y') }} Sistem Reservasi Fasilitas Kampus. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown-menu');
            const btn = document.querySelector('[onclick="toggleDropdown()"]');
            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', !isOpen);
        }

        document.addEventListener('click', (e) => {
            const menu = document.getElementById('dropdown-menu');
            const btn = document.querySelector('[onclick="toggleDropdown()"]');
            if (menu && !menu.contains(e.target) && !btn?.contains(e.target)) {
                menu.classList.add('hidden');
                btn?.setAttribute('aria-expanded', 'false');
            }
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        setTimeout(() => {
            document.querySelectorAll('#flash-success, #flash-error').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
