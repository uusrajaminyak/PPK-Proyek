<header class="site-header">
    <div class="navbar">
        <a class="brand" href="{{ route('home') }}" aria-label="Fasilita FSM, beranda">
            <img class="brand-mark" src="{{ asset('images/undip-logo.png') }}" alt="">
            <span class="brand-name">Fasilita <span>FSM</span></span>
        </a>
        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="nav-link {{ request()->routeIs('facilities.*') ? 'is-active' : '' }}" href="{{ route('facilities.index') }}">Jelajah Fasilitas</a>
            @auth
                <a class="nav-link {{ request()->routeIs('reservations.*') ? 'is-active' : '' }}" href="{{ route('reservations.index') }}">Reservasi Saya</a>
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'is-active' : '' }}" href="{{ route('reports.create') }}">Lapor Kerusakan</a>
            @else
                <a class="nav-link" href="{{ route('login') }}">Reservasi Saya</a>
                <a class="nav-link" href="{{ route('login') }}">Lapor Kerusakan</a>
            @endauth
        </nav>
        @guest
            <a class="login-link" href="{{ route('login') }}">
                <img src="{{ asset('images/landing/icon-logout.svg') }}" alt="" aria-hidden="true">
                <span>Login</span>
            </a>
        @else
            <form action="{{ route('logout') }}" method="POST" class="login-link">
                @csrf
                <button type="submit" aria-label="Logout"
                    style="display:inline-flex;align-items:center;gap:inherit;background:none;border:none;padding:0;cursor:pointer;color:inherit;font:inherit;">
                    <img src="{{ asset('images/landing/icon-logout.svg') }}" alt="" aria-hidden="true">
                    <span>Logout</span>
                </button>
            </form>
        @endguest
        <details class="mobile-nav">
            <summary aria-label="Buka menu navigasi"><span></span><span></span><span></span></summary>
            <nav class="mobile-nav-panel" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" {{ request()->routeIs('home') ? 'aria-current="page"' : '' }}>Beranda</a>
                <a href="{{ route('facilities.index') }}" {{ request()->routeIs('facilities.*') ? 'aria-current="page"' : '' }}>Jelajah Fasilitas</a>
                @auth
                    <a href="{{ route('reservations.index') }}">Reservasi Saya</a>
                    <a href="{{ route('reports.create') }}">Lapor Kerusakan</a>
                @else
                    <a href="{{ route('login') }}">Reservasi Saya</a>
                    <a href="{{ route('login') }}">Lapor Kerusakan</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}">Login</a>
                @else
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        Logout
                    </a>
                @endguest
            </nav>
        </details>
    </div>
</header>
