@extends('layouts.officer-dashboard')

@section('title', 'Riwayat Reservasi')
@section('header_title', 'Riwayat Reservasi')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    <div class="officer-reservations-page" data-ajax-list>
        <form class="officer-reservation-filters" method="GET" action="{{ route('officer.reservations.history') }}">
            <label class="officer-reservation-search">
                <span class="sr-only">Cari reservasi</span>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari berdasarkan nama pemohon, ID, atau nama fasilitas...">
                <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
            </label>

            <label class="officer-reservation-filter">
                <span class="sr-only">Filter status</span>
                <select name="status" onchange="this.form.requestSubmit()">
                    <option value="">Status</option>
                    <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Dibatalkan</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
                </select>
                <img class="officer-reservation-dropdown-icon" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>

            <label class="officer-reservation-filter">
                <span class="sr-only">Filter tipe fasilitas</span>
                <select name="tipe" onchange="this.form.requestSubmit()">
                    <option value="">Tipe</option>
                    @foreach ($facilityTypes as $facilityType)
                        <option value="{{ $facilityType }}" @selected(($filters['tipe'] ?? '') === $facilityType)>{{ $facilityType }}</option>
                    @endforeach
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
                <span class="sr-only">Urutkan reservasi</span>
                <select name="urut" onchange="this.form.requestSubmit()">
                    <option value="terbaru" @selected(($filters['urut'] ?? 'terbaru') === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(($filters['urut'] ?? '') === 'terlama')>Terlama</option>
                </select>
                <img class="officer-reservation-dropdown-icon" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>
        </form>

        <section class="officer-reservation-table-card" aria-label="Daftar reservasi">
            <div class="officer-table-wrap">
                <table class="officer-reservations-table officer-management-table">
                    <colgroup>
                        <col class="officer-col-reservation-id">
                        <col class="officer-col-applicant">
                        <col class="officer-col-facility">
                        <col class="officer-col-date">
                        <col class="officer-col-purpose">
                        <col class="officer-col-status">
                        <col class="officer-col-action">
                    </colgroup>
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
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td class="officer-reservation-id">RSV-{{ $reservation->start_time->format('Ymd') }}-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $reservation->user?->name ?? 'Pengguna' }}</td>
                                <td>
                                    <span class="officer-facility-name">{{ $reservation->facility?->nama_fasilitas ?? 'Fasilitas dihapus' }}</span>
                                    <small>{{ $reservation->facility?->tipe ?? '—' }}</small>
                                </td>
                                <td>
                                    <span>{{ $reservation->start_time->translatedFormat('d M Y') }}</span>
                                    <small>{{ $reservation->start_time->format('H:i') }}–{{ $reservation->end_time->format('H:i') }}</small>
                                </td>
                                <td class="officer-purpose" title="{{ $reservation->tujuan_penggunaan }}">{{ \Illuminate\Support\Str::limit($reservation->tujuan_penggunaan, 32) }}</td>
                                <td>
                                    @php
                                        [$statusLabel, $statusClass] = match ($reservation->status_reservasi) {
                                            'approved' => ['Selesai', 'is-completed'],
                                            'cancelled_by_user' => ['Dibatalkan', 'is-cancelled'],
                                            'cancelled_by_officer' => ['Ditolak', 'is-rejected'],
                                            default => ['Tidak diketahui', 'is-cancelled'],
                                        };
                                    @endphp
                                    <span class="officer-reservation-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td><a class="officer-detail-button" href="{{ route('officer.reservations.show', ['reservation' => $reservation, 'from' => 'history']) }}" aria-label="Detail reservasi {{ $reservation->id }}">Detail</a></td>
                            </tr>
                        @empty
                            <tr class="officer-empty-row">
                                <td colspan="7">Belum ada reservasi selesai, dibatalkan, atau ditolak yang cocok dengan filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reservations->hasPages())
                <div class="officer-reservation-pagination">{{ $reservations->links('vendor.pagination.officer-reservations') }}</div>
            @endif
        </section>
    </div>
@endsection
