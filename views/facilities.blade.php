<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <meta name="description" content="Jelajahi fasilitas yang tersedia di lingkungan Universitas Diponegoro.">
    <title>Jelajah Fasilitas — Fasilita UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body>
    <header class="site-header">
        <div class="navbar">
            <a class="brand" href="{{ route('home') }}" aria-label="Fasilita UNDIP, beranda">
                <img class="brand-mark" src="{{ asset('images/undip-logo.png') }}" alt="">
                <span class="brand-name">Fasilita <span>UNDIP</span></span>
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
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="login-link" style="background:transparent; border:none; cursor:pointer;">
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
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
                        <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:transparent; border:none; cursor:pointer;">Logout</button>
                        </form>
                    @endguest
                </nav>
            </details>
        </div>
    </header>
    <main class="facilities-page">
        <div class="facilities-watermark" aria-hidden="true">
            <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
        </div>
        <section class="facilities-content" aria-labelledby="facilities-title">
            <div class="facilities-intro">
                <h1 id="facilities-title">Jelajah Fasilitas</h1>
                <p>Temukan berbagai fasilitas yang tersedia di lingkungan UNDIP, mulai dari ruang kelas, aula, laboratorium, hingga lapangan olahraga. Setiap fasilitas dilengkapi informasi lokasi, kapasitas, dan status ketersediaan secara real-time, sehingga kamu bisa merencanakan kegiatan dengan lebih mudah tanpa perlu bertanya langsung ke petugas. Gunakan fitur pencarian dan filter untuk menemukan fasilitas yang sesuai dengan kebutuhanmu, baik berdasarkan tipe, lokasi, maupun ketersediaan.</p>
            </div>
            <form method="GET" action="{{ route('facilities.index') }}" class="facility-search-form">
                <div class="facility-search">
                    <input type="text" name="nama" value="{{ $filters['nama'] ?? '' }}" placeholder="Cari nama fasilitas..." class="bg-transparent border-none outline-none w-full">
                    <button type="submit">
                        <img src="{{ asset('images/facilities/icon-search.svg') }}" alt="Cari">
                    </button>
                </div>

                <div class="facility-filters">
                    <!-- Filter Tipe -->
                    <select name="tipe" onchange="this.form.submit()" class="facility-filter">
                        <option value="">Semua Tipe</option>
                        @foreach($tipeList as $tipe)
                            <option value="{{ $tipe }}" {{ ($filters['tipe'] ?? '') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                        @endforeach
                    </select>

                    <!-- Filter Lokasi -->
                    <select name="lokasi" onchange="this.form.submit()" class="facility-filter">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lokasi)
                            <option value="{{ $lokasi }}" {{ ($filters['lokasi'] ?? '') === $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                        @endforeach
                    </select>

                    @if(!empty($filters))
                        <a href="{{ route('facilities.index') }}" class="text-xs text-blue-600 underline">Reset</a>
                    @endif
                </div>
            </form>
            <div class="facility-grid" aria-label="Daftar fasilitas">
                @forelse($facilities as $facility)
                    <article class="facility-card {{ $facility->status_fasilitas === 'in_repair' ? 'is-repair' : '' }}">
                        <div class="facility-card-image" aria-hidden="true"></div>
                        
                        <!-- Badge Status -->
                        <span class="facility-status {{ $facility->status_fasilitas === 'in_repair' ? 'status-repair' : '' }}">
                            {{ $facility->getStatusLabel() }}
                        </span>

                        <div class="facility-card-content">
                            <h2>{{ $facility->nama_fasilitas }}</h2>
                            <p class="facility-detail-row">
                                <img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true">
                                <span>{{ $facility->lokasi }} ({{ $facility->tipe }})</span>
                            </p>
                            <p class="facility-detail-row">
                                <img src="{{ asset('images/facilities/icon-capacity.svg') }}" alt="" aria-hidden="true">
                                <span>Kapasitas {{ number_format($facility->kapasitas) }} orang</span>
                            </p>
                        </div>

                        <!-- Tombol Aksi Reservasi -->
                        @if($facility->status_fasilitas === 'active')
                            <a href="{{ route('reservations.create', $facility->id) }}" class="facility-card-button">
                                Reservasi
                            </a>
                        @else
                            <span class="facility-card-button" style="background:#d97706; cursor:not-allowed;">
                                Sedang Perbaikan
                            </span>
                        @endif
                    </article>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Tidak ada fasilitas yang sesuai dengan pencarian Anda.
                    </div>
                @endforelse
            </div>

            <!-- Pagination Dinamis -->
            <div class="mt-6">
                {{ $facilities->links() }}
            </div>
            <nav class="facility-pagination" aria-label="Navigasi halaman">
                <span class="pagination-arrow is-disabled" aria-hidden="true"><img class="pagination-arrow-icon is-left" src="{{ asset('images/facilities/icon-arrow1 down.svg') }}" alt=""></span><span class="pagination-page is-current" aria-current="page">1</span><span class="pagination-page">2</span><span class="pagination-arrow" aria-hidden="true"><img class="pagination-arrow-icon is-right" src="{{ asset('images/facilities/icon-arrow1 down.svg') }}" alt=""></span>
            </nav>
        </section>
    </main>
    <footer class="site-footer">
        <p>Fasilita UNDIP — Sistem Reservasi &amp; Pelaporan Fasilitas Kampus Universitas Diponegoro © 2026</p>
    </footer>
</body>
</html>
