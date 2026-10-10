@extends('layouts.officer-dashboard')

@section('title', 'Detail Fasilitas')
@section('header_title', 'Kelola Fasilitas')
@section('brand_label', 'Fasilita FSM')
@section('body_class', 'officer-facility-detail-shell')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    @php
        $facilityStatus = match ($facility->status_fasilitas) {
            'active' => ['Aktif', 'is-active'],
            'in_repair' => ['Dalam Perbaikan', 'is-repair'],
            default => ['Nonaktif', 'is-inactive'],
        };
        $imagePath = $facility->foto_path ?? $facility->foto ?? null;
        $imageUrl = $imagePath
            ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . $imagePath))
            : null;
        $facilityCode = str_pad((string) $facility->id, 8, '0', STR_PAD_LEFT);
    @endphp

    <div class="officer-facility-detail-page">
        <section class="officer-facility-detail-gallery" aria-label="Foto fasilitas">
            <a class="officer-facility-detail-back" href="{{ route('officer.facilities.index') }}">
                <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true"> Kembali
            </a>

            <div class="officer-facility-detail-main-photo">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" alt="Foto {{ $facility->nama_fasilitas }}">
                @endif
            </div>

            <div class="officer-facility-detail-thumbnails" aria-hidden="true">
                @for ($thumbnail = 0; $thumbnail < 3; $thumbnail++)
                    <div class="officer-facility-detail-thumbnail {{ $thumbnail === 0 && $imageUrl ? 'has-image' : '' }}">
                        @if ($thumbnail === 0 && $imageUrl)
                            <img src="{{ $imageUrl }}" alt="">
                        @endif
                    </div>
                @endfor
            </div>
        </section>

        <div class="officer-detail-divider" aria-hidden="true"></div>

        <section class="officer-facility-detail-information" aria-labelledby="officer-facility-detail-title">
            @if (session('success'))
                <div class="officer-detail-alert is-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="officer-detail-alert is-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <header class="officer-facility-detail-heading">
                <h2 id="officer-facility-detail-title">Detail Fasilitas</h2>
                <p>FAC-{{ substr($facilityCode, 0, 4) }}-{{ substr($facilityCode, 4, 4) }}</p>
            </header>

            <div class="officer-facility-detail-summary">
                <h3>{{ $facility->nama_fasilitas }}</h3>
                <div class="officer-facility-detail-meta">
                    <span>
                        <img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true">
                        {{ $facility->lokasi ?: 'Lokasi belum tersedia' }}
                    </span>
                    <span>
                        <img class="officer-facility-capacity-icon" src="{{ asset('images/officer/3-users.svg') }}" alt="" aria-hidden="true">
                        Kapasitas {{ $facility->kapasitas ?: '—' }} orang
                    </span>
                </div>
            </div>

            <section class="officer-facility-description" aria-labelledby="officer-facility-description-title">
                <h3 id="officer-facility-description-title">Fasilitas</h3>
                <p>{{ $facility->deskripsi ?: 'Belum ada deskripsi untuk fasilitas ini.' }}</p>
            </section>

            <section class="officer-facility-operational-status" aria-labelledby="officer-facility-status-title">
                <h3 id="officer-facility-status-title">Status Fasilitas</h3>
                @if ($facility->status_fasilitas === 'inactive')
                    <label class="officer-facility-status-select is-disabled">
                        <span class="sr-only">Status fasilitas</span>
                        <select disabled aria-label="Status fasilitas: {{ $facilityStatus[0] }}">
                            <option selected>{{ $facilityStatus[0] }}</option>
                        </select>
                        <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>
                @else
                    <form class="officer-facility-status-form" action="{{ route('officer.facilities.update-status', $facility) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="officer-facility-status-select">
                            <span class="sr-only">Status fasilitas</span>
                            <select name="status_fasilitas" onchange="this.form.submit()">
                                <option value="active" @selected($facility->status_fasilitas === 'active')>Aktif</option>
                                <option value="in_repair" @selected($facility->status_fasilitas === 'in_repair')>Dalam Perbaikan</option>
                            </select>
                            <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                        </label>
                    </form>
                @endif
            </section>
        </section>
    </div>
@endsection
