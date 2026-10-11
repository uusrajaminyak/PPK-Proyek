<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <meta name="description" content="Jelajahi fasilitas yang tersedia di Fakultas Sains dan Matematika Universitas Diponegoro.">
    <title>Jelajah Fasilitas — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body>
    @include('partials.navbar')
    <main class="facilities-page">
        <div class="facilities-watermark" aria-hidden="true">
            <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
        </div>
        <section class="facilities-content" aria-labelledby="facilities-title" data-ajax-list>
            <div class="facilities-intro">
                <h1 id="facilities-title">Jelajah Fasilitas</h1>
                <p>Temukan fasilitas yang tersedia di Fakultas Sains dan Matematika, mulai dari ruang kelas, aula, laboratorium, hingga lapangan olahraga. Setiap fasilitas dilengkapi informasi lokasi, kapasitas, dan status ketersediaan secara real-time. Gunakan pencarian dan filter untuk menemukan fasilitas FSM sesuai kebutuhanmu.</p>
            </div>
            <form method="GET" action="{{ route('facilities.index') }}" class="officer-facility-filterbar facility-browse-filterbar">
                <label class="officer-facility-search">
                    <span class="sr-only">Cari fasilitas</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari fasilitas...">
                    <button type="submit" aria-label="Cari fasilitas">
                        <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
                    </button>
                </label>

                <div class="officer-facility-filter-controls">
                    <label class="officer-facility-select">
                        <span class="sr-only">Filter status fasilitas</span>
                    <select name="status" onchange="this.form.requestSubmit()">
                            <option value="">Status</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Tersedia</option>
                            <option value="in_repair" @selected(($filters['status'] ?? '') === 'in_repair')>Dalam Perbaikan</option>
                            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Tidak Tersedia</option>
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>

                    <label class="officer-facility-select">
                        <span class="sr-only">Filter tipe fasilitas</span>
                    <select name="tipe" onchange="this.form.requestSubmit()">
                            <option value="">Tipe</option>
                            @foreach($tipeList as $tipe)
                                <option value="{{ $tipe }}" {{ ($filters['tipe'] ?? '') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>

                    <label class="officer-facility-select officer-facility-sort">
                        <span class="sr-only">Urutkan fasilitas</span>
                    <select name="urut" onchange="this.form.requestSubmit()">
                            <option value="terbaru" @selected(($filters['urut'] ?? 'terbaru') === 'terbaru')>Terbaru</option>
                            <option value="terlama" @selected(($filters['urut'] ?? '') === 'terlama')>Terlama</option>
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>

                    @if(!empty($filters))
                        <a href="{{ route('facilities.index') }}" class="facility-filter-reset" data-ajax-filter-link>Reset</a>
                    @endif
                </div>
            </form>
            <div class="facility-grid" aria-label="Daftar fasilitas">
                @forelse($facilities as $facility)
                    @php
                        $facilityStatusClass = match ($facility->status_fasilitas) {
                            'active' => 'is-active',
                            'in_repair' => 'is-repair',
                            default => 'is-inactive',
                        };
                    @endphp

                    <article class="facility-card">
                        <div class="facility-card-image" aria-hidden="true"></div>

                        <span class="officer-facility-card-status {{ $facilityStatusClass }}">
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

                        <a href="{{ route('reservations.create', $facility->id) }}" class="facility-card-button">
                            Detail
                        </a>
                    </article>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Tidak ada fasilitas yang sesuai dengan pencarian Anda.
                    </div>
                @endforelse
            </div>

            @if ($facilities->hasPages())
                <div class="officer-facility-pagination">{{ $facilities->links('vendor.pagination.officer-facilities') }}</div>
            @endif
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
