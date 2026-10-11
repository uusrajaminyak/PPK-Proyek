@extends('layouts.officer-dashboard')

@section('title', 'Detail Laporan')
@section('header_title', 'Kelola Laporan')
@section('body_class', 'officer-report-detail-shell')

@section('content')
    <div class="officer-main-watermark" aria-hidden="true">
        <img src="{{ asset('images/landing/undip-blue-watermark.png') }}" alt="">
    </div>

    @php
        [$reportStatusLabel, $reportStatusClass] = match ($report->status_laporan) {
            'baru' => ['Baru', 'is-new'],
            'diproses' => ['Diproses', 'is-processing'],
            'selesai' => ['Selesai', 'is-done'],
            'ditolak' => ['Ditolak', 'is-rejected'],
            default => ['Tidak diketahui', 'is-rejected'],
        };
    @endphp

    <div class="officer-reservation-detail-page officer-report-detail-page">
        @if (session('success'))
            <div class="officer-detail-alert is-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="officer-detail-alert is-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <div class="officer-reservation-detail-grid officer-report-detail-grid">
            <section class="officer-detail-facility officer-report-information" aria-labelledby="officer-report-information-title">
                <a class="officer-detail-back" href="{{ route('officer.reports.index') }}">
                    <img src="{{ asset('images/officer/return.svg') }}" alt="" aria-hidden="true"> Kembali
                </a>

                <p class="officer-detail-eyebrow">Fasilitas</p>
                <h2 id="officer-report-information-title">{{ $report->facility?->nama_fasilitas ?? 'Fasilitas dihapus' }}</h2>
                <div class="officer-detail-facility-meta">
                    <span><img src="{{ asset('images/facilities/icon-location.svg') }}" alt="" aria-hidden="true">{{ $report->facility?->lokasi ?? 'Lokasi tidak tersedia' }}</span>
                    <span><img class="officer-detail-capacity-icon" src="{{ asset('images/officer/calendar.svg') }}" alt="" aria-hidden="true">{{ $report->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <p class="officer-report-facility-current-status">
                    Status fasilitas saat ini:
                    <strong>{{ $report->facility?->getStatusLabel() ?? 'Fasilitas tidak tersedia' }}</strong>
                </p>

                <div class="officer-report-detail-photos" aria-label="Foto bukti laporan">
                    @forelse ($report->foto_paths ?? [] as $photoPath)
                        <a href="{{ asset('storage/' . $photoPath) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $photoPath) }}" alt="Foto bukti laporan">
                        </a>
                    @empty
                        <div class="officer-detail-photo-empty" aria-label="Tidak ada foto bukti"></div>
                    @endforelse
                </div>

                <section class="officer-detail-block" aria-labelledby="officer-report-type-title">
                    <h3 id="officer-report-type-title">Jenis Laporan</h3>
                    <p class="officer-detail-purpose">{{ $report->kategori_laporan }}</p>
                </section>

                <section class="officer-detail-block" aria-labelledby="officer-report-description-title">
                    <h3 id="officer-report-description-title">Deskripsi Laporan</h3>
                    <p class="officer-detail-purpose">{{ $report->deskripsi }}</p>
                </section>
            </section>

            <div class="officer-detail-divider" aria-hidden="true"></div>

            <section class="officer-detail-applicant officer-report-applicant" aria-labelledby="officer-report-detail-title">
                <div class="officer-detail-applicant-content">
                    <header class="officer-detail-heading">
                        <div>
                            <h2 id="officer-report-detail-title">Detail Laporan</h2>
                            <p>LP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span class="officer-report-status {{ $reportStatusClass }}">{{ $reportStatusLabel }}</span>
                    </header>

                    <div class="officer-detail-applicant-data officer-report-reporter-data">
                        <h3>Data Pelapor</h3>
                        <dl>
                            <div><dt>Nama Pelapor</dt><dd>{{ $report->reporter?->name ?? 'Anonim' }}</dd></div>
                            <div><dt>Fakultas/Jurusan</dt><dd>{{ $report->reporter?->program_studi ?? $report->reporter?->fakultas ?? '—' }}</dd></div>
                            <div><dt>No. WhatsApp</dt><dd>{{ $report->reporter?->no_telepon ?? '—' }}</dd></div>
                            <div><dt>Email</dt><dd>{{ $report->reporter?->email ?? '—' }}</dd></div>
                        </dl>
                    </div>

                    @if ($report->catatan_resolusi)
                        <p class="officer-report-resolution-note"><strong>Catatan Resolusi</strong>{{ $report->catatan_resolusi }}</p>
                    @endif

                    <form class="officer-report-update officer-report-detail-update" action="{{ route('officer.reports.update', $report) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label>Status Penanganan
                            <span class="officer-report-select">
                                <select name="status_laporan" required>
                                    <option value="baru" @selected(old('status_laporan', $report->status_laporan) === 'baru')>Baru</option>
                                    <option value="diproses" @selected(old('status_laporan', $report->status_laporan) === 'diproses')>Diproses</option>
                                    <option value="selesai" @selected(old('status_laporan', $report->status_laporan) === 'selesai')>Selesai</option>
                                    <option value="ditolak" @selected(old('status_laporan', $report->status_laporan) === 'ditolak')>Ditolak</option>
                                </select>
                                <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                            </span>
                        </label>
                        <label>Catatan Resolusi
                            <textarea name="catatan_resolusi" rows="3" minlength="5" maxlength="1000" placeholder="Wajib diisi jika laporan selesai atau ditolak">{{ old('catatan_resolusi', $report->catatan_resolusi) }}</textarea>
                        </label>
                        <label>Status Fasilitas
                            <span class="officer-report-select">
                                <select name="update_facility_status">
                                    <option value="keep">Tidak mengubah status saat ini</option>
                                    <option value="in_repair">Dalam Perbaikan</option>
                                    <option value="active">Aktif</option>
                                    @if (auth()->user()->isAdmin())
                                        <option value="inactive">Tidak Tersedia</option>
                                    @endif
                                </select>
                                <img src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                            </span>
                        </label>
                        <button class="officer-detail-action is-approve" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
