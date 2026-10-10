<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->get('month', 9); // September 2026 default
        $selectedYear  = (int) $request->get('year', 2026);

        // 1. Dynamic Metric Summaries
        $aktif = Facility::where('status_fasilitas', 'active')->count();
        $perbaikan = Facility::where('status_fasilitas', 'in_repair')->count();

        $reservasiMenunggu = Reservation::where('status_reservasi', 'pending')->count();

        $statusLaporan = [
            'baru' => Report::where('status_laporan', 'baru')->count(),
            'diproses' => Report::where('status_laporan', 'diproses')->count(),
        ];

        $totalPengguna = User::where('role', 'pengguna')->count();
        $totalPetugas  = User::where('role', 'petugas')->count();

        // 2. Dynamic Monthly Rekap Aggregations
        $facilities = Facility::withCount([
            'reservations as total_direservasi' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('start_time', $selectedMonth)
                    ->whereYear('start_time', $selectedYear);
            },
            'reports as total_dilaporkan' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('created_at', $selectedMonth)
                    ->whereYear('created_at', $selectedYear);
            }
        ])->get();

        $maxReservations = max($facilities->max('total_direservasi') ?? 1, 1);
        $maxReports      = max($facilities->max('total_dilaporkan') ?? 1, 1);

        $occupancyData = $facilities->map(function ($facility) use ($maxReservations) {
            return [
                'name' => $facility->nama_fasilitas,
                'val'  => $facility->total_direservasi,
                'pct'  => round(($facility->total_direservasi / $maxReservations) * 100),
            ];
        })->sortByDesc('val')->take(6)->values();

        $reportFrequencyData = $facilities->map(function ($facility) use ($maxReports) {
            return [
                'name' => $facility->nama_fasilitas,
                'val'  => $facility->total_dilaporkan,
                'pct'  => round(($facility->total_dilaporkan / $maxReports) * 100),
            ];
        })->sortByDesc('val')->take(6)->values();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $years = [2024, 2025, 2026, 2027];

        return view('admin.dashboard', compact(
            'aktif',
            'perbaikan',
            'reservasiMenunggu',
            'statusLaporan',
            'totalPengguna',
            'totalPetugas',
            'occupancyData',
            'reportFrequencyData',
            'selectedMonth',
            'selectedYear',
            'months',
            'years'
        ));
    }
}
