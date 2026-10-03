<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dasbor Admin | Fasilita FSM</title>
    @fonts('plus-jakarta-sans')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-dashboard">
    <header class="admin-topbar">
        <a class="brand admin-brand" href="{{ route('admin.dashboard') }}">
            <img class="brand-mark" src="{{ asset('images/undip-logo.png') }}" alt="">
            <span class="brand-name">Fasilita <span>FSM</span></span>
        </a>
        <span class="admin-topbar-divider" aria-hidden="true"></span>
        <h1>Dasbor</h1>
        <div class="admin-account">
            <span class="admin-avatar" aria-hidden="true"></span>
            <span class="admin-account-copy">
                <strong>{{ auth()->user()?->name ?? 'Agus Brody' }}</strong>
                <small>Admin</small>
            </span>
        </div>
    </header>

    <div class="admin-workspace">
        <aside class="admin-sidebar" aria-label="Navigasi admin">
            <nav class="admin-nav-primary">
                <a class="admin-nav-link is-active" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/admin/Dashboard.svg') }}" alt="">
                    <span>Dasbor</span>
                </a>
                <a class="admin-nav-link" href="{{ route('admin.facilities.index') }}">
                    <img src="{{ asset('images/admin/Facilities.svg') }}" alt="">
                    <span>Kelola Fasilitas</span>
                </a>
                <a class="admin-nav-link" href="{{ route('admin.users.index') }}">
                    <img src="{{ asset('images/admin/3 Users.svg') }}" alt="">
                    <span>Kelola Pengguna</span>
                </a>
                <a class="admin-nav-link" href="{{ route('admin.officers.index') }}">
                    <img src="{{ asset('images/admin/Officer.svg') }}" alt="">
                    <span>Kelola Petugas</span>
                </a>
                <a class="admin-nav-link" href="#admin-recap">
                    <img src="{{ asset('images/admin/Recap.svg') }}" alt="">
                    <span>Rekap</span>
                </a>
            </nav>

            <nav class="admin-nav-secondary" aria-label="Lainnya">
                <a class="admin-nav-link" href="#">
                    <img src="{{ asset('images/admin/Setting.svg') }}" alt="">
                    <span>Pengaturan Akun</span>
                </a>
                <a class="admin-nav-link" href="#">
                    <img src="{{ asset('images/admin/Info Circle.svg') }}" alt="">
                    <span>Bantuan</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="admin-nav-link" type="submit">
                        <img src="{{ asset('images/admin/Logout.svg') }}" alt="">
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>
        </aside>

        <main class="admin-main-panel">
            <img class="admin-watermark" src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="" aria-hidden="true">
            <div class="admin-dashboard-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
