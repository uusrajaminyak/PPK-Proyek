@extends('layouts.admin')

@section('title', 'Kelola Petugas')
@section('breadcrumb', 'Admin / Kelola Petugas')

@section('content')
<div class="space-y-6">

    <!-- Security Warning Banner -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex items-center">
        <i class="fas fa-exclamation-triangle text-orange-500 mr-3"></i>
        <p class="text-sm text-orange-800 font-medium">Akun petugas hanya dapat dibuat oleh Admin. Tidak tersedia pendaftaran mandiri demi menjaga keamanan sistem internal.</p>
    </div>

    <!-- Top Card: Title, Action Button, and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Daftar Petugas Fasilitas UNDIP</h3>
            <a href="{{ route('admin.officers.create') }}" class="bg-[#0a192f] hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center transition-colors shadow-sm">
                <i class="far fa-user mr-2"></i> + Tambah Petugas
            </a>
        </div>

        <form method="GET" action="{{ route('admin.officers.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, ID petugas, atau email..."
                    class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
            </div>

            <div class="flex items-center gap-2 min-w-[150px]">
                <select name="status" onchange="this.form.submit()" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
                    <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Status: Semua</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Status: Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Status: Nonaktif</option>
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
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider">Nama Petugas</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider">ID Petugas</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider">Email</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-800 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($officers as $officer)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-4 px-6 font-semibold text-[#0a192f]">{{ $officer->name }}</td>
                        <td class="py-4 px-6 text-gray-600">{{ $officer->nomor_identitas }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $officer->email }}</td>
                        <td class="py-4 px-6">
                            @if($officer->status_akun == 'verified')
                            <span class="text-green-500 bg-green-50 px-2 py-1 rounded-md text-xs font-semibold">Aktif</span>
                            @else
                            <span class="text-gray-500 bg-gray-100 px-2 py-1 rounded-md text-xs font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $officer->created_at->format('d M Y') }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Edit Button -->
                                <button class="bg-slate-100 text-[#0a192f] hover:bg-slate-200 text-xs font-bold px-3.5 py-1.5 rounded-lg transition-colors">
                                    Edit
                                </button>

                                <!-- Detail Button -->
                                <button class="bg-gray-100 text-gray-500 hover:bg-gray-200 text-xs font-bold px-3.5 py-1.5 rounded-lg transition-colors">
                                    Detail
                                </button>

                                <!-- Nonaktifkan Button -->
                                <button class="bg-red-50 text-red-500 hover:bg-red-100 text-xs font-bold px-3.5 py-1.5 rounded-lg transition-colors">
                                    Nonaktifkan
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500 text-sm">Tidak ada data petugas ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mockup Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $officers->links() }}
            </div>
            <div class="flex items-center gap-1 mt-4 md:mt-0">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50 transition-colors" disabled>
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#0a192f] text-white font-medium shadow-sm">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50 transition-colors" disabled>
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection