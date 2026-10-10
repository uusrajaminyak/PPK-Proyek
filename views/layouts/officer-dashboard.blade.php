<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#001348">
    <title>@yield('title', 'Dasbor Petugas') — Fasilita UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="officer-dashboard-page @yield('body_class')">
    <div class="officer-app-frame">
        <header class="officer-topbar">
            <a href="{{ route('officer.dashboard') }}" class="officer-brand" aria-label="Fasilita UNDIP">
                <img src="{{ asset('images/undip-logo.png') }}" alt="" aria-hidden="true">
                <span>@yield('brand_label', 'Fasilita FSM')</span><span class="officer-brand-divider" aria-hidden="true"></span>
            </a>
            <h1 class="officer-page-title">@yield('header_title', 'Dasbor')</h1>
            <div class="officer-account">
                <span class="officer-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="officer-account-copy">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->isAdmin() ? 'Admin' : 'Petugas' }}</small>
                </span>
            </div>
        </header>

        <div class="officer-workspace">
            <aside class="officer-sidebar" aria-label="Navigasi petugas">
                <nav class="officer-nav-main">
                    <a href="{{ route('officer.dashboard') }}" class="officer-nav-link {{ request()->routeIs('officer.dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('officer.dashboard')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/dashboard.svg') }}" alt="" aria-hidden="true"><span>Dasbor</span>
                    </a>
                    <a href="{{ route('officer.reservations.index') }}" class="officer-nav-link {{ request()->routeIs('officer.reservations.index', 'officer.reservations.show', 'officer.reservations.update-status') ? 'is-active' : '' }}" @if (request()->routeIs('officer.reservations.index', 'officer.reservations.show', 'officer.reservations.update-status')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/reservations.svg') }}" alt="" aria-hidden="true"><span>Kelola Reservasi</span>
                    </a>
                    <a href="{{ route('officer.reservations.history') }}" class="officer-nav-link {{ request()->routeIs('officer.reservations.history') ? 'is-active' : '' }}" @if (request()->routeIs('officer.reservations.history')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/history.svg') }}" alt="" aria-hidden="true"><span>Riwayat Reservasi</span>
                    </a>
                    <a href="{{ route('officer.reports.index') }}" class="officer-nav-link {{ request()->routeIs('officer.reports.*') ? 'is-active' : '' }}" @if (request()->routeIs('officer.reports.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/reports.svg') }}" alt="" aria-hidden="true"><span>Kelola Laporan</span>
                    </a>
                    <a href="{{ route('officer.facilities.index') }}" class="officer-nav-link {{ request()->routeIs('officer.facilities.*') ? 'is-active' : '' }}" @if (request()->routeIs('officer.facilities.*')) aria-current="page" @endif>
                        <img src="{{ asset('images/officer/facilities.svg') }}" alt="" aria-hidden="true"><span>Kelola Fasilitas</span>
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
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
