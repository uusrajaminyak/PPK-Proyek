@extends('layouts.admin')

@section('title', 'Detail Rekap')
@section('header_title', 'Rekap')

@section('content')
    <section class="admin-recap-detail-page" aria-labelledby="recap-detail-title" data-ajax-list>
        <div class="recap-detail-toolbar">
            <a class="recap-detail-back" href="{{ route('admin.recap.index', ['month' => $selectedMonth, 'year' => $selectedYear]) }}">
                <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true">
                Kembali
            </a>

            <form action="{{ route('admin.recap.facility', $facilityId) }}" method="GET" class="recap-detail-period" aria-label="Periode rekap">
                <label class="recap-select">
                    <span class="sr-only">Bulan</span>
                <select name="month" onchange="this.form.requestSubmit()">
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>
                <label class="recap-select recap-year-select">
                    <span class="sr-only">Tahun</span>
                <select name="year" onchange="this.form.requestSubmit()">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                </label>
            </form>
        </div>

        <article class="recap-detail-card">
            <header class="recap-detail-heading">
                <div>
                    <h2 id="recap-detail-title">{{ $facility?->nama_fasilitas ?? 'Semua Fasilitas' }}</h2>
                    <p>Ringkasan {{ $months[$selectedMonth] }} {{ $selectedYear }}</p>
                </div>
            </header>

            <div class="recap-detail-metrics">
                <section class="recap-detail-metric" aria-labelledby="recap-reservations-title">
                    <h3 id="recap-reservations-title">Reservasi</h3>
                    <p class="recap-detail-total">{{ $totalReservations }}</p>
                    <p class="recap-detail-caption">Total reservasi</p>
                    <div class="recap-detail-secondary">
                        <span>Disetujui</span>
                        <strong>{{ $approvedReservations }}</strong>
                    </div>
                </section>

                <section class="recap-detail-metric" aria-labelledby="recap-reports-title">
                    <h3 id="recap-reports-title">Laporan Fasilitas</h3>
                    <p class="recap-detail-total">{{ $totalReports }}</p>
                    <p class="recap-detail-caption">Total laporan</p>
                    <div class="recap-detail-secondary">
                        <span>Selesai</span>
                        <strong>{{ $resolvedReports }}</strong>
                    </div>
                </section>
            </div>

            <div class="recap-detail-history">
                <section class="recap-history-panel" aria-labelledby="recap-reservation-history-title">
                    <header class="recap-history-heading">
                        <h3 id="recap-reservation-history-title">Riwayat Reservasi</h3>
                        <span>{{ $reservations->total() }} data</span>
                    </header>

                    <div class="recap-history-list">
                        @forelse ($reservations as $reservation)
                            @php
                                $reservationStatus = match ($reservation->status_reservasi) {
                                    'approved' => ['Disetujui', 'is-approved'],
                                    'pending' => ['Menunggu', 'is-pending'],
                                    'cancelled_by_user' => ['Dibatalkan', 'is-cancelled'],
                                    'cancelled_by_officer' => ['Ditolak', 'is-rejected'],
                                    default => ['—', ''],
                                };
                            @endphp
                            <article class="recap-history-item">
                                <div class="recap-history-copy">
                                    <strong>{{ $reservation->facility->nama_fasilitas ?? 'Fasilitas' }}</strong>
                                    <span>{{ $reservation->user->name ?? 'Pengguna' }} · {{ $reservation->start_time?->format('d M Y, H:i') ?? 'Waktu tidak tersedia' }}</span>
                                    <span class="recap-history-detail">{{ $reservation->tujuan_penggunaan ?: 'Tujuan tidak dicantumkan' }}</span>
                                </div>
                                <span class="recap-history-status {{ $reservationStatus[1] }}">{{ $reservationStatus[0] }}</span>
                            </article>
                        @empty
                            <p class="recap-history-empty">Belum ada reservasi pada periode ini.</p>
                        @endforelse
                    </div>

                    @if ($reservations->hasPages())
                        <nav class="recap-history-pagination" aria-label="Navigasi riwayat reservasi">
                            @if ($reservations->onFirstPage())
                                <span aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $reservations->previousPageUrl() }}">Sebelumnya</a>
                            @endif
                            <span>{{ $reservations->currentPage() }} / {{ $reservations->lastPage() }}</span>
                            @if ($reservations->hasMorePages())
                                <a href="{{ $reservations->nextPageUrl() }}">Berikutnya</a>
                            @else
                                <span aria-disabled="true">Berikutnya</span>
                            @endif
                        </nav>
                    @endif
                </section>

                <section class="recap-history-panel" aria-labelledby="recap-report-history-title">
                    <header class="recap-history-heading">
                        <h3 id="recap-report-history-title">Riwayat Laporan</h3>
                        <span>{{ $reports->total() }} data</span>
                    </header>

                    <div class="recap-history-list">
                        @forelse ($reports as $report)
                            @php
                                $reportStatus = match ($report->status_laporan) {
                                    'selesai' => ['Selesai', 'is-approved'],
                                    'diproses' => ['Diproses', 'is-pending'],
                                    'baru' => ['Baru', 'is-cancelled'],
                                    'ditolak' => ['Ditolak', 'is-rejected'],
                                    default => [ucfirst($report->status_laporan ?? '—'), ''],
                                };
                            @endphp
                            <article class="recap-history-item">
                                <div class="recap-history-copy">
                                    <strong>{{ $report->facility->nama_fasilitas ?? 'Fasilitas' }}</strong>
                                    <span>{{ $report->reporter->name ?? 'Pengguna' }} · {{ $report->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}</span>
                                    <span class="recap-history-detail">{{ $report->kategori_laporan ?: 'Laporan fasilitas' }} · {{ $report->deskripsi ?: 'Tanpa deskripsi' }}</span>
                                </div>
                                <span class="recap-history-status {{ $reportStatus[1] }}">{{ $reportStatus[0] }}</span>
                            </article>
                        @empty
                            <p class="recap-history-empty">Belum ada laporan pada periode ini.</p>
                        @endforelse
                    </div>

                    @if ($reports->hasPages())
                        <nav class="recap-history-pagination" aria-label="Navigasi riwayat laporan">
                            @if ($reports->onFirstPage())
                                <span aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $reports->previousPageUrl() }}">Sebelumnya</a>
                            @endif
                            <span>{{ $reports->currentPage() }} / {{ $reports->lastPage() }}</span>
                            @if ($reports->hasMorePages())
                                <a href="{{ $reports->nextPageUrl() }}">Berikutnya</a>
                            @else
                                <span aria-disabled="true">Berikutnya</span>
                            @endif
                        </nav>
                    @endif
                </section>
            </div>
        </article>
    </section>
@endsection
