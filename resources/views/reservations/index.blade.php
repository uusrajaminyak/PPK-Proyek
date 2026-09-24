@extends('layouts.app')

@section('title', ' - Riwayat Reservasi Saya')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Reservasi Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar semua permohonan reservasi fasilitas yang pernah Anda ajukan.</p>
        </div>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            + Reservasi Baru
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Reservasi</h3>
            <p class="text-sm text-gray-500 mt-1">Anda belum pernah mengajukan reservasi fasilitas kampus.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                Cari Fasilitas Sekarang
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reservations as $res)
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $res->facility->nama_fasilitas }}</h3>
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $res->getStatusBadgeClass() }}">
                                    {{ $res->getStatusLabel() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                📍 {{ $res->facility->lokasi }} | Tipe: {{ $res->facility->tipe }}
                            </p>
                        </div>

                        <!-- Info Waktu & Aksi -->
                        <div class="flex items-center gap-4">
                            <div class="text-left sm:text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $res->start_time->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $res->start_time->format('H:i') }} - {{ $res->end_time->format('H:i') }} WIB
                                </p>
                            </div>

                            @if($res->canBeCancelled())
                                <form action="{{ route('reservations.cancel', $res->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini? (Pembatalan hanya bisa dilakukan H-1)')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 text-sm">
                        <span class="text-gray-500">Tujuan:</span>
                        <span class="text-gray-800 ml-1">{{ $res->tujuan_penggunaan }}</span>

                        @if($res->alasan_pembatalan)
                            <div class="mt-1 text-xs text-red-600">
                                <strong>Alasan Pembatalan:</strong> {{ $res->alasan_pembatalan }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection