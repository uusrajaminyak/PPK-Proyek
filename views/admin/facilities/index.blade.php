@extends('layouts.admin')

@section('header_title', 'Kelola Fasilitas')

@section('content')
<!-- Master Content Outer Container -->
<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-2.5 shadow-sm flex flex-col justify-between overflow-hidden">

    <!-- Search Bar & Filter Dropdowns -->
    <div class="space-y-1.5 shrink-0 mb-1">
        <!-- Search Bar Row -->
        <div class="w-full flex justify-center">
            <form action="{{ route('admin.facilities.index') }}" method="GET" class="w-full max-w-xl relative">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari fasilitas"
                    class="w-full bg-white border border-gray-300 rounded-full py-1.5 pl-5 pr-10 text-xs font-medium text-[#001248] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
                <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#001248]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Filter & Sort Dropdowns Row -->
        <form action="{{ route('admin.facilities.index') }}" method="GET" class="w-full flex justify-end items-center space-x-2">
            @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-3 py-0.5 text-xs font-medium text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                <option value="">Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <!-- Kategori Filter -->
            <select name="kategori" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded px-3 py-0.5 text-xs font-medium text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                <option value="">Kategori</option>
                <option value="Ruang Kelas" {{ request('kategori') == 'Ruang Kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                <option value="Aula" {{ request('kategori') == 'Aula' ? 'selected' : '' }}>Aula</option>
                <option value="Laboratorium" {{ request('kategori') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                <option value="Lapangan" {{ request('kategori') == 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
            </select>

            <!-- Urutkan Button -->
            <button type="submit" class="bg-white border border-gray-300 rounded px-3 py-0.5 text-xs font-medium text-gray-600 hover:text-[#001248] flex items-center space-x-1.5 transition-colors shadow-none">
                <span>Urutkan</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Dynamic Facility Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-1.5 flex-1 min-h-0 overflow-hidden pr-0">
        @forelse($facilities as $facility)
        @php
        $statusName = $facility->is_active ? 'Aktif' : 'Nonaktif';
        $imagePath = !empty($facility->foto) ? asset('storage/' . $facility->foto) : null;
        @endphp

        <!-- Card Outer Frame -->
        <div class="h-[190px] w-full bg-white border border-[#001248]/30 rounded-lg overflow-hidden flex flex-col justify-between shadow-none hover:border-[#001248] transition-colors">

            <!-- Top Grey Thumbnail Area -->
            <div class="relative w-full h-[105px] bg-[#D2D6DE] flex items-center justify-center shrink-0 rounded-t-lg overflow-hidden">
                @if($imagePath)
                <img src="{{ $imagePath }}" alt="{{ $facility->nama_fasilitas }}" class="w-full h-full object-cover">
                @else
                <!-- Vector Graphic Placeholder -->
                <svg class="w-8 h-8 text-gray-400 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4"></path>
                </svg>
                @endif

                <!-- Inset Status Pill -->
                <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-2xl text-[10px] font-bold shadow-none {{ $facility->is_active ? 'bg-white text-[#001248]' : 'bg-white/90 text-[#001248]' }}">
                    {{ $statusName }}
                </span>
            </div>

            <!-- Bottom White Body Area -->
            <div class="p-3 flex-1 flex items-center justify-between bg-white overflow-hidden">
                <!-- Left Details Block -->
                <div class="flex-1 pr-2">
                    <h3 class="text-base font-extrabold text-[#001248] mb-1 leading-tight truncate">{{ $facility->nama_fasilitas }}</h3>

                    <div class="space-y-0.5 text-[11px] text-[#001248]/70 font-medium">
                        <!-- Location Metadata -->
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0 text-[#001248]/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 21s-6-5.33-6-10a6 6 0 0 1 12 0c0 4.67-6 10-6 10z"></path>
                                <circle cx="12" cy="11" r="2"></circle>
                            </svg>
                            <span class="truncate">{{ $facility->lokasi }}</span>
                        </div>

                        <!-- Capacity Metadata -->
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0 text-[#001248]/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Kapasitas {{ $facility->kapasitas }} orang</span>
                        </div>
                    </div>
                </div>

                <!-- Right Action Block -->
                <div class="shrink-0">
                    <a href="{{ route('admin.facilities.show', $facility->id) }}" class="inline-block bg-[#001248] text-white px-3.5 py-1.5 rounded text-xs font-semibold hover:bg-[#0a1e5c] transition-colors shadow-none">
                        Detail
                    </a>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full bg-white p-6 rounded-2xl border border-gray-200 text-center text-gray-500 text-xs font-medium">
            Tidak ada data fasilitas yang ditemukan dalam database.
        </div>
        @endforelse
    </div>

    <!-- Bottom Dynamic Pagination Footer -->
    <div class="relative flex flex-col sm:flex-row items-center justify-between pt-1 border-t border-gray-100 shrink-0 mt-0.5 min-h-[30px]">
        <!-- Item Summary Counter -->
        <span class="text-xs font-medium text-gray-400 text-left sm:absolute sm:left-0">
            Menampilkan {{ $facilities->firstItem() ?? 0 }} – {{ $facilities->lastItem() ?? 0 }} dari {{ $facilities->total() }} fasilitas
        </span>

        <!-- Numbered Pagination Control Buttons -->
        <div class="flex items-center space-x-1.5 mx-auto my-0.5 sm:my-0 z-10">
            @if ($facilities->hasPages())
            {{-- Previous Page Link --}}
            @if ($facilities->onFirstPage())
            <span class="w-6 h-6 rounded border border-gray-200 text-gray-300 flex items-center justify-center text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
            @else
            <a href="{{ $facilities->previousPageUrl() }}" class="w-6 h-6 rounded border border-gray-300 flex items-center justify-center text-gray-500 hover:border-[#001248] hover:text-[#001248] transition-colors text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            @endif

            {{-- Page Number Links --}}
            @foreach ($facilities->getUrlRange(1, $facilities->lastPage()) as $page => $url)
            @if ($page == $facilities->currentPage())
            <span class="w-6 h-6 rounded bg-[#001248] text-white flex items-center justify-center text-xs font-bold">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="w-6 h-6 rounded border border-gray-300 text-gray-700 hover:border-[#001248] flex items-center justify-center text-xs font-semibold transition-colors">{{ $page }}</a>
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($facilities->hasMorePages())
            <a href="{{ $facilities->nextPageUrl() }}" class="w-6 h-6 rounded bg-[#001248] text-white flex items-center justify-center text-xs font-bold transition-colors">
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