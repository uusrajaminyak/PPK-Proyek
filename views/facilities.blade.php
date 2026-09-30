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
    @include('partials.navbar')
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
                            <span class="facility-card-button is-repair">
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
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
