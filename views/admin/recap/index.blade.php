@extends('layouts.admin')

@section('header_title', 'Rekap')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3.5 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <!-- Top Toolbar: Form points to admin.recap.index -->
    <form action="{{ route('admin.recap.index') }}" method="GET" class="flex items-center justify-between w-full shrink-0 mb-2.5 pt-0.5">
        <div class="flex items-center space-x-2.5">
            <select name="month" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-4 py-1.5 text-xs font-semibold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                @foreach($months as $num => $name)
                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-4 py-1.5 text-xs font-semibold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                @foreach($years as $yr)
                <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center space-x-2.5">
            <input type="hidden" name="format" id="selectedFormat" value="{{ $exportFormat }}">

            <div class="flex items-center bg-gray-100/80 p-0.5 rounded border border-gray-200">
                <button type="button" onclick="setFormat('CSV')" class="px-3 py-1 text-[11px] font-bold rounded transition-colors {{ $exportFormat == 'CSV' ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-400 hover:text-[#001248]' }}">CSV</button>
                <button type="button" onclick="setFormat('XLSX')" class="px-3 py-1 text-[11px] font-bold rounded transition-colors {{ $exportFormat == 'XLSX' ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-400 hover:text-[#001248]' }}">XLSX</button>
                <button type="button" onclick="setFormat('PDF')" class="px-3 py-1 text-[11px] font-bold rounded transition-colors {{ $exportFormat == 'PDF' ? 'bg-white text-[#001248] shadow-sm' : 'text-gray-400 hover:text-[#001248]' }}">PDF</button>
            </div>

            <!-- Export button points to admin.recap.export -->
            <a href="{{ route('admin.recap.export', ['month' => $selectedMonth, 'year' => $selectedYear, 'format' => $exportFormat]) }}" class="bg-[#001248] hover:bg-[#001a66] text-white px-4 py-1.5 rounded text-xs font-bold flex items-center space-x-2 transition-colors shadow-none shrink-0">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Ekspor</span>
            </a>
        </div>
    </form>

    <!-- Analytics Cards & Data Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 shrink-0 mb-2.5">
        <div class="bg-white border border-gray-200 rounded-lg p-3.5 flex flex-col justify-between shadow-none min-h-[200px]">
            <div>
                <h3 class="text-[20px] font-extrabold text-[#001248] mb-2.5">Okupansi Fasilitas</h3>
                <div class="space-y-1.5">
                    <!-- Replace the loop items inside Okupansi Fasilitas with this updated structure -->
                    @forelse($occupancyData as $item)
                    <div class="flex items-center justify-between text-xs font-medium text-[#001248]">
                        <span class="w-28 truncate text-[11px] text-gray-600 font-semibold">{{ $item['nama'] }}</span>

                        <!-- Clean Progress Track (bg-gray-100 removed) -->
                        <div class="flex-1 mx-3 h-2">
                            <div class="bg-gray-300 h-2 rounded-full transition-all duration-500" style="width: {{ max($item['percentage'], 10) }}%"></div>
                        </div>

                        <span class="text-[11px] font-bold text-gray-500 w-5 text-right">{{ $item['count'] }}</span>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 text-center py-4">Belum ada data okupansi.</div>
                    @endforelse
                </div>
            </div>
            <div class="text-right mt-2">
                <a href="{{ route('admin.facilities.index') }}" class="inline-block bg-[#001248] hover:bg-[#001a66] text-white px-3.5 py-1 rounded text-xs font-semibold transition-colors shadow-none">Detail</a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-3.5 flex flex-col justify-between shadow-none min-h-[200px]">
            <div>
                <h3 class="text-[20px] font-extrabold text-[#001248] mb-2.5">Frekuensi Laporan Fasilitas</h3>
                <div class="space-y-1.5">
                    <!-- Replace the loop items inside Frekuensi Laporan Fasilitas with this updated structure -->
                    @forelse($reportFrequencyData as $item)
                    <div class="flex items-center justify-between text-xs font-medium text-[#001248]">
                        <span class="w-28 truncate text-[11px] text-gray-600 font-semibold">{{ $item['nama'] }}</span>

                        <!-- Clean Progress Track (bg-gray-100 removed) -->
                        <div class="flex-1 mx-3 h-2">
                            <div class="bg-gray-300 h-2 rounded-full transition-all duration-500" style="width: {{ max($item['percentage'], 10) }}%"></div>
                        </div>

                        <span class="text-[11px] font-bold text-gray-500 w-5 text-right">{{ $item['count'] }}</span>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 text-center py-4">Belum ada data laporan.</div>
                    @endforelse
                </div>
            </div>
            <div class="text-right mt-2">
                <a href="{{ route('admin.facilities.index') }}" class="inline-block bg-[#001248] hover:bg-[#001a66] text-white px-3.5 py-1 rounded text-xs font-semibold transition-colors shadow-none">Detail</a>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="flex-1 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-none flex flex-col justify-between min-h-0 px-5 py-1.5">
        <div class="overflow-hidden flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#001248] text-xs font-extrabold text-[#001248] bg-white sticky top-0 z-10">
                        <th class="py-1.5 px-3">Nama Fasilitas</th>
                        <th class="py-1.5 px-3">Tipe</th>
                        <th class="py-1.5 px-3">Total Direservasi</th>
                        <th class="py-1.5 px-3">Total Dilaporkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-[#001248] font-normal">
                    @forelse($facilities as $facility)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $facility->nama_fasilitas }}</td>
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $facility->tipe ?? 'Ruang Kelas/Aula' }}</td>
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $facility->total_direservasi }}</td>
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $facility->total_dilaporkan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-400 text-xs font-medium">
                            Tidak ada data rekap fasilitas yang ditemukan dalam database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function setFormat(fmt) {
        document.getElementById('selectedFormat').value = fmt;
        const form = document.querySelector('form');
        if (form) form.submit();
    }
</script>
@endsection