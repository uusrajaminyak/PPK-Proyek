@extends('layouts.admin')

@section('header_title', 'Dasbor')

@section('content')
<div class="admin-dashboard-content">
    <section class="admin-summary" aria-label="Ringkasan administrasi">
        <article class="admin-summary-card">
            <h2>Totalitas Fasilitas</h2>
            <div class="admin-summary-breakdown">
                <p><span>Aktif</span><strong>{{ $aktif }}</strong></p>
                <p><span>Dalam Perbaikan</span><strong>{{ $perbaikan }}</strong></p>
            </div>
        </article>
        <article class="admin-summary-card">
            <h2>Reservasi Menunggu</h2>
            <p class="admin-summary-value"><strong>{{ $reservasiMenunggu }}</strong><span>reservasi</span></p>
        </article>
        <article class="admin-summary-card">
            <h2>Laporan Kerusakan</h2>
            <div class="admin-summary-breakdown">
                <p><span>Baru</span><strong>{{ $statusLaporan['baru'] }}</strong></p>
                <p><span>Diproses</span><strong>{{ $statusLaporan['diproses'] }}</strong></p>
            </div>
        </article>
        <article class="admin-summary-card">
            <h2>Total Pengguna</h2>
            <p class="admin-summary-value"><strong>{{ $totalPengguna }}</strong><span>pengguna</span></p>
        </article>
        <article class="admin-summary-card">
            <h2>Total Petugas</h2>
            <p class="admin-summary-value"><strong>{{ $totalPetugas }}</strong><span>petugas</span></p>
        </article>
    </section>

    <section class="admin-recap" aria-labelledby="admin-recap-title">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="admin-recap-header">
            <div class="admin-recap-title-controls">
                <h2 id="admin-recap-title">Rekap</h2>
                <div class="admin-recap-filters">
                    <label class="admin-recap-filter">
                        <select name="month" aria-label="Bulan rekap" onchange="this.form.submit()">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>
                    <label class="admin-recap-filter">
                        <select name="year" aria-label="Tahun rekap" onchange="this.form.submit()">
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
                    </label>
                </div>
            </div>
            <div class="admin-recap-actions">
                <input type="hidden" name="format" id="admin-dashboard-export-format" value="CSV">
                <div class="admin-export-formats" role="group" aria-label="Pilih format ekspor">
                    @foreach(['CSV', 'XLSX', 'PDF'] as $format)
                        <button type="button" class="admin-dashboard-format-option {{ $format === 'CSV' ? 'is-selected' : '' }}" data-format="{{ $format }}" aria-pressed="{{ $format === 'CSV' ? 'true' : 'false' }}" onclick="setAdminDashboardFormat('{{ $format }}')">{{ $format }}</button>
                    @endforeach
                </div>
                <a id="admin-dashboard-export-link" href="{{ route('admin.recap.export', ['month' => $selectedMonth, 'year' => $selectedYear, 'format' => 'CSV']) }}" class="admin-export-link">
                    <img src="{{ asset('images/admin/export.svg') }}" alt="" aria-hidden="true">
                    <span>Ekspor</span>
                </a>
            </div>
        </form>

        <div class="admin-recap-panels">
            <article class="admin-recap-panel">
                <h3>Okupansi Fasilitas</h3>
                <div class="admin-recap-list">
                    @forelse($occupancyData as $item)
                        <div class="admin-recap-row">
                            <span class="admin-recap-row-name" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                            <span class="admin-recap-track {{ (int) $item['val'] === 0 ? 'is-empty' : '' }}"><span class="admin-recap-fill" style="display:block;width:{{ $item['val'] > 0 ? max($item['pct'], 8) : 0 }}%"></span></span>
                            <span class="admin-recap-row-value">{{ $item['val'] }}</span>
                        </div>
                    @empty
                        <div class="admin-recap-empty">Belum ada data okupansi.</div>
                    @endforelse
                </div>
                <footer class="admin-recap-panel-footer">
                    <a href="{{ route('admin.recap.index') }}" class="admin-detail-link">Detail</a>
                </footer>
            </article>

            <article class="admin-recap-panel">
                <h3>Frekuensi Laporan Fasilitas</h3>
                <div class="admin-recap-list">
                    @forelse($reportFrequencyData as $item)
                        <div class="admin-recap-row">
                            <span class="admin-recap-row-name" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                            <span class="admin-recap-track {{ (int) $item['val'] === 0 ? 'is-empty' : '' }}"><span class="admin-recap-fill" style="display:block;width:{{ $item['val'] > 0 ? max($item['pct'], 8) : 0 }}%"></span></span>
                            <span class="admin-recap-row-value">{{ $item['val'] }}</span>
                        </div>
                    @empty
                        <div class="admin-recap-empty">Belum ada data laporan.</div>
                    @endforelse
                </div>
                <footer class="admin-recap-panel-footer">
                    <a href="{{ route('admin.recap.index') }}" class="admin-detail-link">Detail</a>
                </footer>
            </article>
        </div>
    </section>
</div>

<script>
    function setAdminDashboardFormat(format) {
        document.getElementById('admin-dashboard-export-format').value = format;
        document.querySelectorAll('.admin-dashboard-format-option').forEach((button) => {
            const isSelected = button.dataset.format === format;
            button.classList.toggle('is-selected', isSelected);
            button.setAttribute('aria-pressed', String(isSelected));
        });

        const exportLink = document.getElementById('admin-dashboard-export-link');
        const exportUrl = new URL(exportLink.href);
        exportUrl.searchParams.set('format', format);
        exportLink.href = exportUrl.toString();
    }
</script>
@endsection
