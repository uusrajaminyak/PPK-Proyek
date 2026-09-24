@extends('layouts.app')

@section('title', ' - Ajukan Reservasi')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('facilities.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2">
            &larr; Kembali ke Jelajah Fasilitas
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Ajukan Reservasi Fasilitas</h1>
    </div>

    <!-- Ringkasan Fasilitas -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm mb-6 flex items-start gap-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900">{{ $facility->nama_fasilitas }}</h2>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 mt-1">
                <span><strong>Tipe:</strong> {{ $facility->tipe }}</span>
                <span><strong>Lokasi:</strong> {{ $facility->lokasi }}</span>
                <span><strong>Kapasitas:</strong> {{ $facility->kapasitas }} orang</span>
            </div>
            @if($facility->deskripsi)
                <p class="text-sm text-gray-600 mt-2">{{ $facility->deskripsi }}</p>
            @endif
        </div>
    </div>

    <!-- Form Reservasi -->
    <form action="{{ route('reservations.store') }}" method="POST" class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm space-y-6">
        @csrf
        <input type="hidden" name="facility_id" value="{{ $facility->id }}">

        <!-- Tanggal -->
        <div>
            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Reservasi</label>
            <input type="date" name="tanggal" id="tanggal" required
                   min="{{ date('Y-m-d') }}"
                   value="{{ old('tanggal', date('Y-m-d')) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            @error('tanggal')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pilihan Waktu (Kelipatan 30 Menit) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Waktu Reservasi</label>
            <p class="text-xs text-gray-500 mb-3">Waktu operasional: 07:00 - 20:00 WIB. Durasi reservasi harus kelipatan 30 menit (contoh: 07:00 - 08:00, 08:30 - 10:00).</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Jam Mulai -->
                <div>
                    <label for="slot_start" class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai</label>
                    <select name="slot_start" id="slot_start" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white text-sm">
                        <option value="">-- Pilih Jam Mulai --</option>
                        @foreach($timeOptions as $time)
                            @if($time !== end($timeOptions))
                                <option value="{{ $time }}" {{ old('slot_start') === $time ? 'selected' : '' }}>
                                    {{ $time }} WIB
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('slot_start')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="slot_end" class="block text-xs font-semibold text-gray-600 mb-1">Jam Selesai</label>
                    <select name="slot_end" id="slot_end" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white text-sm">
                        <option value="">-- Pilih Jam Selesai --</option>
                        @foreach($timeOptions as $time)
                            @if($time !== $timeOptions[0])
                                <option value="{{ $time }}" {{ old('slot_end') === $time ? 'selected' : '' }}>
                                    {{ $time }} WIB
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('slot_end')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tujuan Penggunaan -->
        <div>
            <label for="tujuan_penggunaan" class="block text-sm font-medium text-gray-700 mb-1">Tujuan Penggunaan</label>
            <textarea name="tujuan_penggunaan" id="tujuan_penggunaan" rows="3" required
                      placeholder="Jelaskan kegiatan atau keperluan reservasi..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('tujuan_penggunaan') }}</textarea>
            @error('tujuan_penggunaan')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                Ajukan Reservasi
            </button>
        </div>
    </form>
</div>
@endsection