@extends('layouts.officer-dashboard')

@section('title', 'Kelola Laporan')
@section('header_title', 'Kelola Laporan')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    <div class="officer-reservations-page" data-ajax-list>
        @if (session('success'))
            <div class="officer-report-alert is-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="officer-report-alert is-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form class="officer-reservation-filters" method="GET" action="{{ route('officer.reports.index') }}">
            <label class="officer-reservation-search">
                <span class="sr-only">Cari laporan</span>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari ID laporan, nama pelapor, atau fasilitas...">
                <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
            </label>

            <label class="officer-reservation-filter">
                <span class="sr-only">Filter status laporan</span>
                <select name="status" onchange="this.form.requestSubmit()">
                    <option value="">Status</option>
                    <option value="baru" @selected(($filters['status'] ?? '') === 'baru')>Baru</option>
                    <option value="diproses" @selected(($filters['status'] ?? '') === 'diproses')>Diproses</option>
                    <option value="selesai" @selected(($filters['status'] ?? '') === 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak</option>
                </select>
                <img class="officer-reservation-dropdown-icon" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>

            <label class="officer-reservation-filter">
                <span class="sr-only">Filter jenis laporan</span>
                <select name="jenis" onchange="this.form.requestSubmit()">
                    <option value="">Jenis</option>
                    <option value="Kerusakan/Kendala Fasilitas" @selected(($filters['jenis'] ?? '') === 'Kerusakan/Kendala Fasilitas')>Kerusakan/Kendala Fasilitas</option>
                    <option value="Kebersihan Fasilitas" @selected(($filters['jenis'] ?? '') === 'Kebersihan Fasilitas')>Kebersihan Fasilitas</option>
                    <option value="Gangguan Internet" @selected(($filters['jenis'] ?? '') === 'Gangguan Internet')>Gangguan Internet</option>
                </select>
                <img class="officer-reservation-dropdown-icon" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>

            <div class="officer-reservation-date">
                <label class="officer-reservation-date-field">
                    <span class="sr-only">Tanggal mulai</span>
                    <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" onchange="this.form.requestSubmit()">
                    <img src="{{ asset('images/officer/calendar.svg') }}" alt="" aria-hidden="true">
                </label>
                <span aria-hidden="true">–</span>
                <label class="officer-reservation-date-field">
                    <span class="sr-only">Tanggal selesai</span>
                    <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" onchange="this.form.requestSubmit()">
                    <img src="{{ asset('images/officer/calendar.svg') }}" alt="" aria-hidden="true">
                </label>
            </div>

            <label class="officer-reservation-filter officer-reservation-sort">
                <span class="sr-only">Urutkan laporan</span>
                <select name="urut" onchange="this.form.requestSubmit()">
                    <option value="terbaru" @selected(($filters['urut'] ?? 'terbaru') === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(($filters['urut'] ?? '') === 'terlama')>Terlama</option>
                </select>
                <img class="officer-reservation-dropdown-icon" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>
        </form>

        <section class="officer-reservation-table-card" aria-label="Daftar laporan kerusakan">
            <div class="officer-table-wrap">
                <table class="officer-reservations-table officer-management-table officer-report-table">
                    <colgroup>
                        <col class="officer-report-col-id">
                        <col class="officer-report-col-reporter">
                        <col class="officer-report-col-facility">
                        <col class="officer-report-col-date">
                        <col class="officer-report-col-type">
                        <col class="officer-report-col-status">
                        <col class="officer-report-col-action">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">ID Laporan</th>
                            <th scope="col">Pelapor</th>
                            <th scope="col">Fasilitas</th>
                            <th scope="col">Tanggal/Waktu</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Status</th>
                            <th scope="col"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            @php
                                [$reportStatusLabel, $reportStatusClass] = match ($report->status_laporan) {
                                    'baru' => ['Baru', 'is-new'],
                                    'diproses' => ['Diproses', 'is-processing'],
                                    'selesai' => ['Selesai', 'is-done'],
                                    'ditolak' => ['Ditolak', 'is-rejected'],
                                    default => ['Tidak diketahui', 'is-cancelled'],
                                };
                            @endphp
                            <tr>
                                <td class="officer-report-id">LP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $report->reporter?->name ?? 'Anonim' }}</td>
                                <td>
                                    <span class="officer-facility-name">{{ $report->facility?->nama_fasilitas ?? 'Fasilitas tidak diketahui' }}</span>
                                    <small>{{ $report->facility?->lokasi ?? '—' }}</small>
                                </td>
                                <td>
                                    <span>{{ $report->created_at->translatedFormat('d M Y') }}</span>
                                    <small>{{ $report->created_at->format('H:i') }}</small>
                                </td>
                                <td class="officer-report-type">{{ $report->kategori_laporan }}</td>
                                <td><span class="officer-report-status {{ $reportStatusClass }}">{{ $reportStatusLabel }}</span></td>
                                <td>
                                    <a class="officer-detail-button" href="{{ route('officer.reports.show', $report) }}">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="officer-empty-row">
                                <td colspan="7">Tidak ada laporan yang cocok dengan filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="officer-reservation-pagination">{{ $reports->links('vendor.pagination.officer-reservations') }}</div>
            @endif
        </section>
    </div>
@endsection
