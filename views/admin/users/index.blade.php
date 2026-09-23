@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('breadcrumb', 'Admin / Kelola Pengguna')

@section('content')
<!-- Header Filter & Action Bar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <h3 class="text-lg font-bold text-gray-800">Daftar Pengguna FasilitasUNDIP</h3>
        <button class="bg-[#0A1E55] hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm">
            <i class="fas fa-user-plus mr-2"></i> + Tambah Pengguna
        </button>
    </div>

    <!-- Search & Dropdown Filters Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-8 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM/NIP, atau email mahasiswa..." class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2 flex items-center space-x-2">
            <span class="text-xs text-gray-500 font-medium">Role:</span>
            <select name="kategori" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Semua" {{ request('kategori') == 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Mahasiswa" {{ request('kategori') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="Dosen" {{ request('kategori') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="Staf" {{ request('kategori') == 'Staf' ? 'selected' : '' }}>Staf</option>
            </select>
        </div>
        <div class="md:col-span-2 flex items-center space-x-2">
            <span class="text-xs text-gray-500 font-medium">Status:</span>
            <select name="status" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </form>
</div>

<!-- Data Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-xs font-bold text-rgb(27 39 59) uppercase tracking-wider bg-gray-50/50">
                    <th class="py-4 px-6">Nama</th>
                    <th class="py-4 px-6">NIM / NIP / ID</th>
                    <th class="py-4 px-6">Kategori</th>
                    <th class="py-4 px-6">Email</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Dibuat</th>
                    <th class="py-4 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 font-semibold text-gray-800">{{ $user->name }}</td>
                    <td class="py-4 px-6 text-rgb(27 39 59) font-mono text-xs">{{ $user->nomor_identitas ?? '-' }}</td>
                    <td class="py-4 px-6 text-rgb(90 111 139)">{{ $user->kategori ?? 'Mahasiswa' }}</td>
                    <td class="py-4 px-6 text-rgb(90 111 139)">{{ $user->email }}</td>
                    <td class="py-4 px-6">
                        @if($user->status_akun == 'verified')
                        <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Aktif</span>
                        @elseif($user->status_akun == 'pending')
                        <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-medium">Menunggu</span>
                        @else
                        <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-right">
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500 text-sm">Tidak ada data pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
        <div>
            Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </div>
        <div class="mt-4 md:mt-0">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection