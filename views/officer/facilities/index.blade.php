@extends('layouts.app')

@section('title', 'Status Operasional Fasilitas (Petugas) - Sewa Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Status Operasional Fasilitas</h1>
                <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-0.5 rounded-full border border-emerald-200">
                    FR-12 &bull; Portal Petugas
                </span>
            </div>
            <p class="text-sm text-slate-500">
                Pantau kondisi fasilitas dan kelola status perbaikan terkait penanganan laporan kerusakan.
            </p>
        </div>

        <a href="{{ route('officer.reports.index') }}" 
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-xs transition-all shrink-0">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Buka Antrean Laporan (FR-11)
        </a>
    </div>

    <!-- Metric Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <!-- Total -->
        <a href="{{ route('officer.facilities.index') }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-indigo-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Total Fasilitas</span>
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="mt-2 text-2xl font-black text-slate-900">
                {{ $counts['all'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Seluruh fasilitas terdata</div>
        </a>

        <!-- Aktif -->
        <a href="{{ route('officer.facilities.index', ['status' => 'active']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Fasilitas Aktif</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="mt-2 text-2xl font-black text-emerald-600">
                {{ $counts['active'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Siap digunakan / disewa</div>
        </a>

        <!-- Dalam Perbaikan -->
        <a href="{{ route('officer.facilities.index', ['status' => 'in_repair']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-amber-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Dalam Perbaikan</span>
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            </div>
            <div class="mt-2 text-2xl font-black text-amber-600">
                {{ $counts['in_repair'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Sedang dalam proses reparasi (FR-12)</div>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
        <a href="{{ route('officer.facilities.index') }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Semua Fasilitas</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ empty($status) ? 'bg-indigo-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] }}
            </span>
        </a>

        <a href="{{ route('officer.facilities.index', ['status' => 'active']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'active' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Aktif ({{ $counts['active'] }})</span>
        </a>

        <a href="{{ route('officer.facilities.index', ['status' => 'in_repair']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'in_repair' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            <span>Dalam Perbaikan ({{ $counts['in_repair'] }})</span>
        </a>
    </div>

    <!-- Facilities List -->
    @if($facilities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($facilities as $facility)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-4 hover:border-slate-300 transition-all">
                    <div>
                        <!-- Header & Operational Status -->
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">
                                    {{ $facility->nama_fasilitas }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span class="font-semibold text-slate-700">{{ $facility->tipe }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $facility->lokasi }}</span>
                                </p>
                            </div>

                            <!-- Operational Status Badge -->
                            <div class="shrink-0">
                                @if($facility->status_fasilitas === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @elseif($facility->status_fasilitas === 'in_repair')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Dalam Perbaikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Facility Details -->
                        <div class="pt-3 space-y-2 text-xs text-slate-600">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Kapasitas:</span>
                                <span class="font-semibold text-slate-800">{{ $facility->kapasitas }} Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Laporan Kerusakan Aktif:</span>
                                @if($facility->active_reports_count > 0)
                                    <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">
                                        {{ $facility->active_reports_count }} Laporan Menunggu/Diproses
                                    </span>
                                @else
                                    <span class="font-medium text-emerald-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tidak ada kerusakan aktif
                                    </span>
                                @endif
                            </div>
                            @if($facility->deskripsi)
                                <p class="text-slate-500 text-[11px] pt-1 leading-relaxed italic line-clamp-2">
                                    "{{ $facility->deskripsi }}"
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Toggle Button (FR-12) -->
                    <div class="pt-3 border-t border-slate-100">
                        @if($facility->status_fasilitas === 'active')
                            <form action="{{ route('officer.facilities.update-status', $facility->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status_fasilitas" value="in_repair">
                                <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 font-semibold text-xs transition-colors">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Tandai Dalam Perbaikan (in_repair)
                                </button>
                            </form>
                        @elseif($facility->status_fasilitas === 'in_repair')
                            <form action="{{ route('officer.facilities.update-status', $facility->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status_fasilitas" value="active">
                                <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Kembalikan ke Status Aktif (active)
                                </button>
                            </form>
                        @else
                            <div class="text-center text-xs text-slate-400 py-1 font-medium">
                                Fasilitas dinonaktifkan oleh Admin
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
            <h3 class="text-base font-bold text-slate-800">Tidak Ada Fasilitas Ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1">Belum ada fasilitas yang cocok dengan filter yang dipilih.</p>
        </div>
    @endif
</div>
@endsection

