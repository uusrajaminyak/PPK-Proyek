@extends('layouts.admin-dashboard')

@section('content')
    <section class="admin-kpi-grid" aria-label="Ringkasan">
        <article class="admin-kpi-card admin-kpi-card--split">
            <h2>Totalitas Fasilitas</h2>
            <dl class="admin-kpi-split">
                <div><dt>Aktif:</dt><dd>{{ $aktif }}</dd></div>
                <div><dt>Dalam Perbaikan:</dt><dd>{{ $perbaikan }}</dd></div>
            </dl>
        </article>

        <article class="admin-kpi-card">
            <h2>Reservasi Pending</h2>
            <p class="admin-kpi-value">{{ $reservasiMenunggu }}</p>
        </article>

        <article class="admin-kpi-card admin-kpi-card--split">
            <h2>Laporan Kerusakan</h2>
            <dl class="admin-kpi-split">
                <div><dt>Baru:</dt><dd>{{ $statusLaporan['baru'] ?? 0 }}</dd></div>
                <div><dt>Diproses:</dt><dd>{{ $statusLaporan['diproses'] ?? 0 }}</dd></div>
            </dl>
        </article>

        <article class="admin-kpi-card">
            <h2>Total Pengguna</h2>
            <p class="admin-kpi-value">{{ $totalPengguna }}</p>
        </article>

        <article class="admin-kpi-card">
            <h2>Total Petugas</h2>
            <p class="admin-kpi-value">{{ $totalPetugas }}</p>
        </article>
    </section>

    <section class="admin-recap" id="admin-recap" aria-labelledby="admin-recap-title">
        <div class="admin-recap-heading">
            <h2 id="admin-recap-title">Rekap</h2>
            <label class="admin-select-wrap admin-month-select">
                <span class="sr-only">Bulan</span>
                <select aria-label="Pilih bulan">
                    <option>Januari</option><option>Februari</option><option>Maret</option><option>April</option>
                    <option>Mei</option><option>Juni</option><option>Juli</option><option>Agustus</option>
                    <option selected>September</option><option>Oktober</option><option>November</option><option>Desember</option>
                </select>
            </label>
            <label class="admin-select-wrap admin-year-select">
                <span class="sr-only">Tahun</span>
                <select aria-label="Pilih tahun"><option>2025</option><option selected>2026</option><option>2027</option></select>
            </label>
            <div class="admin-export-formats" role="group" aria-label="Format ekspor">
                <button type="button">CSV</button><button type="button">XLSX</button><button class="is-selected" type="button">PDF</button>
            </div>
            <button class="admin-export-button" type="button">
                <img src="{{ asset('images/admin/Export.svg') }}" alt="">
                <span>Ekspor</span>
            </button>
        </div>

        <div class="admin-recap-charts">
            @foreach (['Okupansi Fasilitas', 'Frekuensi Laporan Fasilitas'] as $chartTitle)
                <article class="admin-chart-card">
                    <h3>{{ $chartTitle }}</h3>
                    <ul class="admin-hbar-chart" aria-hidden="true">
                        @foreach ([100, 84, 78, 68, 58, 53, 46] as $width)
                            <li>
                                <span class="admin-hbar-label">Lorem Ipsum</span>
                                <span class="admin-hbar-track">
                                    <span class="admin-hbar-bar" style="--bar-width: {{ $width }}%"></span>
                                    <span class="admin-hbar-value">XX</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <a class="admin-detail-button" href="{{ $loop->first ? route('admin.facilities.index') : route('reports.index') }}">Detail</a>
                </article>
            @endforeach
        </div>
    </section>
@endsection
