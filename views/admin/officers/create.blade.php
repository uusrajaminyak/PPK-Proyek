@extends('layouts.admin')

@section('title', 'Tambah Petugas Baru')
@section('breadcrumb', 'Admin / Kelola Petugas / Tambah')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-lg font-bold text-[#0a192f] mb-8 border-b border-gray-100 pb-4">Form Registrasi Akun Petugas</h3>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.officers.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap petugas..." required
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Institusi <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: nama.petugas@staff.undip.ac.id" required
                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                </div>

                <!-- Telepon & Jabatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="contoh: 0812345678..."
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan / Posisi</label>
                        <select name="jabatan" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                            @foreach($jabatanOptions as $jabatan)
                            <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bagian / Unit Kerja</label>
                    <select name="unit_kerja" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 appearance-none">
                        @foreach($unitKerjaOptions as $unit)
                        <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status & Auto-Password Note Card -->
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-5 mt-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">Status Akun Aktif</label>
                            <p class="text-[11px] text-gray-500 mt-0.5">Petugas dapat langsung login setelah akun berhasil dibuat</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status_aktif" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>

                    <div class="flex items-start pt-3 border-t border-gray-200">
                        <i class="fas fa-question-circle text-[#0a192f] mt-0.5 mr-2 text-sm"></i>
                        <p class="text-xs text-[#0a192f] font-medium">
                            Catatan: Password akan digenerate otomatis secara aman oleh sistem dan dikirim langsung ke alamat email petugas yang didaftarkan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.officers.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 font-medium text-sm hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#0a192f] hover:bg-slate-800 text-white font-medium text-sm transition-colors shadow-sm">
                    Simpan & Kirim Kredensial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection