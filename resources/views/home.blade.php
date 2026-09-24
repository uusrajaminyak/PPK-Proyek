@extends('layouts.app')

@section('title', ' - Daftar Fasilitas')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-4xl sm:text-5xl font-bold mb-4">Fasilitas Kampus</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
            Temukan dan reservasi fasilitas kampus dengan mudah. Aula, ruang kelas, laboratorium, ruang rapat, dan lapangan olahraga.
        </p>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto">
        <form method="GET" action="{{ route('home') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- Search Nama -->
            <div class="md:col-span-2">
                <label for="nama" class="sr-only">Cari nama fasilitas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="nama" 
                        id="nama" 
                        value="{{ $filters['nama'] ?? '' }}"
                        placeholder="Cari nama fasilitas..."
                        class="appearance-none w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >
                </div>
            </div>

            <!-- Filter Tipe -->
            <div>
                <label for="tipe" class="sr-only">Filter tipe</label>
                <select 
                    name="tipe" 
                    id="tipe"
                    class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white"
                >
                    <option value="">Semua Tipe</option>
                    @foreach($tipeList as $tipe)
                        <option value="{{ $tipe }}" {{ ($filters['tipe'] ?? '') === $tipe ? 'selected' : '' }}>
                            {{ $tipe }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label for="lokasi" class="sr-only">Filter lokasi</label>
                <select 
                    name="lokasi" 
                    id="lokasi"
                    class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white"
                >
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiList as $lokasi)
                        <option value="{{ $lokasi }}" {{ ($filters['lokasi'] ?? '') === $lokasi ? 'selected' : '' }}>
                            {{ $lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kapasitas Min -->
            <div class="flex items-end">
                <label for="kapasitas_min" class="sr-only">Kapasitas minimum</label>
                <input 
                    type="number" 
                    name="kapasitas_min" 
                    id="kapasitas_min"
                    value="{{ $filters['kapasitas_min'] ?? '' }}"
                    min="1"
                    placeholder="Kapasitas min"
                    class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
            </div>

            <!-- Submit & Reset -->
            <div class="lg:col-span-5 flex flex-wrap gap-3">
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a 
                    href="{{ route('home') }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Facilities Grid -->
<section class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Daftar Fasilitas 
                <span class="text-normal font-normal text-gray-500">({{ $facilities->total() }} fasilitas)</span>
            </h2>
            {{-- @auth
                <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    + Tambah Fasilitas (Coming Soon)
                </a>
            @endauth --}}
        </div>

        @if($facilities->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada fasilitas ditemukan</h3>
                <p class="mt-2 text-gray-500">Coba ubah filter pencarian atau reset filter.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-500 font-medium">Reset filter</a>
            </div>
        @else
            <!-- Grid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($facilities as $facility)
                    <article class="bg-white rounded-xl border {{ $facility->status_fasilitas === 'in_repair' ? 'border-amber-200 bg-amber-50/10' : 'border-gray-100' }} shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col">
                        <!-- Facility Image Placeholder -->
                        <div class="h-40 {{ $facility->status_fasilitas === 'in_repair' ? 'bg-gradient-to-br from-amber-100 to-amber-200 text-amber-400' : 'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-300' }} flex items-center justify-center relative">
                            <svg class="w-16 h-16 {{ $facility->status_fasilitas === 'in_repair' ? 'text-amber-400' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @switch($facility->tipe)
                                    @case('Aula')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        @break
                                    @case('Laboratorium')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        @break
                                    @case('Ruang Rapat')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        @break
                                    @case('Lapangan')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        @break
                                    @default
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                @endswitch
                            </svg>
                            
                            <!-- Status Badge -->
                            <span class="absolute top-3 right-3 px-2 py-1 text-xs font-medium rounded-full {{ $facility->getStatusBadgeClass() }}">
                                {{ $facility->getStatusLabel() }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-1 flex-1">{{ $facility->nama_fasilitas }}</h3>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">{{ $facility->tipe }}</span>
                                <span class="text-gray-300">|</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $facility->lokasi }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-3 flex-1">{{ $facility->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-sm font-medium text-gray-900">
                                Kapasitas: <span class="font-normal text-gray-600">{{ number_format($facility->kapasitas) }} orang</span>
                            </span>
                            
                            @if($facility->status_fasilitas === 'active')
                                @auth
                                    <a href="{{ route('reservations.create', $facility->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        Reservasi
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        Reservasi
                                    </a>
                                @endauth
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 {{ $facility->status_fasilitas === 'in_repair' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }} text-xs font-semibold rounded-lg cursor-not-allowed">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $facility->status_fasilitas === 'in_repair' ? 'Sedang Perbaikan' : 'Tidak Aktif' }}
                                </span>
                            @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>
</section>

<!-- CTA Section (untuk guest) -->
@guest
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-blue-50">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Ingin Memesan Fasilitas?</h2>
        <p class="text-gray-600 mb-8">Login untuk mengakses fitur reservasi, riwayat pemesanan, dan pelaporan kerusakan fasilitas.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">Masuk</a>
        </div>
    </div>
</section>
@endguest
@endsection
