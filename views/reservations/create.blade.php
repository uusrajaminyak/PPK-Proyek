<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $facility->nama_fasilitas }} - Fasilita UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts('plus-jakarta-sans')
</head>
<body class="detail-page">

    {{-- Watermark Siluet --}}
    <img class="page-watermark" src="{{ asset('images/landing/undip-watermark.png') }}" alt="" aria-hidden="true">

    {{-- Main Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main class="detail-main">
        <a class="back-link" href="{{ route('facilities.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>

        {{-- Galeri Foto --}}
        <div class="gallery-container">
            <div class="hero-image-wrapper">
                <img id="mainHeroImg" src="{{ asset('images/landing/landing-hero.webp') }}" alt="{{ $facility->nama_fasilitas }}">
            </div>
            <div class="thumbnail-gallery">
                <div class="thumbnail-item active" onclick="switchHero(this, '{{ asset('images/landing/landing-hero.webp') }}')">
                    <img src="{{ asset('images/landing/landing-hero.webp') }}" alt="">
                </div>
                <div class="thumbnail-item" onclick="switchHero(this, '{{ asset('images/landing/landing-hero.webp') }}')">
                    <img src="{{ asset('images/landing/landing-hero.webp') }}" alt="">
                </div>
                <div class="thumbnail-item" onclick="switchHero(this, '{{ asset('images/landing/landing-hero.webp') }}')">
                    <img src="{{ asset('images/landing/landing-hero.webp') }}" alt="">
                </div>
            </div>
        </div>

        {{-- 2 Kolom: Info Ruangan & Card Reservasi --}}
        <div class="booking-layout-grid">
            <div class="facility-info">
                <h1>{{ $facility->nama_fasilitas }}</h1>
                <div class="facility-meta">
                    <span>📍 {{ $facility->lokasi }}</span>
                    <span>👥 Kapasitas {{ number_format($facility->kapasitas) }} orang</span>
                </div>
                <div class="facility-description">
                    <h3>Fasilitas</h3>
                    <p>
                        {{ $facility->deskripsi ?: 'Ruangan dilengkapi dengan fasilitas standar perkuliahan, pendingin ruangan (AC), proyektor LCD, papan tulis, serta meja dan kursi ergonomis.' }}
                    </p>
                </div>
            </div>

            <div class="reservation-card">
                <h2>Reservasi</h2>

                @if ($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:0.65rem 0.9rem;margin-bottom:1rem;font-size:0.8rem;color:#991b1b;">
                        @foreach ($errors->all() as $error)
                            <p style="margin:0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
                    @csrf
                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">

                    <div class="form-group">
                        <label class="form-label">Tanggal &amp; Waktu Reservasi</label>
                        <div class="date-time-inputs">
                            <input type="text" name="tanggal_display" id="inputTanggalDisplay" class="input-date" placeholder="DD/BB/TTTT" readonly required>
                            <input type="hidden" name="tanggal" id="inputTanggalReal" required>
                            <input type="text" name="slot_start" id="inputSlotStart" class="input-time" placeholder="07:00" readonly required>
                            <span class="time-separator">&rarr;</span>
                            <input type="text" name="slot_end" id="inputSlotEnd" class="input-time" placeholder="07:30" readonly required>
                        </div>
                        <small class="input-helper">(Anda dapat mengecek jadwal yang tersedia di bawah)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tujuan_penggunaan">Tujuan Penggunaan</label>
                        <input type="text" name="tujuan_penggunaan" id="tujuan_penggunaan" class="input-purpose"
                               placeholder="Contoh: Rapat Bulanan BEM" required oninput="validateForm()">
                    </div>

                    <button type="button" id="btnSubmitReservation" class="btn-submit-reservation" onclick="openConfirmModal()" disabled>
                        Buat Reservasi
                    </button>
                </form>
            </div>
        </div>

        {{-- Jadwal Ketersediaan --}}
        <section class="schedule-section">
            <h2>Jadwal Ketersediaan</h2>

            <div class="schedule-nav">
                {{-- Selector Bulan (Fast Jump) --}}
                <div class="month-selector">
                    <button type="button" class="nav-month-btn" id="btnPrevMonth" onclick="navigateSchedule('prev_month')" title="Bulan Sebelumnya" disabled>&larr;</button>
                    <span class="current-month-text" id="displayMonthText">Memuat...</span>
                    <button type="button" class="nav-month-btn" id="btnNextMonth" onclick="navigateSchedule('next_month')" title="Bulan Selanjutnya">&rarr;</button>
                </div>

                {{-- Slider Hari (Precision Step) --}}
                <div class="day-slider-nav">
                    <button type="button" class="nav-day-btn" id="btnPrevDays" onclick="navigateSchedule('prev_days')" title="Hari Sebelumnya" disabled>&lsaquo;</button>
                    <span class="day-slider-label" id="displayDaysRange">Memuat hari...</span>
                    <button type="button" class="nav-day-btn" id="btnNextDays" onclick="navigateSchedule('next_days')" title="Hari Selanjutnya">&rsaquo;</button>
                </div>
            </div>

            <div class="schedule-container" style="position: relative; min-height: 320px;">
                <div id="scheduleLoading" class="schedule-loading-overlay" style="display: none;">
                    <div class="schedule-spinner"></div>
                    <span>Memuat data jadwal...</span>
                </div>

                <table class="timetable" id="scheduleTable">
                    <thead id="scheduleThead">
                        {{-- Diisi secara dinamis via AJAX --}}
                    </thead>
                    <tbody id="scheduleTbody">
                        {{-- Diisi secara dinamis via AJAX --}}
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Modal Konfirmasi Reservasi --}}
        <div id="confirmModal" class="modal-backdrop" style="display: none;">
            <div class="modal-card">
                <h3>Konfirmasi Reservasi</h3>
                <p class="modal-subtitle">Mohon periksa kembali detail reservasi Anda sebelum mengajukan:</p>
                
                <div class="confirm-summary-box">
                    <div class="summary-row">
                        <span class="summary-label">Fasilitas</span>
                        <span class="summary-val">{{ $facility->nama_fasilitas }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Lokasi</span>
                        <span class="summary-val">{{ $facility->lokasi }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tanggal</span>
                        <span class="summary-val" id="modalConfirmDate">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Waktu</span>
                        <span class="summary-val" id="modalConfirmTime">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tujuan</span>
                        <span class="summary-val" id="modalConfirmPurpose">-</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-modal-cancel" onclick="closeConfirmModal()">Cek Lagi</button>
                    <button type="button" class="btn-modal-submit" id="btnConfirmSubmit" onclick="submitReservationReal()">Ya, Ajukan Reservasi</button>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <p>Fasilita UNDIP &mdash; Sistem Reservasi &amp; Pelaporan Fasilitas Kampus Universitas Diponegoro &copy; 2026</p>
    </footer>

    {{-- Script Interaktivitas Jadwal & AJAX --}}
    <script>
        const FACILITY_ID = {{ $facility->id }};
        const SCHEDULE_URL = "{{ route('reservations.schedule', $facility->id) }}";

        let currentScheduleData = null;
        let selectedSlots = [];

        function toggleUserDropdown(e) {
            e.stopPropagation();
            const menu = document.getElementById('userMenu');
            if (menu) menu.classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            if (menu && !menu.contains(e.target)) menu.classList.remove('show');
        });

        function switchHero(thumbElement, imgSrc) {
            document.querySelectorAll('.thumbnail-item').forEach(el => el.classList.remove('active'));
            thumbElement.classList.add('active');
            document.getElementById('mainHeroImg').src = imgSrc;
        }

         // Fetch & Render Jadwal via AJAX
        async function fetchSchedule(startDate = null) {
            const loadingOverlay = document.getElementById('scheduleLoading');
            if (loadingOverlay) loadingOverlay.style.display = 'flex';

            let url = SCHEDULE_URL;
            if (startDate) {
                url += '?start_date=' + encodeURIComponent(startDate);
            }

            try {
                const response = await fetch(url, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat jadwal');
                const data = await response.json();
                currentScheduleData = data;
                renderSchedule(data);
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat memuat jadwal.');
            } finally {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
            }
        }

        function renderSchedule(data) {
            document.getElementById('displayMonthText').innerText = data.month_label;
            document.getElementById('btnPrevMonth').disabled = !data.can_prev_month;
            document.getElementById('btnPrevDays').disabled = !data.can_prev_days;

            if (data.week_days.length > 0) {
                const firstDay = data.week_days[0];
                const lastDay = data.week_days[data.week_days.length - 1];
                document.getElementById('displayDaysRange').innerText = firstDay.label + ' - ' + lastDay.label;
            }

            const thead = document.getElementById('scheduleThead');
            let headerHtml = '<tr><th></th>';
            data.week_days.forEach(function(day) {
                let cls = '';
                if (day.is_today) cls = 'is-today';
                else if (day.is_past) cls = 'is-past';
                headerHtml += '<th class="' + cls + '">' + day.label + '</th>';
            });
            headerHtml += '</tr>';
            thead.innerHTML = headerHtml;

            const tbody = document.getElementById('scheduleTbody');
            let bodyHtml = '';
            const times = data.time_options;
            const bookedList = data.booked;
            const now = new Date();

            for (let i = 0; i < times.length - 1; i++) {
                const startTime = times[i];
                const nextTime = times[i + 1];
                const displayTime = startTime.replace(':', '.');

                bodyHtml += '<tr><td class="time-col">' + displayTime + '</td>';

                data.week_days.forEach(function(day) {
                    const slotStartStr = day.date_ymd + ' ' + startTime;
                    const slotEndStr = day.date_ymd + ' ' + nextTime;
                    const slotStartDate = new Date(day.date_ymd + 'T' + startTime + ':00');
                    const isPast = slotStartDate < now;

                    let isBooked = false;
                    if (!isPast) {
                        for (let b of bookedList) {
                            if (b.start < slotEndStr && b.end > slotStartStr) {
                                isBooked = true;
                                break;
                            }
                        }
                    }

                    const isSelected = selectedSlots.some(function(s) {
                        return s.dateYmd === day.date_ymd && s.startTime === startTime;
                    });

                    if (isPast) {
                        bodyHtml += '<td><button type="button" class="slot-btn past" disabled>Lewat</button></td>';
                    } else if (isBooked) {
                        bodyHtml += '<td><button type="button" class="slot-btn booked" disabled>Booked</button></td>';
                    } else if (isSelected) {
                        bodyHtml += '<td><button type="button" class="slot-btn available selected" data-date-ymd="' + day.date_ymd + '" data-date-dmy="' + day.date_dmy + '" data-start="' + startTime + '" data-end="' + nextTime + '" onclick="selectSlot(this)">Dipilih</button></td>';
                    } else {
                        bodyHtml += '<td><button type="button" class="slot-btn available" data-date-ymd="' + day.date_ymd + '" data-date-dmy="' + day.date_dmy + '" data-start="' + startTime + '" data-end="' + nextTime + '" onclick="selectSlot(this)">Tersedia</button></td>';
                    }
                });

                bodyHtml += '</tr>';
            }

            tbody.innerHTML = bodyHtml;
        }

        function navigateSchedule(action) {
            if (!currentScheduleData) return;

            if (action === 'prev_month' && currentScheduleData.can_prev_month) {
                fetchSchedule(currentScheduleData.prev_month_date);
            } else if (action === 'next_month') {
                fetchSchedule(currentScheduleData.next_month_date);
            } else if (action === 'prev_days' && currentScheduleData.can_prev_days) {
                fetchSchedule(currentScheduleData.prev_week_date);
            } else if (action === 'next_days') {
                fetchSchedule(currentScheduleData.next_week_date);
            }
        }

                function selectSlot(button) {
            const dateYmd = button.getAttribute('data-date-ymd');
            const dateDmy = button.getAttribute('data-date-dmy');
            const startTime = button.getAttribute('data-start');
            const endTime = button.getAttribute('data-end');

            // 1. Jika klik di tanggal yang berbeda, reset total
            if (selectedSlots.length > 0 && selectedSlots[0].dateYmd !== dateYmd) {
                resetSelectedSlots();
            }

            // 2. KASUS A: Belum ada slot terpilih
            if (selectedSlots.length === 0) {
                markSlotSelected(button, { dateYmd, dateDmy, startTime, endTime, element: button });
            } 
            // 3. KASUS B: Slot yang diklik sudah berstatus terpilih (User ingin unselect / memperkecil rentang)
            else if (selectedSlots.some(s => s.startTime === startTime)) {
                if (selectedSlots.length === 1) {
                    resetSelectedSlots();
                } else {
                    const firstSlot = selectedSlots[0];
                    const lastSlot = selectedSlots[selectedSlots.length - 1];

                    // Jika klik slot paling atas -> kurangi dari atas
                    if (startTime === firstSlot.startTime) {
                        unmarkSlotSelected(button, startTime);
                    }
                    // Jika klik slot paling bawah -> kurangi dari bawah
                    else if (startTime === lastSlot.startTime) {
                        unmarkSlotSelected(button, startTime);
                    }
                    // Jika klik di tengah-tengah -> potong sampai slot tersebut
                    else {
                        const cutIndex = selectedSlots.findIndex(s => s.startTime === startTime);
                        const slotsToRemove = selectedSlots.slice(cutIndex + 1);
                        slotsToRemove.forEach(s => unmarkSlotSelected(s.element, s.startTime));
                    }
                }
            } 
            // 4. KASUS C: Menambah slot baru (bisa klik bersebelahan satu-satu ATAU klik lompat range)
            else {
                const allDayButtons = Array.from(document.querySelectorAll('button.slot-btn[data-date-ymd="' + dateYmd + '"]'));

                // Cari batas waktu terendah dan tertinggi jika slot ini digabung
                const existingStarts = selectedSlots.map(s => s.startTime);
                existingStarts.push(startTime);
                existingStarts.sort();

                const minStart = existingStarts[0];
                const maxStart = existingStarts[existingStarts.length - 1];

                // Ambil semua tombol slot di antara minStart s/d maxStart
                const inRangeButtons = allDayButtons.filter(btn => {
                    const st = btn.getAttribute('data-start');
                    return st >= minStart && st <= maxStart;
                });

                // Cek apakah ada jadwal bentrok (booked atau past)
                const hasConflict = inRangeButtons.some(btn => btn.classList.contains('booked') || btn.classList.contains('past'));

                if (hasConflict) {
                    alert('Rentang waktu yang dipilih terhalang oleh jadwal yang sudah dipesan atau telah lewat.');
                } else {
                    resetSelectedSlots();
                    inRangeButtons.forEach(btn => {
                        markSlotSelected(btn, {
                            dateYmd: btn.getAttribute('data-date-ymd'),
                            dateDmy: btn.getAttribute('data-date-dmy'),
                            startTime: btn.getAttribute('data-start'),
                            endTime: btn.getAttribute('data-end'),
                            element: btn
                        });
                    });
                }
            }

            selectedSlots.sort((a, b) => a.startTime.localeCompare(b.startTime));
            updateFormInputs();
            validateForm();
        }

        function markSlotSelected(btn, slotData) {
            btn.classList.add('selected');
            btn.innerText = 'Dipilih';
            selectedSlots.push(slotData);
        }

        function unmarkSlotSelected(btn, startTime) {
            if (btn) {
                btn.classList.remove('selected');
                btn.innerText = 'Tersedia';
            }
            selectedSlots = selectedSlots.filter(s => s.startTime !== startTime);
        }

        function resetSelectedSlots() {
            selectedSlots.forEach(s => {
                if (s.element) {
                    s.element.classList.remove('selected');
                    s.element.innerText = 'Tersedia';
                }
            });
            document.querySelectorAll('.slot-btn.selected').forEach(btn => {
                btn.classList.remove('selected');
                btn.innerText = 'Tersedia';
            });
            selectedSlots = [];
        }

        function updateFormInputs() {
            const inputDateDisplay = document.getElementById('inputTanggalDisplay');
            const inputDateReal = document.getElementById('inputTanggalReal');
            const inputSlotStart = document.getElementById('inputSlotStart');
            const inputSlotEnd = document.getElementById('inputSlotEnd');

            if (selectedSlots.length > 0) {
                const firstSlot = selectedSlots[0];
                const lastSlot = selectedSlots[selectedSlots.length - 1];

                inputDateDisplay.value = firstSlot.dateDmy;
                inputDateReal.value = firstSlot.dateYmd;
                inputSlotStart.value = firstSlot.startTime;
                inputSlotEnd.value = lastSlot.endTime;
            } else {
                inputDateDisplay.value = '';
                inputDateReal.value = '';
                inputSlotStart.value = '';
                inputSlotEnd.value = '';
            }
        }

        function validateForm() {
            const purposeInput = document.getElementById('tujuan_penggunaan').value.trim();
            const btnSubmit = document.getElementById('btnSubmitReservation');

            if (selectedSlots.length > 0 && purposeInput.length > 0) {
                btnSubmit.disabled = false;
                btnSubmit.classList.add('active');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.remove('active');
            }
        }

         function openConfirmModal() {
            const dateDisplay = document.getElementById('inputTanggalDisplay').value;
            const slotStart   = document.getElementById('inputSlotStart').value;
            const slotEnd     = document.getElementById('inputSlotEnd').value;
            const purpose     = document.getElementById('tujuan_penggunaan').value.trim();

            document.getElementById('modalConfirmDate').innerText = dateDisplay;
            document.getElementById('modalConfirmTime').innerText = slotStart + ' - ' + slotEnd + ' WIB';
            document.getElementById('modalConfirmPurpose').innerText = purpose;

            const modal = document.getElementById('confirmModal');
            if (modal) { modal.classList.add('show'); modal.style.display = 'flex'; }
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            if (modal) { modal.classList.remove('show'); modal.style.display = 'none'; }
        }

        function submitReservationReal() {
            const btn = document.getElementById('btnConfirmSubmit');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Mengirim...';
            }
            document.getElementById('reservationForm').submit();
        }

        // Jalankan fetch otomatis saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', function() {
            fetchSchedule();
        });
    </script>
</body>
</html>