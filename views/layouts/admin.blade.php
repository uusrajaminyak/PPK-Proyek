<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilita FSM - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>


<body class="bg-[#F8F9FA] text-[#001248] h-screen overflow-hidden flex flex-col pt-2 md:pt-2 px-3 md:px-4 pb-2 gap-3">

    <!-- Header -->
    <header class="bg-white border border-[#D3D5DB] rounded-[34px] h-14 flex items-center justify-between px-6 z-10 shrink-0 w-full">
        <div class="flex items-center">
            <!-- Branding Logo & Title -->
            <div class="flex items-center space-x-2.5">
                <img src="{{ asset('images/UNDIP 2.png') }}" alt="UNDIP Logo" class="h-8 w-8 object-contain">
                <span class="font-medium text-[24px] text-[#001248] tracking-tight">Fasilita <span class="font-medium">FSM</span></span>
            </div>

            <!-- Vertical Divider Line -->
            <div class="h-7 w-[1px] bg-[#D3D5DB] ml-[50px] mr-6 self-center"></div>

            <!-- Dynamic Page Title -->
            <h1 class="text-[26px] font-bold text-[#001248] tracking-tight">@yield('header_title', 'Dasbor')</h1>
        </div>

        <!-- User Profile Badge -->
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 bg-gray-300 rounded-full overflow-hidden shrink-0"></div>
            <div class="flex flex-col text-left">
                <span class="text-xs font-bold text-[#081640] leading-tight">{{ Auth::user()->name ?? 'Agus Brody' }}</span>
                <span class="text-[9px] text-[#081640] opacity-70 font-medium leading-none mt-0.5">Admin</span>
            </div>
        </div>
    </header>

    <!-- Lower Flex Row Container  -->
    <div class="flex-1 flex gap-3 min-h-0 relative items-stretch overflow-hidden">

        <!-- Background Watermark -->
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none flex items-center justify-start pl-25 overflow-hidden">
            <img src="{{ asset('images/UNDIP 2.png') }}" alt="Watermark" class="w-[55%] max-w-none object-contain grayscale scale-110">
        </div>

        <!-- Dark Navy Sidebar -->
        <aside class="w-56 bg-[#001248] text-white flex flex-col justify-between h-full py-6 px-3.5 z-20 shadow-xl rounded shrink-0">

            <!-- Sidebar Navigation Links -->
            <nav class="space-y-1.5 px-3 py-4 flex-1">
                <!-- 1. Dasbor -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-200 hover:text-white hover:bg-white/10 font-semibold' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    <span class="ml-3">Dasbor</span>
                </a>

                <!-- 2. Kelola Fasilitas (Highlighted when route matches admin.facilities.*) -->
                <a href="{{ route('admin.facilities.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.facilities.*') ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-200 hover:text-white hover:bg-white/10 font-semibold' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 9V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3"></path>
                        <rect x="3" y="9" width="18" height="11" rx="2"></rect>
                        <path d="M12 12v3"></path>
                    </svg>
                    <span class="ml-3">Kelola Fasilitas</span>
                </a>

                <!-- 3. Kelola Pengguna -->
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-200 hover:text-white hover:bg-white/10 font-semibold' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="ml-3">Kelola Pengguna</span>
                </a>

                <!-- 4. Kelola Petugas -->
                <a href="{{ route('admin.officers.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.officers.*') ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-200 hover:text-white hover:bg-white/10 font-semibold' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span class="ml-3">Kelola Petugas</span>
                </a>

                <!-- 5. Rekap -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.rekap.*') ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-200 hover:text-white hover:bg-white/10 font-semibold' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="ml-3">Rekap</span>
                </a>
            </nav>

            <!-- Bottom Navigation Items -->
            <div class="space-y-2 pt-2">
                <!-- 6. Pengaturan Akun -->
                <a href="#" class="flex items-center px-3.5 py-2.5 text-gray-200 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition-colors text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span class="ml-3">Pengaturan Akun</span>
                </a>

                <!-- 7. Bantuan -->
                <a href="#" class="flex items-center px-3.5 py-2.5 text-gray-200 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition-colors text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span class="ml-3">Bantuan</span>
                </a>

                <!-- 8. Keluar -->
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3.5 py-2.5 text-gray-200 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition-colors text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ml-3">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Dynamic Content Wrapper -->
        <main class="flex-1 h-full z-10 relative pr-1 flex flex-col min-w-0">
            @yield('content')
        </main>
    </div>

</body>

</html>