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
                <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link is-active" href="{{ route('facilities.index') }}" aria-current="page">Jelajah Fasilitas</a>
                <a class="nav-link" href="{{ url('/login') }}">Reservasi Saya</a>
                <a class="nav-link" href="{{ url('/login') }}">Lapor Fasilitas</a>
            </nav>
            <a class="login-link" href="{{ url('/login') }}">
                <img src="{{ asset('images/landing/icon-logout.svg') }}" alt="" aria-hidden="true">
                <span>Login</span>
            </a>
            <details class="mobile-nav">
                <summary aria-label="Buka menu navigasi"><span></span><span></span><span></span></summary>
                <nav class="mobile-nav-panel" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('facilities.index') }}" aria-current="page">Jelajah Fasilitas</a>
                    <a href="{{ url('/login') }}">Reservasi Saya</a>
                    <a href="{{ url('/login') }}">Lapor Fasilitas</a>
                    <a href="{{ url('/login') }}">Login</a>
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
            <div class="facility-search" role="group" aria-label="Pencarian fasilitas">
                <span>Cari fasilitas...</span><img src="{{ asset('images/facilities/icon-search.svg') }}" alt="" aria-hidden="true">
            </div>
            <div class="facility-filters" role="group" aria-label="Filter fasilitas">
                <div class="facility-filter facility-filter-type"><span>Tipe</span><img src="{{ asset('images/facilities/icon-arrow1 down.svg') }}" alt="" aria-hidden="true"></div>
                <div class="facility-filter facility-filter-location"><img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true"><span>Lokasi...</span><img class="filter-chevron" src="{{ asset('images/facilities/icon-arrow1 down.svg') }}" alt="" aria-hidden="true"></div>
                <div class="facility-filter facility-filter-date"><img src="{{ asset('images/facilities/icon-calendar.svg') }}" alt="" aria-hidden="true"><span>DD/BB/TTTT</span></div>
                <div class="facility-filter facility-filter-time"><img src="{{ asset('images/facilities/icon-time.svg') }}" alt="" aria-hidden="true"><span>07:00</span></div>
                <img class="filter-arrow" src="{{ asset('images/facilities/icon-arrow2 right.svg') }}" alt="" aria-hidden="true">
                <div class="facility-filter facility-filter-time"><img src="{{ asset('images/facilities/icon-time.svg') }}" alt="" aria-hidden="true"><span>07:30</span></div>
                <span class="filter-submit" aria-hidden="true"><img src="{{ asset('images/facilities/icon-arrow2 right.svg') }}" alt=""></span>
            </div>
            <div class="facility-grid" aria-label="Daftar fasilitas">
                @for ($facility = 0; $facility < 6; $facility++)
                    <article class="facility-card">
                        <div class="facility-card-image" aria-hidden="true"></div><span class="facility-status">Tersedia</span>
                        <div class="facility-card-content">
                            <h2>Ruang Kelas E101</h2>
                            <p class="facility-detail-row"><img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true"><span>FSM - Gedung E - Lt. 1</span></p>
                            <p class="facility-detail-row"><img src="{{ asset('images/facilities/icon-capacity.svg') }}" alt="" aria-hidden="true"><span>Kapasitas 60 orang</span></p>
                        </div>
                        <span class="facility-card-button">Detail</span>
                    </article>
                @endfor
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
