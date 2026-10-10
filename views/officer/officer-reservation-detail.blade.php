@extends('layouts.officer-dashboard')

@section('title', 'Detail Reservasi')
@section('header_title', match (request()->query('from')) { 'history' => 'Riwayat Reservasi', 'dashboard' => 'Dasbor', default => 'Kelola Reservasi' })

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    @php
        [$statusLabel, $statusClass] = match ($reservation->status_reservasi) {
            'pending' => ['Menunggu', 'is-pending'],
            'approved' => $reservation->end_time->isPast() ? ['Selesai', 'is-completed'] : ['Disetujui', 'is-approved'],
            'cancelled_by_user' => ['Dibatalkan Pengguna', 'is-cancelled'],
            'cancelled_by_officer' => ['Ditolak', 'is-rejected'],
            default => ['Tidak diketahui', 'is-cancelled'],
        };
    @endphp

    <div class="officer-reservation-detail-page">
        @if (session('success'))
            <div class="officer-detail-alert is-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="officer-detail-alert is-error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="officer-reservation-detail-grid">
            <section class="officer-detail-facility" aria-labelledby="officer-detail-facility-title">
                <a class="officer-detail-back" href="{{ match (request()->query('from')) { 'history' => route('officer.reservations.history'), 'dashboard' => route('officer.dashboard'), default => route('officer.reservations.index') } }}">
                    <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true"> Kembali
                </a>

                <p class="officer-detail-eyebrow">Fasilitas</p>
                <h2 id="officer-detail-facility-title">{{ $reservation->facility?->nama_fasilitas ?? 'Fasilitas dihapus' }}</h2>
                <div class="officer-detail-facility-meta">
                    <span><img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true">{{ $reservation->facility?->lokasi ?? 'Lokasi tidak tersedia' }}</span>
                    <span><img class="officer-detail-capacity-icon" src="{{ asset('images/officer/3-users.svg') }}" alt="" aria-hidden="true">Kapasitas {{ $reservation->facility?->kapasitas ?? '—' }} orang</span>
                </div>

                <div class="officer-detail-photo-gallery" aria-label="Foto fasilitas">
                    <div class="officer-detail-photo-empty" aria-hidden="true"></div>
                    <div class="officer-detail-photo-empty" aria-hidden="true"></div>
                    <div class="officer-detail-photo-empty" aria-hidden="true"></div>
                </div>

                <section class="officer-detail-block" aria-labelledby="officer-detail-schedule-title">
                    <h3 id="officer-detail-schedule-title">Jadwal Reservasi</h3>
                    <dl class="officer-detail-schedule">
                        <div><dt>Tanggal</dt><dd>{{ $reservation->start_time->translatedFormat('d F Y') }}</dd></div>
                        <div><dt>Waktu Mulai – Selesai</dt><dd>{{ $reservation->start_time->format('H:i') }} – {{ $reservation->end_time->format('H:i') }} WIB</dd></div>
                    </dl>
                </section>

                <section class="officer-detail-block" aria-labelledby="officer-detail-purpose-title">
                    <h3 id="officer-detail-purpose-title">Tujuan Penggunaan</h3>
                    <p class="officer-detail-purpose">{{ $reservation->tujuan_penggunaan }}</p>
                </section>
            </section>

            <div class="officer-detail-divider" aria-hidden="true"></div>

            <section class="officer-detail-applicant" aria-labelledby="officer-detail-title">
                <div class="officer-detail-applicant-content">
                <header class="officer-detail-heading">
                    <div>
                        <h2 id="officer-detail-title">Detail Reservasi</h2>
                        <p>RSV-{{ $reservation->start_time->format('Ymd') }}-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="officer-reservation-status {{ $statusClass }}">{{ $statusLabel }}</span>
                </header>

                <div class="officer-detail-applicant-data">
                    <h3>Data Pemohon</h3>
                    <dl>
                        <div><dt>Nama Pemohon</dt><dd>{{ $reservation->user?->name ?? 'Pengguna' }}</dd></div>
                        <div><dt>Jurusan</dt><dd>{{ $reservation->user?->program_studi ?? $reservation->user?->fakultas ?? '—' }}</dd></div>
                        <div><dt>No. WhatsApp</dt><dd>{{ $reservation->user?->no_telepon ?? '—' }}</dd></div>
                        <div><dt>Email</dt><dd>{{ $reservation->user?->email ?? '—' }}</dd></div>
                    </dl>
                </div>

                @if ($reservation->status_reservasi === 'pending')
                    <div class="officer-detail-actions">
                        <button class="officer-detail-action is-reject" type="button" data-open-rejection-dialog>Tolak Reservasi</button>
                        <form method="POST" action="{{ route('officer.reservations.update-status', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="from" value="{{ request()->query('from') }}">
                            <input type="hidden" name="status_reservasi" value="approved">
                            <button class="officer-detail-action is-approve" type="submit">Setujui Reservasi</button>
                        </form>
                    </div>
                @elseif ($reservation->status_reservasi === 'cancelled_by_officer')
                    <div class="officer-detail-applicant-data officer-detail-rejection-data">
                        <h3>Alasan Penolakan</h3>
                        <p class="officer-detail-purpose">{{ $reservation->alasan_pembatalan ?: 'Tidak ada alasan penolakan yang dicantumkan.' }}</p>
                    </div>
                @endif
                </div>
            </section>
        </div>

        <dialog class="officer-rejection-dialog" aria-labelledby="officer-rejection-title" data-rejection-dialog>
            <form method="POST" action="{{ route('officer.reservations.update-status', $reservation) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_reservasi" value="cancelled_by_officer">
                <input type="hidden" name="from" value="{{ request()->query('from') }}">
                <button class="officer-rejection-close" type="button" aria-label="Tutup" data-close-rejection-dialog>&times;</button>
                <h2 id="officer-rejection-title">Alasan Penolakan</h2>
                <label class="sr-only" for="officer-rejection-reason">Alasan menolak pengajuan reservasi</label>
                <textarea id="officer-rejection-reason" name="alasan_pembatalan" placeholder="Masukkan alasan menolak pengajuan reservasi" maxlength="1000" required></textarea>
                <button class="officer-rejection-submit" type="submit">Tolak Reservasi</button>
            </form>
        </dialog>
    </div>
@endsection
