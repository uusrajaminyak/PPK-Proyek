@extends('layouts.admin')

@section('header_title', 'Dasbor')

@section('content')
<!-- Master Outer Frame Card -->
<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3.5 flex flex-col justify-between overflow-hidden">
    <!-- Metrics Top & Bottom Grid Container -->
    <div class="space-y-2.5 shrink-0">
        <!-- Metrics Grid Top Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">
            <!-- Card 1: Totalitas Fasilitas -->
            <div class="bg-white rounded-lg p-3 border border-gray-200 flex flex-col justify-between">
                <h3 class="text-xs font-bold text-[#081640] mb-1">Totalitas Fasilitas</h3>
                <div class="space-y-0.5 text-[11px]">
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-gray-600">Aktif:</span>
                        <span class="text-[#081640] font-bold">{{ $aktif ?? 36 }}</span>
                    </div>
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-gray-600">Dalam Perbaikan:</span>
                        <span class="text-[#081640] font-bold">{{ $perbaikan ?? 2 }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Reservasi Pending -->
            <div class="bg-white rounded-lg p-3 border border-gray-200 flex flex-col justify-between">
                <h3 class="text-xs font-bold text-[#081640] mb-0.5">Reservasi Pending</h3>
                <span class="text-2xl font-bold text-[#081640] tracking-tight">{{ $reservasiMenunggu ?? 2 }}</span>
            </div>

            <!-- Card 3: Laporan Kerusakan -->
            <div class="bg-white rounded-lg p-3 border border-gray-200 flex flex-col justify-between">
                <h3 class="text-xs font-bold text-[#081640] mb-1">Laporan Kerusakan</h3>
                <div class="space-y-0.5 text-[11px]">
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-gray-600">Baru:</span>
                        <span class="text-[#081640] font-bold">{{ $statusLaporan['baru'] ?? 5 }}</span>
                    </div>
                    <div class="flex justify-between items-center font-medium">
                        <span class="text-gray-600">Diproses:</span>
                        <span class="text-[#081640] font-bold">{{ $statusLaporan['diproses'] ?? 3 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid Bottom Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">
            <!-- Card 4: Total Pengguna -->
            <div class="bg-white rounded-lg p-3 border border-gray-200 flex flex-col justify-between">
                <h3 class="text-xs font-bold text-[#081640] mb-0.5">Total Pengguna</h3>
                <span class="text-2xl font-bold text-[#081640] tracking-tight">{{ $totalPengguna ?? 348 }}</span>
            </div>

            <!-- Card 5: Total Petugas -->
            <div class="bg-white rounded-lg p-3 border border-gray-200 flex flex-col justify-between">
                <h3 class="text-xs font-bold text-[#081640] mb-0.5">Total Petugas</h3>
                <span class="text-2xl font-bold text-[#081640] tracking-tight">{{ $totalPetugas ?? 10 }}</span>
            </div>
        </div>
    </div>

    <!-- Rekap Section -->
    <div class="bg-white rounded-lg p-3.5 border border-gray-200 flex-1 flex flex-col justify-between mt-2.5 min-h-0">

        <!-- Rekap Header Controls -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2 gap-2 shrink-0">
            <div class="flex items-center space-x-2.5">
                <h2 class="text-xl font-bold text-[#081640] mr-1">Rekap</h2>
                <select class="border border-gray-300 rounded-lg px-2 py-0.5 text-xs font-semibold text-[#081640] focus:ring-[#081640] focus:border-[#081640] bg-white">
                    <option>September</option>
                    <option>Oktober</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-2 py-0.5 text-xs font-semibold text-[#081640] focus:ring-[#081640] focus:border-[#081640] bg-white">
                    <option>2026</option>
                    <option>2025</option>
                </select>
            </div>

            <div class="flex items-center space-x-2.5">
                <!-- Segmented Control -->
                <div class="flex bg-gray-100 rounded-lg p-0.5 border border-gray-200">
                    <button class="px-2.5 py-0.5 text-[10px] font-semibold text-gray-500 hover:text-[#081640] rounded-md transition-colors">CSV</button>
                    <button class="px-2.5 py-0.5 text-[10px] font-semibold text-gray-500 hover:text-[#081640] rounded-md transition-colors">XLSX</button>
                    <button class="px-2.5 py-0.5 text-[10px] font-semibold bg-white text-[#081640] rounded-md transition-colors">PDF</button>
                </div>
                <!-- Action Button -->
                <button class="bg-[#081640] hover:bg-[#0a1e5c] text-white px-3 py-1 rounded-lg text-xs font-semibold flex items-center transition-colors">
                    <i class="fas fa-download mr-1.5 text-[10px]"></i> Ekspor
                </button>
            </div>
        </div>

        @php
        $mockOkupansi = [
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 85],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 70],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 60],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 45],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 30],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 20],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 15],
        ];

        $mockFrekuensi = [
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 85],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 70],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 60],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 45],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 30],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 20],
        ['name' => 'Lorem Ipsum', 'val' => 'xx', 'pct' => 15],
        ];
        @endphp

        <!-- Rekap Panels -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 flex-1 min-h-0">

            <!-- Okupansi Fasilitas Card -->
            <div class="border border-gray-200 rounded-lg p-3 relative flex flex-col justify-between bg-white min-h-0 shadow-none">
                <h3 class="text-xs font-bold text-[#081640] mb-1.5 shrink-0">Okupansi Fasilitas</h3>
                <div class="space-y-1.5 flex-1 overflow-y-auto">
                    @foreach($mockOkupansi as $item)
                    <div class="flex items-center text-[11px] font-medium text-[#081640]">
                        <span class="w-20 truncate mr-2 text-gray-700">{{ $item['name'] }}</span>
                        <!-- Flat Progress Track with Indicator Fill -->
                        <div class="flex-1 h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden flex items-center shadow-none">
                            <div class="h-full bg-[#94A3B8] rounded-full shadow-none" style="width: {{ $item['pct'] }}%;"></div>
                        </div>
                        <span class="w-5 text-right ml-2 text-gray-500 font-semibold">{{ $item['val'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-1.5 text-right shrink-0">
                    <button class="bg-[#081640] text-white px-3.5 py-1 rounded-lg text-xs font-semibold hover:bg-[#0a1e5c] transition-colors shadow-none">Detail</button>
                </div>
            </div>

            <!-- Frekuensi Laporan Fasilitas Card -->
            <div class="border border-gray-200 rounded-lg p-3 relative flex flex-col justify-between bg-white min-h-0 shadow-none">
                <h3 class="text-xs font-bold text-[#081640] mb-1.5 shrink-0">Frekuensi Laporan Fasilitas</h3>
                <div class="space-y-1.5 flex-1 overflow-y-auto">
                    @foreach($mockFrekuensi as $item)
                    <div class="flex items-center text-[11px] font-medium text-[#081640]">
                        <span class="w-20 truncate mr-2 text-gray-700">{{ $item['name'] }}</span>
                        <!-- Flat Progress Track with Indicator Fill -->
                        <div class="flex-1 h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden flex items-center shadow-none">
                            <div class="h-full bg-[#94A3B8] rounded-full shadow-none" style="width: {{ $item['pct'] }}%;"></div>
                        </div>
                        <span class="w-5 text-right ml-2 text-gray-500 font-semibold">{{ $item['val'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-1.5 text-right shrink-0">
                    <button class="bg-[#081640] text-white px-3.5 py-1 rounded-lg text-xs font-semibold hover:bg-[#0a1e5c] transition-colors shadow-none">Detail</button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection