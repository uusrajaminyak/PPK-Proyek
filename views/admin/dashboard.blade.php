@extends('layouts.admin')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pengguna</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalPengguna }}</h3>
                <p class="text-xs text-gray-400 mt-2">Siswa, Dosen & Staf terdaftar</p>
            </div>
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-medium">Aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Petugas</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalPetugas }}</h3>
                <p class="text-xs text-gray-400 mt-2">Staf penanggung jawab lapangan</p>
            </div>
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-medium">Siap</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Fasilitas</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalFasilitas }}</h3>
                <p class="text-xs text-gray-400 mt-2">Ruang kelas, aula, lapangan</p>
            </div>
            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded font-medium">Unit</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Reservasi Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $reservasiAktif }}</h3>
                <p class="text-xs text-gray-400 mt-2">Sedang berlangsung hari ini</p>
            </div>
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-medium">Aktif</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Reservasi Menunggu</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $reservasiMenunggu }}</h3>
                <p class="text-xs text-gray-400 mt-2">Butuh persetujuan segera</p>
            </div>
            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded font-medium">Pending</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 mb-1">Laporan Kerusakan Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $laporanAktif }}</h3>
                <p class="text-xs text-gray-400 mt-2">Fasilitas butuh maintenance</p>
            </div>
            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-medium">Urgent</span>
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Chart Section -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
        <h3 class="font-bold text-gray-800 mb-1">Aktivitas Reservasi (7 Hari Terakhir)</h3>
        <p class="text-xs text-gray-400 mb-4">Statistik volume peminjaman fasilitas per hari</p>

        <div class="relative w-full h-[250px] block">
            <canvas id="reservasiChart"></canvas>
        </div>
    </div>

    <!-- Right Side Stats -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Laporan Kerusakan Fasilitas</h3>
            <div class="grid grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Baru</p>
                    <p class="text-xl font-bold text-red-500">{{ $statusLaporan['baru'] ?? 0 }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Diproses</p>
                    <p class="text-xl font-bold text-yellow-500">{{ $statusLaporan['diproses'] ?? 0 }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Selesai</p>
                    <p class="text-xl font-bold text-green-500">{{ $statusLaporan['selesai'] ?? 0 }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                    <p class="text-xl font-bold text-gray-500">{{ $statusLaporan['ditolak'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Facility Status Bar -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Status Seluruh Fasilitas</h3>
            @php $total = $aktif + $perbaikan + $inaktif ?: 1; @endphp
            <div class="w-full bg-gray-200 rounded-full h-3 mb-3 flex overflow-hidden">
                <div class="bg-green-500 h-3" style="width: {{ ($aktif / $total) * 100 }}%"></div>
                <div class="bg-yellow-400 h-3" style="width: {{ ($perbaikan / $total) * 100 }}%"></div>
                <div class="bg-gray-500 h-3" style="width: {{ ($inaktif / $total) * 100 }}%"></div>
            </div>

            <div class="flex justify-between text-xs text-gray-500">
                <span><i class="fas fa-circle text-green-500 text-[8px] mr-1"></i> Aktif ({{ $aktif }})</span>
                <span><i class="fas fa-circle text-yellow-400 text-[8px] mr-1"></i> Perbaikan ({{ $perbaikan }})</span>
                <span><i class="fas fa-circle text-gray-500 text-[8px] mr-1"></i> Inaktif ({{ $inaktif }})</span>
            </div>
        </div>
    </div>
</div>

<!-- Activity Timeline -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">Aktivitas Terbaru Sistem</h3>
    <ul class="space-y-4">
        @forelse($activities as $activity)
        <li class="flex items-center text-sm">
            <span class="w-2 h-2 {{ $activity['type'] == 'reservasi' ? 'bg-blue-500' : 'bg-red-500' }} rounded-full mr-3"></span>
            <p class="text-gray-700 flex-1">{{ $activity['description'] }}</p>
            <span class="text-gray-400 text-xs">{{ $activity['created_at']->diffForHumans() }}</span>
        </li>
        @empty
        <li class="text-sm text-gray-500">Belum ada aktivitas terbaru.</li>
        @endforelse
    </ul>
</div>

<!-- Chart Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('reservasiChart');

        if (!canvas) {
            console.error("Canvas error: ID 'reservasiChart' not found in the DOM.");
            return;
        }

        const labels = {
            !!json_encode($chartLabels ?? []) !!
        };
        const data = {
            !!json_encode($chartData ?? []) !!
        };

        if (labels.length === 0 || data.length === 0) {
            console.warn("Chart data is empty. Did the database seeder run correctly?");
        }

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Reservasi',
                    data: data,
                    backgroundColor: '#0ea5e9',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false,
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection