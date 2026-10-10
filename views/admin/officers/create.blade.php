@extends('layouts.admin')

@section('header_title', 'Tambah Petugas')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container (Zero-Scroll Form Shell) -->
<div id="officers-create-container" class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3.5 shadow-sm flex flex-col justify-between overflow-hidden font-jakarta text-[#001248]">

    <form action="{{ route('admin.officers.store') }}" method="POST" class="h-full flex flex-col justify-between space-y-3 overflow-hidden">
        @csrf

        <!-- Header Title Bar -->
        <div class="flex items-center justify-between pb-2 border-b border-gray-100 shrink-0">
            <div>
                <h2 class="text-sm font-extrabold text-[#001248]">Form Registrasi Akun Petugas</h2>
                <p class="text-[11px] text-[#001248]/60 font-medium">Daftarkan akun petugas baru secara langsung ke dalam sistem.</p>
            </div>
            <a href="{{ route('admin.officers.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#001248] transition-colors flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Validation Error Alert Banner -->
        @if ($errors->any())
        <div class="p-2.5 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs font-semibold shrink-0">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Body Container -->
        <div class="flex-1 bg-white border border-gray-200 rounded-lg p-3.5 space-y-3 overflow-hidden flex flex-col justify-between min-h-0">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <!-- Nama Lengkap -->
                <div class="md:col-span-2 space-y-1">
                    <label class="block font-bold text-[#001248]">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap petugas..." required
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-normal text-[#001248] placeholder-[#001248]/40 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors">
                </div>

                <!-- Email Institusi -->
                <div class="space-y-1">
                    <label class="block font-bold text-[#001248]">Email Institusi <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama.petugas@staff.undip.ac.id" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-normal text-[#001248] placeholder-[#001248]/40 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors">
                </div>

                <!-- No. Telepon -->
                <div class="space-y-1">
                    <label class="block font-bold text-[#001248]">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="0812345678..."
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-normal text-[#001248] placeholder-[#001248]/40 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors">
                </div>

                <!-- Jabatan / Posisi -->
                <div class="space-y-1">
                    <label class="block font-bold text-[#001248]">Jabatan / Posisi</label>
                    <select name="jabatan" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-normal text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] cursor-pointer">
                        @foreach($jabatanOptions as $jabatan)
                        <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Kerja -->
                <div class="space-y-1">
                    <label class="block font-bold text-[#001248]">Bagian / Unit Kerja</label>
                    <select name="unit_kerja" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-normal text-[#001248] focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] cursor-pointer">
                        @foreach($unitKerjaOptions as $unit)
                        <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status Toggle & Auto-Password Note Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 space-y-2 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-bold text-[#001248]">Status Akun Aktif</span>
                        <p class="text-[11px] text-[#001248]/60 font-medium">Petugas dapat langsung melakukan login setelah akun terdaftar.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#001248]"></div>
                    </label>
                </div>

                <div class="pt-2 border-t border-gray-200/80 flex items-start space-x-2 text-[11px] text-[#001248]/80 font-medium">
                    <svg class="w-4 h-4 text-[#001248] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span><strong>Catatan:</strong> Password akan digenerate otomatis secara aman oleh sistem dan dapat di-reset kapan saja oleh Administrator.</span>
                </div>
            </div>
        </div>

        <!-- Form Action Footer Buttons -->
        <div class="flex items-center justify-end space-x-2 pt-1 border-t border-gray-100 shrink-0">
            <a href="{{ route('admin.officers.index') }}" class="px-4 py-1.5 rounded-full border border-gray-300 text-[#001248] hover:bg-gray-100 font-semibold text-xs transition-colors shadow-none">
                Batal
            </a>
            <button type="submit" class="bg-[#001248] hover:bg-[#001a66] text-white px-5 py-1.5 rounded-full text-xs font-bold transition-colors shadow-none">
                Simpan & Kirim Kredensial
            </button>
        </div>
    </form>
</div>
@endsection