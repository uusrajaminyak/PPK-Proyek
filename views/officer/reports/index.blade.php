@extends('layouts.app')

@section('title', 'Antrean & Status Laporan Kerusakan (Petugas) - Sewa Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean Laporan Kerusakan</h1>
                <span class="text-xs bg-purple-100 text-purple-800 font-semibold px-2.5 py-0.5 rounded-full border border-purple-200">
                    FR-11 &bull; Portal Petugas
                </span>
            </div>
            <p class="text-sm text-slate-500">
                Kelola laporan kerusakan fasilitas yang masuk, perbarui status pengerjaan, dan berikan catatan resolusi teknis.
            </p>
        </div>
    </div>

    <!-- Metric Statistic Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <!-- Baru -->
        <a href="{{ route('officer.reports.index', ['status' => 'baru']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-sky-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Laporan Baru</span>
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
            </div>
            <div class="mt-2 text-2xl font-black text-sky-600">
                {{ $counts['baru'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Menunggu diverifikasi</div>
        </a>

        <!-- Diproses -->
        <a href="{{ route('officer.reports.index', ['status' => 'diproses']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-amber-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Sedang Diproses</span>
                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </div>
            <div class="mt-2 text-2xl font-black text-amber-600">
                {{ $counts['diproses'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Dalam tahap perbaikan</div>
        </a>

        <!-- Selesai -->
        <a href="{{ route('officer.reports.index', ['status' => 'selesai']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Selesai</span>
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="mt-2 text-2xl font-black text-emerald-600">
                {{ $counts['selesai'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Tuntas diperbaiki</div>
        </a>

        <!-- Ditolak -->
        <a href="{{ route('officer.reports.index', ['status' => 'ditolak']) }}" 
            class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-rose-400 transition-all shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Ditolak</span>
                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="mt-2 text-2xl font-black text-rose-600">
                {{ $counts['ditolak'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5">Tidak valid / ditutup</div>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
        <a href="{{ route('officer.reports.index') }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Semua Antrean</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ empty($status) ? 'bg-indigo-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $counts['all'] }}
            </span>
        </a>

        <a href="{{ route('officer.reports.index', ['status' => 'baru']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'baru' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-sky-400"></span>
            <span>Baru ({{ $counts['baru'] }})</span>
        </a>

        <a href="{{ route('officer.reports.index', ['status' => 'diproses']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'diproses' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            <span>Diproses ({{ $counts['diproses'] }})</span>
        </a>

        <a href="{{ route('officer.reports.index', ['status' => 'selesai']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'selesai' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Selesai ({{ $counts['selesai'] }})</span>
        </a>

        <a href="{{ route('officer.reports.index', ['status' => 'ditolak']) }}" 
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
            <span>Ditolak ({{ $counts['ditolak'] }})</span>
        </a>
    </div>

    <!-- Reports Queue List -->
    @if($reports->count() > 0)
        <div class="space-y-5">
            @foreach($reports as $report)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <!-- Card Top: Info Header -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-slate-400 font-mono">#LP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="font-bold text-slate-900 text-base">
                                    {{ $report->facility->nama_fasilitas ?? 'Fasilitas Tidak Diketahui' }}
                                </h3>
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium">
                                    {{ $report->facility->tipe ?? '-' }} ({{ $report->facility->lokasi ?? '-' }})
                                </span>
                                @if($report->facility)
                                    @if($report->facility->status_fasilitas === 'in_repair')
                                        <span class="inline-flex items-center gap-1 text-[11px] bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded-full border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Fasilitas: Dalam Perbaikan
                                        </span>
                                    @elseif($report->facility->status_fasilitas === 'active')
                                        <span class="inline-flex items-center gap-1 text-[11px] bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Fasilitas: Aktif
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-slate-700">Pelapor: {{ $report->reporter->name ?? 'Anonim' }} ({{ $report->reporter->email ?? '-' }})</span>
                                <span class="text-slate-300">&bull;</span>
                                <span>Waktu: {{ $report->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- Current Status Badge -->
                        <div class="shrink-0">
                            @if($report->status_laporan === 'baru')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                    Baru Masuk
                                </span>
                            @elseif($report->status_laporan === 'diproses')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5 animate-spin text-amber-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sedang Ditangani
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
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Category & Description -->
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori:</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                {{ $report->kategori_laporan }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-700 whitespace-pre-line bg-slate-50/70 p-3 rounded-xl border border-slate-100">
                            {{ $report->deskripsi }}
                        </p>
                    </div>

                    <!-- Photo Evidence -->
                    @if(!empty($report->foto_paths) && count($report->foto_paths) > 0)
                        <div>
                            <span class="text-xs font-semibold text-slate-500 block mb-2">Foto Bukti Kerusakan:</span>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach($report->foto_paths as $foto)
                                    <button type="button" 
                                        onclick="openImageModal('{{ asset('storage/' . $foto) }}')"
                                        class="group relative w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-indigo-500 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto bukti" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/30 transition-colors flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Catatan Resolusi Saat Ini (Jika Ada) -->
                    @if(!empty($report->catatan_resolusi))
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <span class="font-bold text-slate-800">Catatan Resolusi Sebelumnya:</span>
                                <p class="mt-0.5 leading-relaxed">{{ $report->catatan_resolusi }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Box: Ubah Status & Catatan Resolusi (FR-11) -->
                    <div class="pt-4 border-t border-slate-100 bg-slate-50/50 -mx-6 -mb-6 p-6 rounded-b-2xl">
                        <form action="{{ route('officer.reports.update', $report->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Ubah Status Penanganan Laporan (FR-11)
                                </label>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Radio Option: Baru -->
                                    <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer border border-slate-200 bg-white hover:bg-slate-50 has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50 has-[:checked]:text-sky-800">
                                        <input type="radio" name="status_laporan" value="baru" class="text-sky-600 focus:ring-sky-500" 
                                            {{ old('status_laporan', $report->status_laporan) === 'baru' ? 'checked' : '' }}
                                            onchange="toggleResolutionRequirement(this, 'res-box-{{ $report->id }}')">
                                        <span>Baru</span>
                                    </label>

                                    <!-- Radio Option: Diproses -->
                                    <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer border border-slate-200 bg-white hover:bg-slate-50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800">
                                        <input type="radio" name="status_laporan" value="diproses" class="text-amber-600 focus:ring-amber-500" 
                                            {{ old('status_laporan', $report->status_laporan) === 'diproses' ? 'checked' : '' }}
                                            onchange="toggleResolutionRequirement(this, 'res-box-{{ $report->id }}')">
                                        <span>Diproses</span>
                                    </label>

                                    <!-- Radio Option: Selesai -->
                                    <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer border border-slate-200 bg-white hover:bg-slate-50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800">
                                        <input type="radio" name="status_laporan" value="selesai" class="text-emerald-600 focus:ring-emerald-500" 
                                            {{ old('status_laporan', $report->status_laporan) === 'selesai' ? 'checked' : '' }}
                                            onchange="toggleResolutionRequirement(this, 'res-box-{{ $report->id }}')">
                                        <span>Selesai</span>
                                    </label>

                                    <!-- Radio Option: Ditolak -->
                                    <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer border border-slate-200 bg-white hover:bg-slate-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-800">
                                        <input type="radio" name="status_laporan" value="ditolak" class="text-rose-600 focus:ring-rose-500" 
                                            {{ old('status_laporan', $report->status_laporan) === 'ditolak' ? 'checked' : '' }}
                                            onchange="toggleResolutionRequirement(this, 'res-box-{{ $report->id }}')">
                                        <span>Ditolak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Catatan Resolusi Textarea -->
                            <div id="res-box-{{ $report->id }}">
                                <div class="flex justify-between items-center mb-1">
                                    <label for="catatan_resolusi_{{ $report->id }}" class="text-xs font-semibold text-slate-700">
                                        Catatan Resolusi Petugas 
                                        <span class="res-required text-rose-500 {{ in_array(old('status_laporan', $report->status_laporan), ['selesai', 'ditolak']) ? '' : 'hidden' }}">* (Wajib saat laporan ditutup)</span>
                                    </label>
                                    <span class="text-[11px] text-slate-400">Minimal 5 karakter</span>
                                </div>
                                <textarea name="catatan_resolusi" id="catatan_resolusi_{{ $report->id }}" rows="2"
                                    placeholder="Contoh: Teknisi telah mengganti spare part dan fasilitas telah berfungsi kembali normal..."
                                    class="w-full rounded-xl border border-slate-300 p-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 bg-white">{{ old('catatan_resolusi', $report->catatan_resolusi) }}</textarea>
                            </div>

                            <!-- FR-12: Sinkronisasi Status Fasilitas Terkait -->
                            <div class="pt-2.5 pb-1 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <label for="update_facility_status_{{ $report->id }}" class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span>Sinkronkan Status Fasilitas (FR-12):</span>
                                    <span class="text-[11px] text-slate-400 font-normal">
                                        (Saat ini: <strong>{{ $report->facility?->status_fasilitas === 'in_repair' ? 'Dalam Perbaikan' : ($report->facility?->status_fasilitas === 'active' ? 'Aktif' : 'Nonaktif') }}</strong>)
                                    </span>
                                </label>
                                <select name="update_facility_status" id="update_facility_status_{{ $report->id }}" 
                                    class="rounded-lg border border-slate-300 text-xs px-3 py-1.5 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="keep">-- Biarkan Status Fasilitas Tetap --</option>
                                    <option value="in_repair">Tandai Fasilitas: Dalam Perbaikan (in_repair)</option>
                                    <option value="active">Kembalikan Fasilitas: Aktif (active)</option>
                                </select>
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Perbarui Status Laporan
                                </button>
                            </div>
                        </form>
                    </div>
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
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">
                @if(!empty($status))
                    Tidak Ada Laporan di Antrean "{{ ucfirst($status) }}"
                @else
                    Antrean Laporan Kerusakan Kosong
                @endif
            </h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1.5">
                Semua laporan fasilitas telah ditangani atau belum ada laporan baru dari pengguna.
            </p>
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
function toggleResolutionRequirement(radio, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const requiredIndicator = container.querySelector('.res-required');
    if (!requiredIndicator) return;

    if (radio.value === 'selesai' || radio.value === 'ditolak') {
        requiredIndicator.classList.remove('hidden');
    } else {
        requiredIndicator.classList.add('hidden');
    }
}

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

