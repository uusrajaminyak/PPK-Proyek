@extends('layouts.admin')

@section('header_title', 'Detail Rekap Fasilitas')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container (Zero-Scroll Flex Structure) -->
<div id="recap-show-container" class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-2.5 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <div class="max-w-6xl mx-auto w-full h-full flex flex-col justify-between space-y-2 overflow-hidden">

        <!-- Navigation Header & Switcher Toolbar -->
        <div class="flex items-center justify-between pb-1.5 border-b border-gray-100 shrink-0">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-extrabold text-[#001248]">Detail Rekap:</span>
                <!-- Facility Switcher Dropdown -->
                <select id="facilitySwitcher" onchange="switchFacility(this.value)" class="bg-white border border-gray-300 rounded px-2.5 py-1 text-xs font-extrabold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] cursor-pointer">
                    <option value="all" {{ $facilityId === 'all' ? 'selected' : '' }}>Semua Fasilitas</option>
                    @foreach($allFacilities as $fac)
                    <option value="{{ $fac->id }}" {{ $facilityId == $fac->id ? 'selected' : '' }}>{{ $fac->nama_fasilitas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2 shrink-0">
                <!-- Period Filter Form -->
                <form action="{{ route('admin.recap.facility', $facilityId) }}" method="GET" class="flex items-center space-x-1.5">
                    <select name="month" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-2.5 py-1 text-xs font-semibold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] cursor-pointer">
                        @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>

                    <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-2.5 py-1 text-xs font-semibold text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] cursor-pointer">
                        @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('admin.recap.index', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="text-xs font-semibold text-gray-500 hover:text-[#001248] transition-colors flex items-center space-x-1 ml-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Rekap</span>
                </a>
            </div>
        </div>

        <!-- KPI Metrics Summary Cards Row (4 Cards) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 shrink-0">
            <div class="bg-white border border-gray-200 rounded-lg p-2 flex flex-col justify-between shadow-none">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Reservasi</span>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-base font-extrabold text-[#001248]">{{ $totalReservations }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">Permohonan</span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-2 flex flex-col justify-between shadow-none">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Reservasi Disetujui</span>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-base font-extrabold text-[#001248]">{{ $approvedReservations }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">Disetujui</span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-2 flex flex-col justify-between shadow-none">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Laporan Kerusakan</span>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-base font-extrabold text-[#001248]">{{ $totalReports }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">Laporan</span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-2 flex flex-col justify-between shadow-none">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Laporan Selesai</span>
                <div class="flex items-baseline justify-between mt-1">
                    <span class="text-base font-extrabold text-[#001248]">{{ $resolvedReports }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">Selesai</span>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Split Grid (Reservations & Reports Side-by-Side) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2.5 flex-1 min-h-0 overflow-hidden">

            <!-- 1. Reservation Audit Log Table -->
            <div class="bg-white border border-[#001248]/20 rounded-lg p-2.5 shadow-none overflow-hidden flex flex-col justify-between min-h-0 h-full">
                <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-gray-100 shrink-0">
                    <h3 class="text-[11px] font-extrabold text-[#001248] uppercase tracking-wider">Rincian Okupansi & Reservasi</h3>
                    <span class="text-[10px] font-bold text-gray-400">Total Log: {{ $reservations->total() }}</span>
                </div>

                <div class="overflow-hidden flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-extrabold text-[#001248] border-b border-gray-200">
                                @if($facilityId === 'all')
                                <th class="py-1 px-2">Fasilitas</th>
                                @endif
                                <th class="py-1 px-2">Pemesan</th>
                                <th class="py-1 px-2">Waktu</th>
                                <th class="py-1 px-2">Tujuan</th>
                                <th class="py-1 px-2 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs text-[#001248] font-normal leading-tight">
                            @forelse($reservations as $res)
                            <tr>
                                @if($facilityId === 'all')
                                <td class="py-1 px-2 font-semibold text-xs truncate max-w-[110px]">{{ $res->facility->nama_fasilitas ?? 'Fasilitas #' . $res->facility_id }}</td>
                                @endif
                                <td class="py-1 px-2 font-normal text-xs">{{ $res->user->name ?? 'Pengguna' }}</td>
                                <td class="py-1 px-2 text-[11px] text-gray-600">
                                    {{ $res->start_time ? Carbon\Carbon::parse($res->start_time)->format('d M, H:i') : '-' }}
                                </td>
                                <td class="py-1 px-2 text-[11px] text-gray-500 max-w-[110px] truncate">{{ $res->tujuan_penggunaan ?? '-' }}</td>
                                <td class="py-1 px-2 text-right text-[11px] font-normal">
                                    @if($res->status_reservasi === 'approved')
                                    Disetujui
                                    @elseif($res->status_reservasi === 'pending')
                                    Menunggu
                                    @else
                                    Ditolak
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $facilityId === 'all' ? 5 : 4 }}" class="py-4 text-center text-gray-400 text-xs font-normal">Belum ada data reservasi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Damage Report Audit Log Table -->
            <div class="bg-white border border-[#001248]/20 rounded-lg p-2.5 shadow-none overflow-hidden flex flex-col justify-between min-h-0 h-full">
                <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-gray-100 shrink-0">
                    <h3 class="text-[11px] font-extrabold text-[#001248] uppercase tracking-wider">Rincian Laporan Kerusakan</h3>
                    <span class="text-[10px] font-bold text-gray-400">Total Log: {{ $reports->total() }}</span>
                </div>

                <div class="overflow-hidden flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-extrabold text-[#001248] border-b border-gray-200">
                                @if($facilityId === 'all')
                                <th class="py-1 px-2">Fasilitas</th>
                                @endif
                                <th class="py-1 px-2">Pelapor</th>
                                <th class="py-1 px-2">Kategori</th>
                                <th class="py-1 px-2">Deskripsi</th>
                                <th class="py-1 px-2 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs text-[#001248] font-normal leading-tight">
                            @forelse($reports as $rep)
                            <tr>
                                @if($facilityId === 'all')
                                <td class="py-1 px-2 font-semibold text-xs truncate max-w-[110px]">{{ $rep->facility->nama_fasilitas ?? 'Fasilitas #' . $rep->facility_id }}</td>
                                @endif
                                <td class="py-1 px-2 font-normal text-xs">{{ $rep->reporter->name ?? 'Pengguna' }}</td>
                                <td class="py-1 px-2 text-[11px] text-gray-600">{{ $rep->kategori_laporan ?? 'Umum' }}</td>
                                <td class="py-1 px-2 text-[11px] text-gray-500 max-w-[120px] truncate">{{ $rep->deskripsi ?? '-' }}</td>
                                <td class="py-1 px-2 text-right text-[11px] font-normal">
                                    @if($rep->status_laporan === 'selesai')
                                    Selesai
                                    @elseif($rep->status_laporan === 'diproses')
                                    Diproses
                                    @else
                                    Baru
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $facilityId === 'all' ? 5 : 4 }}" class="py-4 text-center text-gray-400 text-xs font-normal">Belum ada data laporan kerusakan pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript Facility Switcher Handler -->
<script>
    function switchFacility(facId) {
        const urlParams = new URLSearchParams(window.location.search);
        const month = urlParams.get('month') || '{{ $selectedMonth }}';
        const year = urlParams.get('year') || '{{ $selectedYear }}';

        const targetUrl = "{{ url('admin/recap/facilities') }}/" + facId + "?month=" + month + "&year=" + year;
        window.location.href = targetUrl;
    }
</script>
@endsection