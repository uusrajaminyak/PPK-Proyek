@extends('layouts.admin')

@section('header_title', 'Tambah Pengguna Baru')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<!-- Master Content Outer Container -->
<div id="users-create-container" class="w-full h-full bg-white/70 border border-gray-200 rounded-lg p-3.5 shadow-sm flex flex-col justify-between overflow-y-auto font-jakarta">

    <div class="max-w-4xl mx-auto w-full space-y-4">
        <!-- Form Header Title -->
        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
            <div>
                <h2 class="text-base font-extrabold text-[#001248]">Form Registrasi Pengguna Baru</h2>
                <p class="text-xs text-[#001248]/60 font-medium">Tambah akun pengguna, petugas, atau admin secara langsung ke sistem.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#001248] transition-colors flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Display Server Validation Errors -->
        @if ($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-medium space-y-1">
            <div class="font-bold flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Terdapat kesalahan pada input form:</span>
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Form Card Body -->
            <div class="bg-white border border-[#001248]/20 rounded-lg p-4 space-y-3.5 shadow-none">

                <!-- Nama Lengkap & Kategori Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-extrabold text-[#001248] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pengguna..." required
                            class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-[#001248] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#001248] mb-1">Kategori Pengguna <span class="text-red-500">*</span></label>
                        <select name="kategori" id="kategori-select" required
                            class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih kategori...</option>
                            @foreach($kategoriOptions as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- NIM/NIP & Email Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div>
                        <label id="identity-label" class="block text-xs font-extrabold text-[#001248] mb-1">NIM / NIP / ID <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_identitas" id="nomor-identitas-input" value="{{ old('nomor_identitas') }}" placeholder="Contoh: 21120122140089" required
                            class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-[#001248] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-[#001248] mb-1">Email Universitas <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email-input" value="{{ old('email') }}" placeholder="username@students.undip.ac.id" required
                            class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-[#001248] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
                    </div>
                </div>

                <!-- Telepon & Fakultas Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-extrabold text-[#001248] mb-1">No. Telepon Aktif <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890" required
                            class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-[#001248] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#001248] focus:border-[#001248] transition-colors shadow-none">
                    </div>

                    <div id="fakultas-container">
                        <label class="block text-xs font-extrabold text-[#001248] mb-1">Fakultas</label>
                        <select name="fakultas" class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                            @foreach($fakultasOptions as $fakultas)
                            <option value="{{ $fakultas }}" {{ old('fakultas', 'Fakultas Sains dan Matematika (FSM)') == $fakultas ? 'selected' : '' }}>{{ $fakultas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Program Studi (Dynamic Field) -->
                <div id="prodi-container">
                    <label class="block text-xs font-extrabold text-[#001248] mb-1">Program Studi</label>
                    <select name="program_studi" class="w-full bg-white border border-gray-300 rounded py-1.5 px-3.5 text-xs font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#001248] shadow-none cursor-pointer">
                        <option value="" disabled {{ old('program_studi') ? '' : 'selected' }}>Pilih program studi...</option>
                        @foreach($prodiOptions as $prodi)
                        <option value="{{ $prodi }}" {{ old('program_studi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Password Info Banner -->
                <div class="bg-[#001248]/5 border border-[#001248]/15 rounded-lg p-3 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-[#001248] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold text-[#001248]">Informasi Kata Sandi Otomatis</h4>
                        <p class="text-[11px] text-[#001248]/70 font-medium mt-0.5">
                            Sandi acak akan di-generate secara otomatis oleh sistem saat data disimpan dan dapat langsung digunakan untuk login.
                        </p>
                    </div>
                </div>

                <!-- Account Status Toggle Switch -->
                <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-[#001248] block">Status Verifikasi Akun</span>
                        <span class="text-[11px] text-[#001248]/60 font-medium block">Aktifkan untuk langsung memverifikasi pengguna tanpa perlu persetujuan manual.</span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="status_aktif" value="1" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#001248]"></div>
                    </label>
                </div>

            </div>

            <!-- Form Action Footer Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-1">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-600 hover:text-[#001248] hover:border-[#001248] transition-colors shadow-none">
                    Batal
                </a>
                <button type="submit" class="px-5 py-1.5 bg-[#001248] text-white rounded-lg text-xs font-semibold hover:bg-[#0a1e5c] transition-colors shadow-none">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Dynamic Interactivity Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori-select');
        const identityLabel = document.getElementById('identity-label');
        const identityInput = document.getElementById('nomor-identitas-input');
        const emailInput = document.getElementById('email-input');
        const prodiContainer = document.getElementById('prodi-container');

        function updateFormLabels() {
            const val = kategoriSelect.value;

            if (val === 'Mahasiswa') {
                identityLabel.innerHTML = 'NIM Mahasiswa <span class="text-red-500">*</span>';
                identityInput.placeholder = 'Contoh: 21120122140089';
                if (!emailInput.value) emailInput.placeholder = 'nama@students.undip.ac.id';
                if (prodiContainer) prodiContainer.style.display = 'block';
            } else if (val === 'Dosen') {
                identityLabel.innerHTML = 'NIP Dosen <span class="text-red-500">*</span>';
                identityInput.placeholder = 'Contoh: 198203152008121001';
                if (!emailInput.value) emailInput.placeholder = 'nama@lecturer.undip.ac.id';
                if (prodiContainer) prodiContainer.style.display = 'block';
            } else if (val === 'Petugas') {
                identityLabel.innerHTML = 'ID Petugas <span class="text-red-500">*</span>';
                identityInput.placeholder = 'Contoh: PTG-007';
                if (!emailInput.value) emailInput.placeholder = 'nama@staff.undip.ac.id';
                if (prodiContainer) prodiContainer.style.display = 'none';
            } else if (val === 'Admin') {
                identityLabel.innerHTML = 'NIP / ID Admin <span class="text-red-500">*</span>';
                identityInput.placeholder = 'Contoh: 19850912201012';
                if (!emailInput.value) emailInput.placeholder = 'admin@undip.ac.id';
                if (prodiContainer) prodiContainer.style.display = 'none';
            }
        }

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', updateFormLabels);
            updateFormLabels();
        }
    });
</script>
@endsection