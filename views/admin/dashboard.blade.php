@extends('layouts.admin')

@section('header_title', 'Dasbor')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container -->
<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <!-- Metrics Top & Bottom Grid Container -->
    <div class="space-y-2 shrink-0">
        <!-- Metrics Grid Top Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            <!-- Card 1: Totalitas Fasilitas -->
            <div class="bg-white rounded-lg p-2.5 border border-gray-200 flex flex-col justify-between shadow-none">
                <h3 class="text-lg font-extrabold text-[#001248] mb-1">Totalitas Fasilitas</h3>
                <div class="space-y-0.5 text-[11px]">
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-[#001248] font-normal">Aktif:</span>
                        <span class="text-[#001248] font-normal">{{ $aktif }}</span>
                    </div>
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-[#001248] font-normal">Dalam Perbaikan:</span>
                        <span class="text-[#001248] font-normal">{{ $perbaikan }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Reservasi Pending -->
            <div class="bg-white rounded-lg p-2.5 border border-gray-200 flex flex-col justify-between shadow-none">
                <h3 class="text-lg font-extrabold text-[#001248] mb-1">Reservasi Pending</h3>
                <span class="text-3xl font-extrabold text-[#001248] tracking-tight">{{ $reservasiMenunggu }}</span>
            </div>

            <!-- Card 3: Laporan Kerusakan -->
            <div class="bg-white rounded-lg p-2.5 border border-gray-200 flex flex-col justify-between shadow-none">
                <h3 class="text-lg font-extrabold text-[#001248] mb-1">Laporan Kerusakan</h3>
                <div class="space-y-0.5 text-[11px]">
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-[#001248] font-normal">Baru:</span>
                        <span class="text-[#001248] font-normal">{{ $statusLaporan['baru'] }}</span>
                    </div>
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-[#001248] font-normal">Diproses:</span>
                        <span class="text-[#001248] font-normal">{{ $statusLaporan['diproses'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid Bottom Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <!-- Card 4: Total Pengguna -->
            <div class="bg-white rounded-lg p-2.5 border border-gray-200 flex flex-col justify-between shadow-none">
                <h3 class="text-lg font-extrabold text-[#001248] mb-1">Total Pengguna</h3>
                <span class="text-3xl font-extrabold text-[#001248] tracking-tight">{{ $totalPengguna }}</span>
            </div>

            <!-- Card 5: Total Petugas -->
            <div class="bg-white rounded-lg p-2.5 border border-gray-200 flex flex-col justify-between shadow-none">
                <h3 class="text-lg font-extrabold text-[#001248] mb-1">Total Petugas</h3>
                <span class="text-3xl font-extrabold text-[#001248] tracking-tight">{{ $totalPetugas }}</span>
            </div>
        </div>
    </div>

    <!-- Rekap Visualization Section -->
    <div class="bg-white rounded-lg p-3 border border-gray-200 flex-1 flex flex-col justify-between mt-2 min-h-0 shadow-none">

        <!-- Rekap Header Controls -->
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2 shrink-0">
            <div class="flex items-center space-x-2">
                <h2 class="text-3xl font-bold text-[#001248] mr-1">Rekap</h2>

                <select name="month" id="dashMonth" onchange="this.form.submit()" class="border border-gray-300 rounded px-2.5 py-1 text-xs font-normal text-[#001248] focus:ring-[#001248] focus:border-[#001248] bg-white cursor-pointer">
                    @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="year" id="dashYear" onchange="this.form.submit()" class="border border-gray-300 rounded px-2.5 py-1 text-xs font-normal text-[#001248] focus:ring-[#001248] focus:border-[#001248] bg-white cursor-pointer">
                    @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Interactive Export Format Controls -->
                <div class="flex bg-gray-100 rounded p-0.5 border border-gray-200">
                    <button type="button" id="btn-fmt-csv" onclick="setDashboardFormat('CSV')" class="px-2.5 py-0.5 text-[10px] font-bold text-[#001248] bg-white rounded shadow-xs transition-colors">CSV</button>
                    <button type="button" id="btn-fmt-xlsx" onclick="setDashboardFormat('XLSX')" class="px-2.5 py-0.5 text-[10px] font-bold text-gray-400 hover:text-[#001248] rounded transition-colors">XLSX</button>
                    <button type="button" id="btn-fmt-pdf" onclick="setDashboardFormat('PDF')" class="px-2.5 py-0.5 text-[10px] font-bold text-gray-400 hover:text-[#001248] rounded transition-colors">PDF</button>
                </div>

                <!-- Dynamic Export Action Link -->
                <a id="dashboardExportBtn" href="{{ route('admin.recap.export', ['month' => $selectedMonth, 'year' => $selectedYear, 'format' => 'CSV']) }}" class="bg-[#001248] hover:bg-[#001a66] text-white px-3 py-1 rounded text-xs font-normal flex items-center transition-colors shadow-none shrink-0">
                    <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Ekspor</span>
                </a>
            </div>
        </form>

        <!-- Rekap Panels Side-by-Side -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 flex-1 min-h-0">

            <!-- Okupansi Fasilitas Card -->
            <div class="border border-gray-200 rounded-xl p-2.5 flex flex-col justify-between bg-white min-h-0 shadow-none">
                <h3 class="text-xl font-semibold text-[#001248] mb-1.5 shrink-0">Okupansi Fasilitas</h3>
                <div class="space-y-1 flex-1 overflow-hidden">
                    @forelse($occupancyData as $item)
                    <div class="flex items-center text-[11px] font-medium text-[#001248]">
                        <span class="w-24 truncate mr-2 text-[#001248] font-normal">{{ $item['name'] }}</span>
                        <div class="flex-1 h-1.5">
                            <div class="h-1.5 bg-gray-400 rounded-full transition-all duration-500" style="width: {{ max($item['pct'], 8) }}%;"></div>
                        </div>
                        <span class="w-6 text-right ml-2 text-[#001248] font-normal">{{ $item['val'] }}</span>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 text-center py-2">Belum ada data okupansi.</div>
                    @endforelse
                </div>
                <div class="mt-1 text-right shrink-0">
                    <a href="{{ route('admin.recap.index') }}" class="inline-block bg-[#001248] text-white px-3 py-0.5 rounded text-xs font-semibold hover:bg-[#001a66] transition-colors shadow-none">Detail</a>
                </div>
            </div>

            <!-- Frekuensi Laporan Fasilitas Card -->
            <div class="border border-gray-200 rounded-xl p-2.5 flex flex-col justify-between bg-white min-h-0 shadow-none">
                <h3 class="text-xl font-semibold text-[#001248] mb-1.5 shrink-0">Frekuensi Laporan Fasilitas</h3>
                <div class="space-y-1 flex-1 overflow-hidden">
                    @forelse($reportFrequencyData as $item)
                    <div class="flex items-center text-[11px] font-medium text-[#001248]">
                        <span class="w-24 truncate mr-2 text-[#001248] font-normal">{{ $item['name'] }}</span>
                        <div class="flex-1 h-1.5">
                            <div class="h-1.5 bg-gray-400 rounded-full transition-all duration-500" style="width: {{ max($item['pct'], 8) }}%;"></div>
                        </div>
                        <span class="w-6 text-right ml-2 text-[#001248] font-normal">{{ $item['val'] }}</span>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 text-center py-2">Belum ada data laporan.</div>
                    @endforelse
                </div>
                <div class="mt-1 text-right shrink-0">
                    <a href="{{ route('admin.recap.index') }}" class="inline-block bg-[#001248] text-white px-3 py-0.5 rounded text-xs font-semibold hover:bg-[#001a66] transition-colors shadow-none">Detail</a>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- JavaScript Engine for Export Format State Switching -->
<script>
    let currentFormat = 'CSV';

    function setDashboardFormat(fmt) {
        currentFormat = fmt;
        const formats = ['CSV', 'XLSX', 'PDF'];

        // Update active button visual state
        formats.forEach(f => {
            const btn = document.getElementById('btn-fmt-' + f.toLowerCase());
            if (btn) {
                if (f === fmt) {
                    btn.className = 'px-2.5 py-0.5 text-[10px] font-bold text-[#001248] bg-white rounded shadow-xs transition-colors';
                } else {
                    btn.className = 'px-2.5 py-0.5 text-[10px] font-bold text-gray-400 hover:text-[#001248] rounded transition-colors';
                }
            }
        });

        // Rebuild export URL with selected format, month, and year
        const month = document.getElementById('dashMonth').value;
        const year = document.getElementById('dashYear').value;
        const exportBtn = document.getElementById('dashboardExportBtn');
        if (exportBtn) {
            exportBtn.href = "{{ route('admin.recap.export') }}?month=" + month + "&year=" + year + "&format=" + fmt;
        }
    }
</script>
@endsection