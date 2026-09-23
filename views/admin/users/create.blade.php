@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')
@section('breadcrumb', 'Admin / Kelola Pengguna / Tambah Pengguna')

@section('content')
<div class="max-w-4xl">
    <!-- Main Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-lg font-bold text-[#0a192f] mb-6">Form Data Pengguna Baru</h3>

        <!-- Display Validation Errors if any -->
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pengguna..." required
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                </div>

                <!-- NIM & Kategori -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIM / NIP / ID *</label>
                        <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" placeholder="Contoh: 21120122140089" required
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pengguna *</label>
                        <select name="kategori" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                            <option value="" disabled selected>Pilih kategori...</option>
                            @foreach($kategoriOptions as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Email & Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Universitas *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="username@students.undip.ac.id" required
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: +62 812-3456-7890" required
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                </div>

                <!-- Fakultas & Prodi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fakultas *</label>
                        <select name="fakultas" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                            <option value="" disabled selected>Pilih fakultas...</option>
                            @foreach($fakultasOptions as $fakultas)
                            <option value="{{ $fakultas }}" {{ old('fakultas') == $fakultas ? 'selected' : '' }}>{{ $fakultas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Program Studi *</label>
                        <select name="program_studi" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                            <option value="" disabled selected>Pilih program studi...</option>
                            @foreach($prodiOptions as $prodi)
                            <option value="{{ $prodi }}" {{ old('program_studi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Auto Password & Confirm Password banner -->
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mt-2">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800">Informasi Kata Sandi</h4>
                            <p class="text-xs text-blue-600 mt-1">
                                Sandi akan di-generate secara otomatis oleh sistem dan dikirimkan langsung ke email universitas yang didaftarkan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Custom Toggle Switch -->
                <div class="border-t border-gray-100 pt-6 pb-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Status Akun Aktif</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ml-3 text-sm font-medium text-gray-600">Akun langsung aktif setelah disimpan</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-200 text-gray-600 font-medium text-sm hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#0a192f] hover:bg-slate-800 text-white font-medium text-sm transition-colors shadow-sm">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection