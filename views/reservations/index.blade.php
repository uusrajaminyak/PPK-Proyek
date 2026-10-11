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
    <main class="reservation-main" data-ajax-list>
        <h1 class="page-title">Reservasi Saya</h1>

        <div class="table-container-wrapper">
            <form class="officer-reservation-filters" method="GET" action="{{ route('reservations.index') }}">
                <label class="officer-reservation-search">
                    <span class="sr-only">Cari reservasi</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari riwayat reservasi...">
                    <img src="{{ asset('images/officer/search.svg') }}" alt="" aria-hidden="true">
                </label>

                <label class="officer-reservation-filter">
                    <span class="sr-only">Filter status reservasi</span>
                    <select name="status" onchange="this.form.requestSubmit()">
                        <option value="">Status</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu</option>
                        <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
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
                        @php
                            [$reservationStatusLabel, $reservationStatusClass] = match ($res->status_reservasi) {
                                'pending' => ['Menunggu', 'pending'],
                                'approved' => $res->end_time->isPast() ? ['Selesai', 'completed'] : ['Disetujui', 'approved'],
                                'cancelled_by_user' => ['Dibatalkan Pengguna', 'cancelled_by_user'],
                                'cancelled_by_staff' => ['Dibatalkan Petugas', 'cancelled_by_user'],
                                'cancelled_by_officer' => ['Ditolak', 'cancelled_by_officer'],
                                default => ['Tidak diketahui', 'cancelled'],
                            };
                        @endphp
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
                                <span class="badge-status badge-{{ $reservationStatusClass }}">{{ $reservationStatusLabel }}</span>
                            </div>

                            {{-- Aksi --}}
                            <div class="col-actions">
                                @if($res->canBeCancelled())
                                    <button type="button" class="btn-cancel" 
                                        onclick="openCancelModal('{{ $res->id }}', '{{ addslashes($res->facility->nama_fasilitas) }}', '{{ $res->start_time->format('d M Y, H:i') }}')">
                                        Batal
                                    </button>
                                @endif
                                <button type="button" class="btn-detail" 
                                    onclick="showDetailModal(
                                        'RSV-{{ $res->start_time->format('Ymd') }}-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}',
                                        '{{ addslashes($res->facility->nama_fasilitas) }}',
                                        '{{ addslashes($res->facility->lokasi) }}',
                                        '{{ $res->start_time->format('d F Y, H:i') }} - {{ $res->end_time->format('H:i') }} WIB',
                                        '{{ $reservationStatusLabel }}',
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

    {{-- Modal Konfirmasi Pembatalan --}}
    <div class="modal-backdrop" id="cancelModal">
        <div class="modal-card">
            <h3 style="color: #ef4444; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Batalkan Reservasi
            </h3>
            
            <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 1rem; line-height: 1.5;">
                Apakah Anda yakin ingin membatalkan reservasi untuk <strong id="cancelTargetFacility" style="color:#001348;">-</strong> pada jadwal <strong id="cancelTargetTime" style="color:#001348;">-</strong>?
            </p>

            <form id="cancelReservationForm" method="POST" action="">
                @csrf
                <div class="modal-field">
                    <label for="alasan_pembatalan" style="font-weight: 700; color: #001348;">Alasan Pembatalan <span style="color: #ef4444;">*</span></label>
                    <textarea 
                        name="alasan_pembatalan" 
                        id="alasan_pembatalan" 
                        rows="3" 
                        required 
                        placeholder="Contoh: Rapat dibatalkan / Ada perubahan jadwal perkuliahan..."
                        style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.85rem; color: #1e293b; outline: none; resize: none; font-family: inherit; margin-top: 0.35rem;"
                    ></textarea>
                </div>

                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem;">
                    <button type="button" onclick="closeCancelModal()" style="padding: 0.55rem 1.15rem; background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer;">
                        Kembali
                    </button>
                    <button type="submit" id="btnSubmitCancel" style="padding: 0.55rem 1.25rem; background: #ef4444; border: none; color: #ffffff; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: background 0.15s;">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
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

        function openCancelModal(id, facility, schedule) {
            const form = document.getElementById('cancelReservationForm');
            form.action = `/reservations/${id}/cancel`;
            document.getElementById('cancelTargetFacility').innerText = facility;
            document.getElementById('cancelTargetTime').innerText = schedule;
            document.getElementById('alasan_pembatalan').value = '';
            document.getElementById('cancelModal').classList.add('show');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.remove('show');
        }
    </script>

</body>
</html>
