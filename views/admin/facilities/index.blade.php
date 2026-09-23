@extends('layouts.admin')

@section('title', 'Kelola Fasilitas')
@section('breadcrumb', 'Admin / Kelola Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <!-- Header Row -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Daftar Sarana dan Fasilitas Kampus</h3>

            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <!-- View Toggles -->
                <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1">
                    <button class="bg-white shadow-sm text-gray-800 text-xs font-semibold px-4 py-1.5 rounded-md">Card View</button>
                    <button class="text-gray-500 hover:text-gray-800 text-xs font-semibold px-4 py-1.5 rounded-md transition-colors">Table View</button>
                </div>
                <!-- Add Button -->
                <a href="{{ route('admin.facilities.create') }}" class="bg-[#0a192f] hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition-colors">
                    <i class="fas fa-plus mr-2 text-xs"></i> Tambah Fasilitas
                </a>
            </div>
        </div>

        <!-- Filter Row -->
        <form method="GET" action="{{ route('admin.facilities.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama fasilitas..."
                    class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
            </div>

            <!-- Dropdown Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 font-medium">Tipe:</span>
                    <select name="tipe" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[120px]">
                        <option value="Semua">Semua</option>
                        <option value="Ruang Kelas">Ruang Kelas</option>
                        <option value="Aula">Aula</option>
                        <option value="Laboratorium">Laboratorium</option>
                        <option value="Lapangan">Lapangan</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 font-medium">Status:</span>
                    <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[120px]">
                        <option value="Semua">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="in_repair">Dalam Perbaikan</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 font-medium">Lokasi:</span>
                    <select name="lokasi" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 min-w-[120px]">
                        <option value="Semua">Semua</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Facility Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($facilities as $facility)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">

            <!-- Image & Badge -->
            <div class="relative h-48 bg-gray-200">
                <!-- Using Unsplash placeholders for realistic UI -->
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80" alt="{{ $facility->nama_fasilitas }}" class="w-full h-full object-cover">

                @if($facility->status_fasilitas == 'active')
                <span class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Aktif</span>
                @elseif($facility->status_fasilitas == 'in_repair')
                <span class="absolute top-3 right-3 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Dalam Perbaikan</span>
                @else
                <span class="absolute top-3 right-3 bg-gray-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">Tidak Aktif</span>
                @endif
            </div>

            <!-- Card Details -->
            <div class="p-6 flex-1 flex flex-col">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $facility->tipe }}</span>
                <h4 class="text-lg font-bold text-gray-800 mb-4">{{ $facility->nama_fasilitas }}</h4>

                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                    <span>{{ $facility->lokasi }}</span>
                </div>

                <div class="flex items-center text-sm text-gray-600 mb-6">
                    <i class="fas fa-users w-5 text-gray-400"></i>
                    <span>Kapasitas {{ $facility->kapasitas > 0 ? $facility->kapasitas . ' orang' : 'Open Area' }}</span>
                </div>

                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="#" class="flex-1 text-center py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 transition-colors">
                        Edit Fasilitas
                    </a>
                    <form action="#" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center py-2 text-sm font-semibold text-red-500 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                            Nonaktifkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-xl border border-gray-100 text-center text-gray-500">
            Tidak ada data fasilitas.
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $facilities->links() }}
    </div>
</div>
@endsection