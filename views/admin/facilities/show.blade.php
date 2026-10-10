@extends('layouts.admin')

@section('header_title', 'Kelola Fasilitas')

@section('content')
<!-- Embed Plus Jakarta Sans Typography -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Outer Card Wrapper (Scroll-free flex container) -->
<div class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248] relative">

    <!-- Top Action Bar: Left-Anchored Back Button & Horizontally Centered Title Block -->
    <div class="relative flex items-center justify-center mb-4 shrink-0 min-h-[40px]">
        <!-- Left-Anchored Back Navigation Button -->
        <a href="{{ route('admin.facilities.index') }}" class="absolute left-0 inline-flex items-center space-x-2 text-xs font-bold text-[#001248] hover:text-[#001a66] transition-colors z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Kelola Fasilitas</span>
        </a>

        <!-- Horizontally Centered Title Badge (Matching Target Mockup 1:1) -->
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-[#001248] tracking-tight">Detail Fasilitas</h2>
            <p class="text-sm font-normal text-[#001248] uppercase tracking-wider mt-0.5">
                FAC-{{ str_pad($facility->id, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>
    </div>

    <!-- Main Detail Content Grid (2-Column Split) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1 min-h-0 items-start overflow-y-auto pr-1">

        <!-- Left Column: Dynamic Image Hero & Gallery Thumbnails (5 cols) -->
        <div class="lg:col-span-5 flex flex-col space-y-3.5">
            @php
            // Resolve dynamic database image path or fallback placeholder
            $dbImage = $facility->foto_path;
            if ($dbImage && !str_starts_with($dbImage, 'http://') && !str_starts_with($dbImage, 'https://')) {
            $imageUrl = asset('storage/' . $dbImage);
            } else {
            $imageUrl = $dbImage ?: 'https://images.unsplash.com/photo-1571260899304-425dea5cfd5b?auto=format&fit=crop&w=800&q=80';
            }
            @endphp

            <!-- Main Hero Photo (Pulled directly from Database foto_path) -->
            <div class="w-full h-64 sm:h-72 bg-gray-100 border border-gray-200 rounded-3xl overflow-hidden shadow-xs relative">
                <img src="{{ $imageUrl }}"
                    class="w-full h-full object-cover">
            </div>

            <!-- Dynamic 3-Image Preview Gallery Row -->
            <div class="grid grid-cols-3 gap-3">
                <!-- Active Thumbnail 1 (Database Image) -->
                <div class="h-20 bg-gray-100 border-2 border-[#001248] rounded-2xl overflow-hidden shadow-xs cursor-pointer">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                </div>
                <!-- Thumbnail 2 -->
                <div class="h-20 bg-gray-100 border border-gray-200 rounded-2xl overflow-hidden opacity-80 hover:opacity-100 transition-opacity cursor-pointer">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                </div>
                <!-- Thumbnail 3 -->
                <div class="h-20 bg-gray-100 border border-gray-200 rounded-2xl overflow-hidden opacity-80 hover:opacity-100 transition-opacity cursor-pointer">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Right Column: Dynamic Facility Metadata & Description (7 cols) -->
        <div class="lg:col-span-7 flex flex-col justify-between h-full pt-1">
            <div class="space-y-4">
                <!-- Facility Title -->
                <div>
                    <h1 class="text-3xl font-bold text-[#001248] tracking-tight leading-tight">
                        {{ $facility->nama_fasilitas }}
                    </h1>
                </div>

                <!-- Location Pin & Capacity Metadata -->
                <div class="space-y-2 pt-1">
                    <!-- Location -->
                    <div class="flex items-center space-x-2.5 text-xs font-normal text-[#001248]">
                        <svg class="w-4 h-4 text-[#001248] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>{{ $facility->lokasi }}</span>
                    </div>

                    <!-- Capacity -->
                    <div class="flex items-center space-x-2.5 text-xs font-normal text-[#001248]">
                        <svg class="w-4 h-4 text-[#001248] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Kapasitas {{ $facility->kapasitas }} orang</span>
                    </div>
                </div>

                <!-- Description & Amenities Header -->
                <div>
                    <h3 class="text-3xl font-bold text-[#001248] mb-2">Fasilitas</h3>
                    <p class="text-sm font-normal text-[#001248] leading-relaxed text-justify">
                        {{ $facility->deskripsi ?? 'Tidak ada deskripsi tambahan untuk fasilitas ini.' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection