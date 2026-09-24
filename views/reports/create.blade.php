@extends('layouts.app')

@section('title', 'Lapor Kerusakan Fasilitas - Sewa Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex text-xs text-slate-500 mb-2 gap-2 items-center">
            <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-slate-700 font-medium">Laporan Kerusakan</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>Laporkan Kerusakan Fasilitas</span>
                    <span class="text-xs bg-amber-100 text-amber-800 font-semibold px-2.5 py-0.5 rounded-full border border-amber-200">
                        FR-06
                    </span>
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Bantu kami menjaga kenyamanan fasilitas kampus dengan melaporkan kendala atau kerusakan yang Anda temukan.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Fasilitas Dropdown -->
                <div>
                    <label for="facility_id" class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Pilih Fasilitas Terkait <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="facility_id" id="facility_id" 
                            class="w-full rounded-xl border {{ $errors->has('facility_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-white">
                            <option value="">-- Pilih Fasilitas yang Mengalami Masalah --</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->nama_fasilitas }} &bull; {{ $facility->tipe }} ({{ $facility->lokasi }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('facility_id')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Kategori Kerusakan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                        Kategori Kerusakan <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($categories as $category)
                            <label class="relative flex items-center p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 has-[:checked]:text-indigo-900">
                                <input type="radio" name="kategori_laporan" value="{{ $category }}" 
                                    class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                    {{ old('kategori_laporan') == $category ? 'checked' : '' }}>
                                <span class="ml-2.5 text-sm font-medium text-slate-700">
                                    {{ $category }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('kategori_laporan')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Deskripsi Kerusakan -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="deskripsi" class="block text-sm font-semibold text-slate-800">
                            Deskripsi Masalah / Kerusakan <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-xs text-slate-400">Minimal 10 karakter</span>
                    </div>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        placeholder="Contoh: AC di ruang aula utama tidak dingin dan mengeluarkan bunyi berisik sejak kemarin siang..."
                        class="w-full rounded-xl border {{ $errors->has('deskripsi') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} p-3.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Unggah Foto Bukti -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Foto Bukti Kerusakan <span class="text-xs font-normal text-slate-500">(Opsional, maks. 5 foto)</span>
                    </label>
                    
                    <div class="mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-indigo-400 transition-colors bg-slate-50/50">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="fotos" class="relative cursor-pointer bg-white rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Pilih berkas foto</span>
                                    <input id="fotos" name="fotos[]" type="file" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="sr-only" onchange="previewImages(this)">
                                </label>
                                <p class="pl-1 text-slate-500">atau tarik ke sini</p>
                            </div>
                            <p class="text-xs text-slate-400">
                                Format JPG, PNG, WEBP hingga 5MB per berkas
                            </p>
                        </div>
                    </div>

                    <!-- Image Preview Grid -->
                    <div id="preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3 hidden"></div>

                    @error('fotos')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                    @error('fotos.*')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Kirim Laporan Kerusakan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Helper Note -->
    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-3 text-xs text-blue-800">
        <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <span class="font-semibold">Alur Penanganan Laporan:</span>
            Laporan Anda akan otomatis berstatus <span class="font-bold underline">Baru</span> dan masuk ke dashboard antrean petugas (**FR-08**). Petugas dapat memperbarui status laporan menjadi diproses atau selesai (**FR-11**), serta menandai fasilitas dalam perbaikan (**FR-12**).
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImages(input) {
    const container = document.getElementById('preview-container');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        container.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-xs';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                
                const badge = document.createElement('div');
                badge.className = 'absolute bottom-1 right-1 bg-slate-900/70 text-white text-[10px] px-1.5 py-0.5 rounded font-mono';
                badge.textContent = (file.size / 1024).toFixed(0) + ' KB';
                
                wrapper.appendChild(img);
                wrapper.appendChild(badge);
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    } else {
        container.classList.add('hidden');
    }
}
</script>
@endpush

