<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#001348">
    <title>@yield('title', 'Dasbor Admin') — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="officer-dashboard-page">
    <div class="officer-app-frame">
        <header class="officer-topbar">
            <a href="{{ route('admin.dashboard') }}" class="officer-brand" aria-label="Fasilita FSM">
                <img src="{{ asset('images/undip-logo.png') }}" alt="" aria-hidden="true">
                <span>Fasilita FSM</span><span class="officer-brand-divider" aria-hidden="true"></span>
            </a>
            <h1 class="officer-page-title">@yield('header_title', 'Dasbor')</h1>
            <div class="officer-account">
                <span class="officer-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="officer-account-copy">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>Admin</small>
                </span>
            </div>
        </header>

        <div class="officer-workspace">
            <aside class="officer-sidebar" aria-label="Navigasi admin">
                <nav class="officer-nav-main">
                    <a href="{{ route('admin.dashboard') }}" class="officer-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/dashboard.svg') }}" alt="" aria-hidden="true"><span>Dasbor</span>
                    </a>
                    <a href="{{ route('admin.facilities.index') }}" class="officer-nav-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.facilities.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/facilities.svg') }}" alt="" aria-hidden="true"><span>Kelola Fasilitas</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="officer-nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.users.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/3-users.svg') }}" alt="" aria-hidden="true"><span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('admin.officers.index') }}" class="officer-nav-link {{ request()->routeIs('admin.officers.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.officers.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/admin/officer.svg') }}" alt="" aria-hidden="true"><span>Kelola Petugas</span>
                    </a>
                    <a href="{{ route('admin.recap.index') }}" class="officer-nav-link {{ request()->routeIs('admin.recap.*') ? 'is-active' : '' }}" @if (request()->routeIs('admin.recap.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/admin/recap.svg') }}" alt="" aria-hidden="true"><span>Rekap</span>
                    </a>
                </nav>

                <nav class="officer-nav-secondary" aria-label="Bantuan dan akun">
                    <a href="#" class="officer-nav-link" aria-disabled="true">
                        <img src="{{ asset('images/officer/settings.svg') }}" alt="" aria-hidden="true"><span>Pengaturan Akun</span>
                    </a>
                    <a href="#" class="officer-nav-link" aria-disabled="true">
                        <img src="{{ asset('images/officer/help.svg') }}" alt="" aria-hidden="true"><span>Bantuan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="officer-nav-link officer-logout">
                            <img src="{{ asset('images/officer/logout.svg') }}" alt="" aria-hidden="true"><span>Keluar</span>
                        </button>
                    </form>
                </nav>
            </aside>

            <main class="officer-main">
                <div class="officer-main-watermark" aria-hidden="true">
                    <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
                </div>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
