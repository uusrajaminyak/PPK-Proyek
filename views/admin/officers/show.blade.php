@extends('layouts.admin')

@section('header_title', 'Detail Petugas')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container (Zero-Scroll Flex Structure) -->
<div id="officers-show-container" class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-2.5 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <div class="max-w-6xl mx-auto w-full h-full flex flex-col justify-between space-y-2 overflow-hidden">

        <!-- Navigation Header -->
        <div class="flex items-center justify-between pb-1.5 border-b border-gray-100 shrink-0">
            <div>
                <h2 class="text-sm font-extrabold text-[#001248]">Detail Akun & Kinerja Petugas</h2>
                <p class="text-[11px] text-[#001248]/60 font-medium">Informasi profil resmi, status operasional, dan riwayat penanganan fasilitas.</p>
            </div>
            <a href="{{ route('admin.officers.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#001248] transition-colors flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Success Alert Banner -->
        @if (session('success'))
        <div class="p-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center justify-between shrink-0">
            <div class="flex items-center space-x-2">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Main Dashboard Split Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2.5 flex-1 min-h-0 overflow-hidden">

            <!-- Left Profile Card (1 Column) -->
            <div class="bg-white border border-[#001248]/20 rounded-lg p-2.5 flex flex-col justify-between space-y-2 shadow-none overflow-hidden h-full">
                <div class="space-y-2 overflow-hidden">
                    <!-- Avatar Header -->
                    <div class="flex flex-col items-center text-center pb-2 border-b border-gray-100 shrink-0">
                        <div class="w-12 h-12 rounded-full bg-[#001248] text-white font-extrabold text-base flex items-center justify-center shadow-none mb-1">
                            {{ strtoupper(substr($officer->name, 0, 2)) }}
                        </div>
                        <h3 class="text-sm font-extrabold text-[#001248] leading-tight truncate w-full">{{ $officer->name }}</h3>
                        <p class="text-[11px] text-gray-500 font-medium truncate w-full">{{ $officer->email }}</p>

                        <!-- Role & Status Pills -->
                        <div class="flex items-center space-x-1.5 mt-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold text-[#001248]">
                                Petugas Operasional
                            </span>
                            @if($officer->status_akun === 'verified' || $officer->status_akun === 'active')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold text-emerald-800">
                                Aktif
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold text-red-800">
                                Nonaktif
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata List -->
                    <div class="space-y-1 text-xs overflow-hidden">
                        <div class="flex justify-between py-0.5 border-b border-gray-50">
                            <span class="text-gray-400 font-medium text-[11px]">ID Petugas</span>
                            <span class="font-bold text-[#001248] text-[11px]">{{ $officer->nomor_identitas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-0.5 border-b border-gray-50">
                            <span class="text-gray-400 font-medium text-[11px]">Jabatan</span>
                            <span class="font-bold text-[#001248] text-right max-w-[160px] truncate text-[11px]">{{ $officer->kategori ?? 'Staf Lapangan' }}</span>
                        </div>
                        <div class="flex justify-between py-0.5 border-b border-gray-50">
                            <span class="text-gray-400 font-medium text-[11px]">No. Telepon</span>
                            <span class="font-bold text-[#001248] text-[11px]">{{ $officer->no_telepon ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-0.5 border-b border-gray-50">
                            <span class="text-gray-400 font-medium text-[11px]">Unit Kerja</span>
                            <span class="font-bold text-[#001248] text-right max-w-[160px] truncate text-[11px]">{{ $officer->fakultas ?? 'FSM' }}</span>
                        </div>
                        <div class="flex justify-between py-0.5">
                            <span class="text-gray-400 font-medium text-[11px]">Terdaftar Sejak</span>
                            <span class="font-bold text-[#001248] text-[11px]">{{ $officer->created_at ? $officer->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions Toolbar -->
                <div class="pt-2 border-t border-gray-100 space-y-1.5 shrink-0">
                    <h4 class="text-[11px] font-extrabold text-[#001248]">Tindakan Administrator</h4>

                    @if($officer->status_akun === 'verified' || $officer->status_akun === 'active')
                    <form action="{{ route('admin.officers.update-status', $officer->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="suspended">
                        <button type="submit" class="w-full bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold py-1 px-2 rounded text-xs transition-colors shadow-none">
                            Bekukan / Suspend Akun
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.officers.update-status', $officer->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_akun" value="verified">
                        <button type="submit" class="w-full bg-[#001248] hover:bg-[#001a66] text-white font-bold py-1 px-2 rounded text-xs transition-colors shadow-none">
                            Aktifkan Kembali Akun
                        </button>
                    </form>
                    @endif

                    <!-- Reset Password Form -->
                    <form action="{{ route('admin.officers.reset-password', $officer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset password akun petugas ini?');">
                        @csrf
                        <button type="submit" class="w-full bg-white border border-gray-300 text-[#001248] hover:border-[#001248] font-bold py-1 px-2 rounded text-xs transition-colors shadow-none flex items-center justify-center space-x-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Reset Password</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Activity Section (2 Columns) -->
            <div class="lg:col-span-2 flex flex-col justify-between space-y-2.5 h-full overflow-hidden">

                <!-- 1. Managed Damage Reports Card -->
                <div class="flex-1 bg-white border border-[#001248]/20 rounded-lg p-2.5 shadow-none overflow-hidden flex flex-col justify-between min-h-0">
                    <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-gray-100 shrink-0">
                        <h3 class="text-[11px] font-extrabold text-[#001248] uppercase tracking-wider">Antrean Laporan Kerusakan Unit</h3>
                        <span class="text-[10px] font-bold text-gray-400">Total: {{ $reports->total() }}</span>
                    </div>

                    <div class="overflow-hidden flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] font-extrabold text-[#001248] border-b border-gray-200">
                                    <th class="py-1 px-2">Fasilitas</th>
                                    <th class="py-1 px-2">Pelapor</th>
                                    <th class="py-1 px-2">Deskripsi</th>
                                    <th class="py-1 px-2 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs text-[#001248] font-normal leading-tight">
                                @forelse($reports as $rep)
                                <tr>
                                    <td class="py-1 px-2 font-semibold text-xs">{{ $rep->facility->nama_fasilitas ?? 'Fasilitas #' . $rep->facility_id }}</td>
                                    <td class="py-1 px-2 text-[11px] text-gray-600">{{ $rep->reporter->name ?? 'Pengguna' }}</td>
                                    <td class="py-1 px-2 text-[11px] text-gray-500 max-w-[180px] truncate">{{ $rep->deskripsi ?? '-' }}</td>
                                    <td class="py-1 px-2 text-right text-[11px]">
                                        @if($rep->status_laporan === 'selesai')
                                        <span class="text-emerald-700 font-semibold">Selesai</span>
                                        @elseif($rep->status_laporan === 'diproses')
                                        <span class="text-amber-700 font-semibold">Diproses</span>
                                        @else
                                        <span class="text-blue-700 font-semibold">Baru</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-center text-gray-400 text-xs font-normal">Belum ada laporan kerusakan di unit kerja ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Unit Reservations Overview Card -->
                <div class="flex-1 bg-white border border-[#001248]/20 rounded-lg p-2.5 shadow-none overflow-hidden flex flex-col justify-between min-h-0">
                    <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-gray-100 shrink-0">
                        <h3 class="text-[11px] font-extrabold text-[#001248] uppercase tracking-wider">Riwayat Reservasi Unit Kerja</h3>
                        <span class="text-[10px] font-bold text-gray-400">Total: {{ $reservations->total() }}</span>
                    </div>

                    <div class="overflow-hidden flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] font-extrabold text-[#001248] border-b border-gray-200">
                                    <th class="py-1 px-2">Fasilitas</th>
                                    <th class="py-1 px-2">Pemesan</th>
                                    <th class="py-1 px-2">Waktu Penggunaan</th>
                                    <th class="py-1 px-2 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs text-[#001248] font-normal leading-tight">
                                @forelse($reservations as $res)
                                <tr>
                                    <td class="py-1 px-2 font-semibold text-xs">{{ $res->facility->nama_fasilitas ?? 'Fasilitas #' . $res->facility_id }}</td>
                                    <td class="py-1 px-2 text-[11px] text-gray-600">{{ $res->user->name ?? 'Pemesan' }}</td>
                                    <td class="py-1 px-2 text-[11px] text-gray-500">
                                        {{ $res->start_time ? Carbon\Carbon::parse($res->start_time)->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-1 px-2 text-right text-[11px]">
                                        @if($res->status_reservasi === 'approved')
                                        <span class="text-emerald-700 font-semibold">Disetujui</span>
                                        @elseif($res->status_reservasi === 'pending')
                                        <span class="text-amber-700 font-semibold">Menunggu</span>
                                        @else
                                        <span class="text-red-700 font-semibold">Ditolak / Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-center text-gray-400 text-xs font-normal">Belum ada riwayat reservasi di unit kerja ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection