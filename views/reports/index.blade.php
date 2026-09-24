@extends('layouts.staff')

@section('title', 'Riwayat & Status Laporan Kerusakan - Sewa Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat & Status Laporan</h1>
                <span class="text-xs bg-indigo-100 text-indigo-800 font-semibold px-2.5 py-0.5 rounded-full border border-indigo-200">
                    FR-07
                </span>
            </div>
            <p class="text-sm text-slate-500">
                Pantau perkembangan penanganan kerusakan fasilitas yang telah Anda laporkan.
            </p>
        </div>
        <a href="{{ route('reports.create') }}" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Lapor Kerusakan Baru
        </a>
    </div>

    <!-- Filter Tabs by Status -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
        <a href="{{ route('reports.index') }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Semua</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ empty($status) ? 'bg-indigo-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] }}
            </span>
        </a>

        <a href="{{ route('reports.index', ['status' => 'baru']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'baru' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-sky-400"></span>
            <span>Baru</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $status === 'baru' ? 'bg-sky-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['baru'] }}
            </span>
        </a>

        <a href="{{ route('reports.index', ['status' => 'diproses']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'diproses' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            <span>Diproses</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $status === 'diproses' ? 'bg-amber-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['diproses'] }}
            </span>
        </a>

        <a href="{{ route('reports.index', ['status' => 'selesai']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'selesai' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Selesai</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $status === 'selesai' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['selesai'] }}
            </span>
        </a>

        <a href="{{ route('reports.index', ['status' => 'ditolak']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
            <span>Ditolak</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $status === 'ditolak' ? 'bg-rose-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['ditolak'] }}
            </span>
        </a>
    </div>

    <!-- Reports Listing -->
    @if($reports->count() > 0)
        <div class="space-y-4">
            @foreach($reports as $report)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs hover:border-slate-300 transition-all space-y-4">
                    <!-- Top Bar: Facility Info & Status Badge -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-base">
                                    {{ $report->facility->nama_fasilitas ?? 'Fasilitas Tidak Diketahui' }}
                                </h3>
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium">
                                    {{ $report->facility->tipe ?? '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $report->facility->lokasi ?? '-' }}
                                <span class="text-slate-300">&bull;</span>
                                <span>Dilaporkan pada: {{ $report->created_at->format('d M Y, H:i') }}</span>
                            </p>
                        </div>

                        <!-- Status Badge Component -->
                        <div class="shrink-0">
                            @if($report->status_laporan === 'baru')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                    Baru (Menunggu Verifikasi)
                                </span>
                            @elseif($report->status_laporan === 'diproses')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5 animate-spin text-amber-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sedang Ditangani Petugas
                                </span>
                            @elseif($report->status_laporan === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Selesai Diperbaiki
                                </span>
                            @elseif($report->status_laporan === 'ditolak')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Laporan Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Category & Description -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori:</span>
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                {{ $report->kategori_laporan }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/60 p-3.5 rounded-xl border border-slate-100">
                            {{ $report->deskripsi }}
                        </p>
                    </div>

                    <!-- Photo Evidence Thumbnails -->
                    @if(!empty($report->foto_paths) && count($report->foto_paths) > 0)
                        <div>
                            <span class="text-xs font-semibold text-slate-500 block mb-2">Foto Bukti Kerusakan:</span>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach($report->foto_paths as $foto)
                                    <button type="button" 
                                        onclick="openImageModal('{{ asset('storage/' . $foto) }}')"
                                        class="group relative w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-indigo-500 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <img src="{{ asset('storage/' . $foto) }}" 
                                            alt="Bukti foto kerusakan" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/30 transition-colors flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Catatan Resolusi dari Petugas (Jika Ada) -->
                    @if(!empty($report->catatan_resolusi))
                        <div class="p-4 rounded-xl {{ $report->status_laporan === 'ditolak' ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900' }} border flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg {{ $report->status_laporan === 'ditolak' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center shrink-0 mt-0.5">
                                @if($report->status_laporan === 'ditolak')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-xs">
                                <span class="font-bold block text-sm mb-0.5">
                                    {{ $report->status_laporan === 'ditolak' ? 'Alasan Penolakan Petugas:' : 'Catatan Resolusi Petugas:' }}
                                </span>
                                <p class="leading-relaxed opacity-90">{{ $report->catatan_resolusi }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $reports->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">
                @if(!empty($status))
                    Tidak Ada Laporan dengan Status "{{ ucfirst($status) }}"
                @else
                    Belum Ada Laporan Kerusakan
                @endif
            </h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1.5 mb-5">
                @if(!empty($status))
                    Anda tidak memiliki laporan kerusakan pada kategori status ini. Coba pilih filter "Semua".
                @else
                    Anda belum pernah mengajukan laporan kerusakan fasilitas. Jika menemukan kendala fasilitas kampus, silakan ajukan laporan.
                @endif
            </p>
            <a href="{{ route('reports.create') }}" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Baru Sekarang
            </a>
        </div>
    @endif
</div>

<!-- Image Lightbox Modal -->
<div id="image-modal" class="fixed inset-0 bg-slate-900/80 z-50 hidden flex items-center justify-center p-4 backdrop-blur-xs" onclick="closeImageModal()">
    <div class="relative max-w-3xl max-h-[90vh] bg-transparent" onclick="event.stopPropagation()">
        <img id="modal-img" src="" alt="Pratinjau Foto" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain">
        <button type="button" onclick="closeImageModal()" 
            class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white text-slate-700 font-bold shadow-md hover:bg-slate-100 flex items-center justify-center transition-colors">
            &times;
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modal-img').src = src;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.getElementById('modal-img').src = '';
}
</script>
@endpush

