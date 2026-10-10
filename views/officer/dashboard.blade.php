@extends('layouts.officer-dashboard')

@section('title', 'Dasbor Petugas')
@section('header_title', 'Dasbor')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>
    <section class="officer-stats" aria-label="Ringkasan operasional">
        <article class="officer-stat">
            <h2>Reservasi Menunggu</h2>
            <p><strong>{{ $pendingReservationsCount }}</strong><span>reservasi</span></p>
        </article>
        <article class="officer-stat">
            <h2>Laporan Masuk</h2>
            <p><strong>{{ $incomingReportsCount }}</strong><span>laporan</span></p>
        </article>
        <article class="officer-stat">
            <h2>Dalam Perbaikan</h2>
            <p><strong>{{ $facilitiesInRepairCount }}</strong><span>fasilitas</span></p>
        </article>
    </section>

    <section class="officer-queue" id="reservation-queue" aria-labelledby="reservation-queue-title">
        <header class="officer-queue-header">
            <h2 id="reservation-queue-title">Antrian Reservasi</h2>
            <a href="{{ route('officer.reservations.index') }}">Lihat Semua</a>
        </header>

        <div class="officer-table-wrap">
            <table class="officer-reservations-table">
                <thead>
                    <tr>
                        <th scope="col">ID Reservasi</th>
                        <th scope="col">Pemohon</th>
                        <th scope="col">Fasilitas</th>
                        <th scope="col">Tanggal/Waktu</th>
                        <th scope="col">Tujuan</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingReservations as $reservation)
                        <tr>
                            <td class="officer-reservation-id">RSV-{{ $reservation->start_time->format('Ymd') }}-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $reservation->user?->name ?? 'Pengguna' }}</td>
                            <td>
                                <span class="officer-facility-name">{{ $reservation->facility?->nama_fasilitas ?? 'Fasilitas dihapus' }}</span>
                                @if ($reservation->facility?->lokasi)
                                    <small>{{ $reservation->facility->lokasi }}</small>
                                @endif
                            </td>
                            <td>
                                <span>{{ $reservation->start_time->translatedFormat('d M Y') }}</span>
                                <small>{{ $reservation->start_time->format('H:i') }}–{{ $reservation->end_time->format('H:i') }}</small>
                            </td>
                            <td class="officer-purpose" title="{{ $reservation->tujuan_penggunaan }}">{{ \Illuminate\Support\Str::limit($reservation->tujuan_penggunaan, 28) }}</td>
                            <td><span class="officer-status-pending">Menunggu</span></td>
                            <td><a class="officer-detail-button" href="{{ route('officer.reservations.show', ['reservation' => $reservation, 'from' => 'dashboard']) }}" aria-label="Detail reservasi {{ $reservation->id }}">Detail</a></td>
                        </tr>
                    @empty
                        <tr class="officer-empty-row">
                            <td colspan="7">Belum ada reservasi yang menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
