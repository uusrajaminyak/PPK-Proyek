@extends('layouts.admin')

@section('header_title', 'Kelola Petugas')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container -->
<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <!-- Top Toolbar: Search Input Stretched Flush to Status Dropdown & Aligned Edge-to-Edge with Table Card -->
    <form action="{{ route('admin.officers.index') }}" method="GET" class="flex items-center justify-between w-full px-0 shrink-0 mb-1.5 pt-1 gap-3">
        <!-- Fluid Stretched Search Input -->
        <div class="flex-1 relative">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari petugas"
                class="w-full bg-white border border-gray-300 rounded-full py-1.5 pl-5 pr-10 text-xs font-semibold text-[#001248] placeholder-[#001248]/50 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
            <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#001248]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>

        <!-- Right-aligned Filter Controls & Add Officer Action Button -->
        <div class="flex items-center space-x-2 shrink-0">
            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-3 py-1.5 text-xs font-semibold text-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                <option value="Semua">Status</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Aktif</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <!-- Tambah Petugas Button -->
            <a href="{{ route('admin.officers.create') }}" class="bg-[#001248] hover:bg-[#001a66] text-white px-4 py-1.5 rounded-full text-xs font-bold flex items-center space-x-2 transition-colors shadow-none shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="16" y1="11" x2="22" y2="11"></line>
                </svg>
                <span>Tambah Petugas</span>
            </a>
        </div>
    </form>

    <!-- Data Table Container (Inner px-5 padding keeps row dividers inset from card borders) -->
    <div class="flex-1 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-none flex flex-col justify-between min-h-0 mb-1 px-5 py-1">
        <div class="overflow-hidden flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#001248] text-xs font-extrabold text-[#001248] bg-white sticky top-0 z-10">
                        <th class="py-1.5 px-3">Nama</th>
                        <th class="py-1.5 px-3">ID</th>
                        <th class="py-1.5 px-3">Email</th>
                        <th class="py-1.5 px-3">Status</th>
                        <th class="py-1.5 px-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-[#001248] font-medium">
                    @forelse($officers as $officer)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $officer->name }}</td>
                        <td class="py-1.5 px-3 text-[#001248] text-xs font-medium align-middle">{{ $officer->nomor_identitas ?? '-' }}</td>
                        <td class="py-1.5 px-3 text-[#001248] align-middle">{{ $officer->email }}</td>
                        <td class="py-1.5 px-3 align-middle">
                            @if($officer->status_akun == 'verified' || $officer->status_akun == 'active')
                            <span class="text-[#001248]">Aktif</span>
                            @else
                            <span class="text-[#001248]">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-1.5 px-3 text-right align-middle">
                            <a href="{{ route('admin.officers.show', $officer->id) }}" class="inline-block bg-[#001248] hover:bg-[#001a66] text-white px-3.5 py-1 rounded text-xs font-semibold transition-colors shadow-none">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-400 text-xs font-medium">
                            Tidak ada data petugas yang ditemukan dalam database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bottom Dynamic Pagination Footer -->
    <div class="relative flex flex-col sm:flex-row items-center justify-between pt-1 border-t border-gray-100 shrink-0 mt-0.5 min-h-[28px] px-1">
        <!-- Item Summary Counter (Left Anchored) -->
        <span class="text-xs font-semibold text-gray-400 text-left sm:absolute sm:left-1">
            Menampilkan {{ $officers->firstItem() ?? 0 }}–{{ $officers->lastItem() ?? 0 }} dari {{ $officers->total() }} petugas
        </span>

        <!-- Numbered Pagination Control Buttons (Horizontally Centered) -->
        <div class="flex items-center space-x-1.5 mx-auto my-0.5 sm:my-0 z-10">
            @if ($officers->hasPages())
            {{-- Previous Page Link --}}
            @if ($officers->onFirstPage())
            <span class="w-6 h-6 rounded border border-gray-300 text-gray-300 flex items-center justify-center text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
            @else
            <a href="{{ $officers->previousPageUrl() }}" class="w-6 h-6 rounded border border-gray-300 flex items-center justify-center text-[#001248] hover:bg-[#001248] hover:text-white transition-colors text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            @endif

            {{-- Page Number Links --}}
            @foreach ($officers->getUrlRange(1, $officers->lastPage()) as $page => $url)
            @if ($page == $officers->currentPage())
            <span class="w-6 h-6 rounded border border-gray-300 bg-[#001248] text-white flex items-center justify-center text-xs font-bold">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="w-6 h-6 rounded border border-gray-300 text-[#001248] hover:bg-[#001248] hover:text-white flex items-center justify-center text-xs font-semibold transition-colors">{{ $page }}</a>
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($officers->hasMorePages())
            <a href="{{ $officers->nextPageUrl() }}" class="w-6 h-6 rounded-lg bg-[#001248] text-white flex items-center justify-center text-xs font-bold transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
            @else
            <span class="w-6 h-6 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
            @endif
            @endif
        </div>
    </div>

</div>
@endsection