@extends('layouts.admin')

@section('header_title', 'Rekap')

@section('content')
<div class="admin-recap-page">
    <form id="recap-filter-form" action="{{ route('admin.recap.index') }}" method="GET" class="recap-toolbar">
        <div class="recap-period-filters" aria-label="Periode rekap">
            <label class="recap-select">
                <span class="sr-only">Bulan</span>
                <select name="month" onchange="this.form.submit()">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>
            <label class="recap-select recap-year-select">
                <span class="sr-only">Tahun</span>
                <select name="year" onchange="this.form.submit()">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
                <img src="{{ asset('images/admin/arrow-dropdown.svg') }}" alt="" aria-hidden="true">
            </label>
        </div>

        <div class="recap-export-actions">
            <input type="hidden" name="format" id="selectedFormat" value="{{ $exportFormat }}">
            <div class="recap-export-formats" role="group" aria-label="Pilih format ekspor">
                @foreach(['CSV', 'XLSX', 'PDF'] as $format)
                    <button type="button" aria-pressed="{{ $exportFormat === $format ? 'true' : 'false' }}" class="recap-format-option {{ $exportFormat === $format ? 'is-selected' : '' }}" data-format="{{ $format }}" onclick="setRecapFormat('{{ $format }}')">{{ $format }}</button>
                @endforeach
            </div>
            <a id="recap-export-link" href="{{ route('admin.recap.export', ['month' => $selectedMonth, 'year' => $selectedYear, 'format' => $exportFormat]) }}" class="recap-export-button">
                <img src="{{ asset('images/admin/export.svg') }}" alt="" aria-hidden="true">
                <span>Ekspor</span>
            </a>
        </div>
    </form>

    <section class="recap-analytics" aria-label="Ringkasan fasilitas">
        <article class="recap-panel">
            <header class="recap-panel-heading">
                <h2>Okupansi Fasilitas</h2>
                <span>Top 4 Fasilitas</span>
            </header>
            <div class="recap-chart-list">
                @forelse($occupancyData->take(4) as $item)
                    <div class="recap-chart-row">
                        <span class="recap-chart-name" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                        <span class="recap-chart-track {{ $item['count'] === 0 ? 'is-empty' : '' }}"><span style="width: {{ $item['count'] === 0 ? 0 : $item['percentage'] }}%"></span></span>
                        <span class="recap-chart-value">{{ $item['count'] }}</span>
                    </div>
                @empty
                    <p class="recap-empty">Belum ada data okupansi.</p>
                @endforelse
            </div>
            <footer class="recap-panel-footer">
                <a href="{{ route('admin.recap.facility', ['facility' => 'all', 'month' => $selectedMonth, 'year' => $selectedYear]) }}">Detail</a>
            </footer>
        </article>

        <article class="recap-panel">
            <header class="recap-panel-heading">
                <h2>Frekuensi Laporan Fasilitas</h2>
                <span>Top 4 Laporan</span>
            </header>
            <div class="recap-chart-list">
                @forelse($reportFrequencyData->take(4) as $item)
                    <div class="recap-chart-row">
                        <span class="recap-chart-name" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                        <span class="recap-chart-track {{ $item['count'] === 0 ? 'is-empty' : '' }}"><span style="width: {{ $item['count'] === 0 ? 0 : $item['percentage'] }}%"></span></span>
                        <span class="recap-chart-value">{{ $item['count'] }}</span>
                    </div>
                @empty
                    <p class="recap-empty">Belum ada data laporan.</p>
                @endforelse
            </div>
            <footer class="recap-panel-footer">
                <a href="{{ route('admin.recap.facility', ['facility' => 'all', 'month' => $selectedMonth, 'year' => $selectedYear]) }}">Detail</a>
            </footer>
        </article>
    </section>

    <section class="recap-table-panel" aria-label="Rekap per fasilitas">
        <div class="recap-table-scroll">
            <table class="recap-table">
                <thead>
                    <tr>
                        <th scope="col">Nama Fasilitas</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Total Direservasi</th>
                        <th scope="col">Total Dilaporkan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $facility)
                        <tr>
                            <td><a href="{{ route('admin.recap.facility', ['facility' => $facility->id, 'month' => $selectedMonth, 'year' => $selectedYear]) }}">{{ $facility->nama_fasilitas }}</a></td>
                            <td>{{ $facility->tipe ?? 'Ruang Kelas/Aula' }}</td>
                            <td>{{ $facility->total_direservasi }}</td>
                            <td>{{ $facility->total_dilaporkan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="recap-table-empty">Tidak ada data rekap fasilitas yang ditemukan dalam database.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    function setRecapFormat(format) {
        document.getElementById('selectedFormat').value = format;
        document.querySelectorAll('.recap-format-option').forEach((button) => {
            const isSelected = button.dataset.format === format;
            button.classList.toggle('is-selected', isSelected);
            button.setAttribute('aria-pressed', String(isSelected));
        });

        const exportLink = document.getElementById('recap-export-link');
        const exportUrl = new URL(exportLink.href);
        exportUrl.searchParams.set('format', format);
        exportLink.href = exportUrl.toString();
    }
</script>
@endsection
