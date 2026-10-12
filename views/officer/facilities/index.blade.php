@extends('layouts.officer-dashboard')

@section('title', 'Kelola Fasilitas')
@section('header_title', 'Kelola Fasilitas')
@section('brand_label', 'Fasilita FSM')
@section('body_class', 'officer-facilities-shell')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    <div class="officer-facilities-page" data-ajax-list>
        @if (session('success'))
            <div class="officer-report-alert is-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="officer-report-alert is-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form class="officer-facility-filterbar" method="GET" action="{{ route('officer.facilities.index') }}">
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
                    </select>
                    <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>

                <label class="officer-facility-select">
                    <span class="sr-only">Filter tipe fasilitas</span>
                    <select name="tipe" onchange="this.form.requestSubmit()">
                        <option value="">Tipe</option>
                        @foreach ($facilityTypes as $facilityType)
                            <option value="{{ $facilityType }}" @selected(($filters['tipe'] ?? '') === $facilityType)>{{ $facilityType }}</option>
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
            </div>
        </form>

        <section class="officer-facility-card-grid" aria-label="Daftar fasilitas">
            @forelse ($facilities as $facility)
                @php
                    [$facilityStatusLabel, $facilityStatusClass] = match ($facility->status_fasilitas) {
                        'active' => ['Tersedia', 'is-active'],
                        'in_repair' => ['Dalam Perbaikan', 'is-repair'],
                        default => ['Tidak Tersedia', 'is-inactive'],
                    };
                @endphp

                <article class="officer-facility-card">
                    <div class="officer-facility-card-image">
                        @if ($facility->foto_url)
                            <img src="{{ $facility->foto_url }}" alt="Foto {{ $facility->nama_fasilitas }}" loading="lazy">
                        @endif
                        <span class="officer-facility-card-status {{ $facilityStatusClass }}">{{ $facilityStatusLabel }}</span>
                    </div>

                    <div class="officer-facility-card-body">
                        <div class="officer-facility-card-copy">
                            <h2 title="{{ $facility->nama_fasilitas }}">{{ $facility->nama_fasilitas }}</h2>
                            <p>
                                <img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true">
                                <span title="{{ $facility->lokasi }}">{{ $facility->lokasi ?: 'Lokasi belum tersedia' }}</span>
                            </p>
                            <p>
                                <img src="{{ asset('images/officer/3-users.svg') }}" alt="" aria-hidden="true">
                                <span>Kapasitas {{ $facility->kapasitas ?: '—' }} orang</span>
                            </p>
                        </div>
                        <a class="officer-facility-detail-button" href="{{ route('officer.facilities.show', $facility) }}">Detail</a>
                    </div>
                </article>
            @empty
                <div class="officer-facility-empty">
                    <h2>Fasilitas tidak ditemukan</h2>
                    <p>Coba ubah kata pencarian atau filter yang dipilih.</p>
                </div>
            @endforelse
        </section>

        @if ($facilities->hasPages())
            <div class="officer-facility-pagination">{{ $facilities->links('vendor.pagination.officer-facilities') }}</div>
        @endif
    </div>
@endsection
