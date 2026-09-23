@extends('layouts.admin')

@section('title', 'Tambah Fasilitas Baru')
@section('breadcrumb', 'Admin / Kelola Fasilitas / Tambah')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-lg font-bold text-[#0a192f] mb-6">Informasi Detail Fasilitas</h3>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- File uploads require multipart/form-data -->
        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Nama & Tipe -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Fasilitas *</label>
                        <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" placeholder="Masukkan nama lengkap sarana / fasilitas..." required
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Fasilitas *</label>
                        <select name="tipe" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                            <option value="" disabled selected>Pilih tipe...</option>
                            @foreach($tipeOptions as $tipe)
                            <option value="{{ $tipe }}" {{ old('tipe') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Lokasi & Kapasitas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Gedung / Ruangan *</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gedung Widya Puraya Lt. 2, Kampus Tembalang" required
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas Maksimal (Orang) *</label>
                        <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" placeholder="Masukkan kapasitas berupa angka..." min="0"
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi & Aturan Penggunaan</label>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan fasilitas yang disediakan, syarat peminjaman, serta ketentuan khusus lainnya..."
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Foto Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Utama Fasilitas</label>
                    <div class="w-full bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-100 transition-colors relative">
                        <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center justify-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400 text-2xl mb-3"></i>
                            <span class="text-sm font-bold text-[#0a192f] mb-1">Tarik & lepas file foto di sini, atau klik untuk telusuri</span>
                            <span class="text-xs text-gray-400">Mendukung format JPG, PNG maksimal ukuran 5MB</span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Status -->
                <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Status Publikasi Fasilitas</label>
                        <p class="text-xs text-gray-500">Tentukan apakah fasilitas langsung siap dipesan oleh mahasiswa & dosen.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.facilities.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-200 text-gray-600 font-medium text-sm hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#0a192f] hover:bg-slate-800 text-white font-medium text-sm transition-colors shadow-sm">
                    Simpan Fasilitas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection