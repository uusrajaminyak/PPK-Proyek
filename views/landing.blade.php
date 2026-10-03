<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#001348">
    <meta name="description" content="Jelajahi, reservasi, dan laporkan fasilitas kampus Universitas Diponegoro dengan mudah.">
    <title>Fasilita FSM — Reservasi Fasilitas Kampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body>
    @include('partials.navbar')
    <main>
        <section class="landing-hero" id="beranda" aria-labelledby="landing-title">
            <img class="hero-background" src="{{ asset('images/landing/landing-hero.jpg') }}" alt="" aria-hidden="true">
            <div class="hero-watermark" aria-hidden="true">
                <img src="{{ asset('images/landing/undip-watermark.png') }}" alt="">
            </div>
            <div class="hero-copy">
                <h1 id="landing-title">Reservasi Fasilitas Jadi Lebih Mudah</h1>
                <p>Fasilita FSM membantu civitas akademika FSM UNDIP mengecek ketersediaan, memesan, dan melaporkan kondisi fasilitas kampus, semua dalam satu platform.</p>
            </div>
        </section>
        <section class="how-it-works" id="cara-kerja" aria-labelledby="how-it-works-title">
            <h2 id="how-it-works-title">Cara Kerja</h2>
            <div class="steps-grid">
                <article class="step-card">
                    <div class="step-icon"><span class="step-icon-circle" aria-hidden="true"></span><img src="{{ asset('images/landing/icon-search.svg') }}" alt="" aria-hidden="true"></div>
                    <h3>Cari Fasilitas</h3>
                    <p>Telusuri ruang kelas, aula, laboratorium, dan lapangan berdasarkan lokasi, atau kapasitas.</p>
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
    @include('partials.footer')
</body>
</html>
