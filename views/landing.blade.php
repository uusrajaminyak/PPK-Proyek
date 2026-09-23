<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#001348">
    <meta name="description" content="Jelajahi, reservasi, dan laporkan fasilitas kampus Universitas Diponegoro dengan mudah.">
    <title>Fasilita UNDIP — Reservasi Fasilitas Kampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body>
    <main>
        <section class="landing-hero" id="beranda" aria-labelledby="landing-title">
            <img class="hero-background" src="{{ asset('images/landing/landing-hero.webp') }}" alt="" aria-hidden="true">
            <div class="hero-watermark" aria-hidden="true">
                <img src="{{ asset('images/landing/undip-watermark.png') }}" alt="">
            </div>
            <header class="site-header">
                <div class="navbar">
                    <a class="brand" href="{{ route('home') }}" aria-label="Fasilita UNDIP, beranda">
                        <img class="brand-mark" src="{{ asset('images/undip-logo.png') }}" alt="">
                        <span class="brand-name">Fasilita <span>UNDIP</span></span>
                    </a>
                    <nav class="desktop-nav" aria-label="Navigasi utama">
                        <a class="nav-link is-active" href="{{ route('home') }}" aria-current="page">Beranda</a>
                        <a class="nav-link" href="#cara-kerja">Jelajah Fasilitas</a>
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
                            <a href="{{ route('home') }}" aria-current="page">Beranda</a>
                            <a href="#cara-kerja">Jelajah Fasilitas</a>
                            <a href="{{ url('/login') }}">Reservasi Saya</a>
                            <a href="{{ url('/login') }}">Lapor Fasilitas</a>
                            <a href="{{ url('/login') }}">Login</a>
                        </nav>
                    </details>
                </div>
            </header>
            <div class="hero-copy">
                <h1 id="landing-title">Reservasi Fasilitas Kampus<br>Jadi Lebih Mudah</h1>
                <p>Fasilita UNDIP membantu civitas akademika UNDIP mengecek ketersediaan, memesan, dan melaporkan kondisi fasilitas kampus, semua dalam satu platform.</p>
            </div>
        </section>
        <section class="how-it-works" id="cara-kerja" aria-labelledby="how-it-works-title">
            <h2 id="how-it-works-title">Cara Kerja</h2>
            <div class="steps-grid">
                <article class="step-card">
                    <div class="step-icon"><span class="step-icon-circle" aria-hidden="true"></span><img src="{{ asset('images/landing/icon-search.svg') }}" alt="" aria-hidden="true"></div>
                    <h3>Cari Fasilitas</h3>
                    <p>Telusuri ruang kelas, aula, laboratorium, alat, dan lapangan berdasarkan tipe, lokasi, atau kapasitas.</p>
                </article>
                <article class="step-card">
                    <div class="step-icon"><span class="step-icon-circle" aria-hidden="true"></span><img src="{{ asset('images/landing/icon-calendar.svg') }}" alt="" aria-hidden="true"></div>
                    <h3>Pilih Jadwal</h3>
                    <p>Cek ketersediaan slot waktu secara real-time dan ajukan reservasi sesuai kebutuhanmu.</p>
                </article>
                <article class="step-card">
                    <div class="step-icon"><span class="step-icon-circle" aria-hidden="true"></span><img src="{{ asset('images/landing/icon-check.svg') }}" alt="" aria-hidden="true"></div>
                    <h3>Konfirmasi &amp; Gunakan</h3>
                    <p>Pantau status reservasimu dan gunakan fasilitas sesuai jadwal yang disetujui.</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <p>Fasilita UNDIP — Sistem Reservasi &amp; Pelaporan Fasilitas Kampus Universitas Diponegoro © 2026</p>
    </footer>
</body>
</html>
