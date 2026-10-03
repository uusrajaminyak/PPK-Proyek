<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f5">
    <title>Reservasi Saya — Fasilita FSM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="reservation-page">

    {{-- Watermark Siluet Kiri --}}
    <img class="page-watermark" src="{{ asset('images/landing/undip-watermark.png') }}" alt="" aria-hidden="true">

    {{-- Navbar Utama --}}
    @include('partials.navbar')

    {{-- Main Container --}}
    <main class="reservation-main">
        <h1 class="page-title">Reservasi Saya</h1>

        <div class="table-container-wrapper">
            {{-- Filter Bar --}}
            <div class="filter-bar">
                <button type="button" class="btn-filter" onclick="alert('Filter fitur segera hadir!')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    <span>Filter</span>
                </button>
            </div>

            {{-- Box Tabel --}}
            <div class="reservation-box">
                <div class="table-header-grid">
                    <span></span>
                    <span>ID Reservasi</span>
                    <span>Fasilitas</span>
                    <span>Tanggal &amp; Waktu</span>
                    <span>Status</span>
                    <span></span>
                </div>

                <div class="reservation-list">
                    @forelse($reservations as $res)
                        <div class="reservation-card-row">
                            {{-- Thumbnail Foto Generic Ruang/Gedung --}}
                            <img class="res-thumb" src="{{ asset('images/landing/landing-hero.webp') }}" alt="Foto Fasilitas">

                            {{-- ID Reservasi --}}
                            <div class="col-id">
                                RSV-{{ $res->start_time->format('Ymd') }}-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                            </div>

                            {{-- Fasilitas --}}
                            <div class="col-facility">
                                <strong>{{ $res->facility->nama_fasilitas }}</strong>
                                <small>{{ $res->facility->lokasi }}</small>
                            </div>

                            {{-- Tanggal & Waktu --}}
                            <div class="col-time">
                                <div>{{ $res->start_time->format('d F Y') }}</div>
                                <small>{{ $res->start_time->format('H:i') }} - {{ $res->end_time->format('H:i') }}</small>
                            </div>

                            {{-- Status Badge --}}
                            <div class="col-status">
                                <span class="badge-status badge-{{ $res->status_reservasi }}">
                                    {{ $res->status_reservasi === 'pending' ? 'Waiting' : $res->getStatusLabel() }}
                                </span>
                            </div>

                            {{-- Aksi --}}
                            <div class="col-actions">
                                @if($res->canBeCancelled())
                                    <form action="{{ route('reservations.cancel', $res->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                        @csrf
                                        <button type="submit" class="btn-cancel">Batal</button>
                                    </form>
                                @endif
                                <button type="button" class="btn-detail"
                                    onclick="showDetailModal(
                                        'RSV-{{ $res->start_time->format('Ymd') }}-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}',
                                        '{{ addslashes($res->facility->nama_fasilitas) }}',
                                        '{{ addslashes($res->facility->lokasi) }}',
                                        '{{ $res->start_time->format('d F Y') }} ({{ $res->start_time->format('H:i') }} - {{ $res->end_time->format('H:i') }})',
                                        '{{ $res->status_reservasi === 'pending' ? 'Waiting' : $res->getStatusLabel() }}',
                                        '{{ addslashes($res->tujuan_penggunaan) }}'
                                    )">
                                    Detail
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-reservations">
                            <p>Belum ada riwayat reservasi fasilitas.</p>
                            <a href="{{ route('facilities.index') }}" class="btn-create-first">Jelajah Fasilitas Sekarang</a>
                        </div>
                    @endforelse
                </div>

                @if($reservations->hasPages())
                    <div class="reservation-pagination">
                        {{ $reservations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Detail Modal --}}
    <div class="modal-backdrop" id="detailModal">
        <div class="modal-card">
            <h3>Detail Reservasi</h3>
            <div class="modal-field">
                <label>ID Reservasi</label>
                <div id="modalId">-</div>
            </div>
            <div class="modal-field">
                <label>Fasilitas</label>
                <div id="modalFacility">-</div>
            </div>
            <div class="modal-field">
                <label>Lokasi</label>
                <div id="modalLocation">-</div>
            </div>
            <div class="modal-field">
                <label>Jadwal</label>
                <div id="modalTime">-</div>
            </div>
            <div class="modal-field">
                <label>Status</label>
                <div id="modalStatus">-</div>
            </div>
            <div class="modal-field">
                <label>Tujuan Penggunaan</label>
                <div id="modalPurpose">-</div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>

    {{-- Footer --}}
    @include('partials.footer')

    <script>
        function showDetailModal(id, facility, loc, time, status, purpose) {
            document.getElementById('modalId').innerText = id;
            document.getElementById('modalFacility').innerText = facility;
            document.getElementById('modalLocation').innerText = loc;
            document.getElementById('modalTime').innerText = time;
            document.getElementById('modalStatus').innerText = status;
            document.getElementById('modalPurpose').innerText = purpose;
            document.getElementById('detailModal').classList.add('show');
        }
        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }
    </script>

</body>
</html>
