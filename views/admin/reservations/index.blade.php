@extends('layouts.admin')

@section('title', 'Monitoring Reservasi Kampus')
@section('breadcrumb', 'Admin / Reservasi')

@section('content')
<div class="space-y-6">

    <!-- Top Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Reservasi, nama pemohon, atau keterangan..."
                    class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
            </div>

            <div class="flex items-center gap-3">
                <select name="fasilitas" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[140px]">
                    <option value="Semua">Fasilitas: Semua</option>
                    @foreach($facilities as $fac)
                    <option value="{{ $fac->id }}" {{ request('fasilitas') == $fac->id ? 'selected' : '' }}>
                        {{ $fac->nama_fasilitas }}
                    </option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[140px]">
                    <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Status: Semua</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <!-- Dropdown Waktu (Fungsional) -->
                <select name="waktu" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[140px]">
                    <option value="terbaru" {{ request('waktu') == 'terbaru' ? 'selected' : '' }}>Waktu: Terbaru</option>
                    <option value="terlama" {{ request('waktu') == 'terlama' ? 'selected' : '' }}>Waktu: Terlama</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">ID Reservasi</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Fasilitas</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Pemohon / Institusi</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Waktu</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Tujuan Kegiatan</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-800 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($reservations as $res)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-[#0a192f] whitespace-nowrap">
                            {{ 'RES-' . date('Y', strtotime($res->start_time)) . '-' . str_pad($res->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="py-4 px-6 font-medium text-[#0a192f] whitespace-nowrap">{{ $res->facility->nama_fasilitas ?? '-' }}</td>
                        <td class="py-4 px-6 text-gray-600 whitespace-nowrap">
                            {{ $res->user->name ?? '-' }}
                            <span class="text-gray-400 text-xs">({{ $res->user->fakultas ?? 'Personal' }})</span>
                        </td>
                        <td class="py-4 px-6 text-gray-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($res->start_time)->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                        </td>
                        <td class="py-4 px-6 text-gray-400">
                            <div class="max-w-[100px] truncate text-xs" title="{{ $res->tujuan_penggunaan }}">
                                {{ $res->tujuan_penggunaan ?? '...' }}
                            </div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            @php
                            $status = strtolower(trim($res->status ?? ''));
                            @endphp

                            @if($status == 'disetujui' || $status == 'approved')
                            <span class="text-teal-500 bg-teal-50 px-2.5 py-1 rounded-md text-[11px] font-bold">Disetujui</span>
                            @elseif($status == 'menunggu' || $status == 'pending')
                            <span class="text-orange-500 bg-orange-50 px-2.5 py-1 rounded-md text-[11px] font-bold">Menunggu</span>
                            @elseif($status == 'ditolak' || $status == 'rejected')
                            <span class="text-red-500 bg-red-50 px-2.5 py-1 rounded-md text-[11px] font-bold">Ditolak</span>
                            @elseif($status == 'dibatalkan' || $status == 'cancelled')
                            <span class="text-red-500 bg-red-50 px-2.5 py-1 rounded-md text-[11px] font-bold">Dibatalkan</span>
                            @else
                            <!-- Fallback badge to show raw value if it doesn't match any known enum -->
                            <span class="text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md text-[11px] font-bold">
                                {{ ucfirst($res->status ?? 'Unknown') }}
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="#" class="inline-block bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-700 text-[11px] font-semibold px-4 py-1.5 rounded transition-colors shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 text-sm">Tidak ada data reservasi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Dynamic Laravel Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection