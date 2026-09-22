<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fasilitas UNDIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#0a1E55] text-white flex flex-col hidden md:flex border-r border-gray-800">
        <div class="flex items-center px-6 h-20 border-b border-gray-800">
            <img src="{{ asset('images/undip-logo.png') }}" alt="Logo UNDIP" class="h-10 w-10 mr-3 object-contain">
            <div class="flex flex-col">
                <h1 class="text-lg font-bold leading-tight">Fasilitas UNDIP</h1>
                <span class="text-[10px] text-gray-400 font-medium">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="#" class="flex items-center px-4 py-3 bg-white/10 rounded-lg text-white transition-colors">
                <i class="fas fa-users w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Dashboard</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="far fa-user w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Kelola Pengguna</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="fas fa-user-shield w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Kelola Petugas</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="fas fa-map-marker-alt w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Kelola Fasilitas</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="far fa-calendar-alt w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Reservasi</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="far fa-comment-alt w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Laporan Kerusakan</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <i class="fas fa-clipboard-list w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Rekap & Laporan</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors mb-2">
                <i class="far fa-user-circle w-6 text-center text-sm"></i>
                <span class="ml-3 text-sm font-medium">Profile</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center px-4 py-2 mt-2">
                <img src="https://ui-avatars.com/api/?name=Admin+Utama&background=0D8ABC&color=fff" alt="Admin" class="h-9 w-9 rounded-full object-cover border border-gray-600">
                <div class="ml-3 flex flex-col">
                    <span class="text-sm font-bold text-white">Admin Utama</span>
                    <span class="text-[10px] text-gray-400">admin@undip.ac.id</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b flex items-center justify-between px-8">
            <div>
                <p class="text-sm text-gray-500">Admin / Beranda</p>
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Utama</h2>
            </div>
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <input type="text" placeholder="Cari sesuatu..." class="bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button class="text-gray-500 hover:text-gray-700 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">Admin Utama</p>
                        <p class="text-xs text-blue-500">Admin</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gray-300"></div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
            @yield('content')
        </main>
    </div>
</body>

</html>