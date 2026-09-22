<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //KPI Cards Data
        $totalPengguna = User::where('role', 'pengguna')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalFasilitas = Facility::count();

        $reservasiAktif = Reservation::whereDate('start_time', Carbon::today())
            ->where('status_reservasi', 'approved')
            ->count();
        $reservasiMenunggu = Reservation::where('status_reservasi', 'pending')->count();
        $laporanAktif = Report::whereIn('status_laporan', ['baru', 'diproses'])->count();

        //Chart Data (Reservations last 7 days)
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D'); // Sen, Sel, Rab...
            $chartData[] = Reservation::whereDate('created_at', $date)->count();
        }

        //Facility Status Progress Bar
        $statusFasilitas = Facility::selectRaw('status_fasilitas, count(*) as count')
            ->groupBy('status_fasilitas')
            ->pluck('count', 'status_fasilitas')->toArray();

        $aktif = $statusFasilitas['active'] ?? 0;
        $perbaikan = $statusFasilitas['in_repair'] ?? 0;
        $inaktif = $statusFasilitas['inactive'] ?? 0;

        //Report Status Breakdown
        $statusLaporan = Report::selectRaw('status_laporan, count(*) as count')
            ->groupBy('status_laporan')
            ->pluck('count', 'status_laporan')->toArray();

        //Recent Activity Timeline (Merge & Sort)
        $recentReservations = Reservation::with(['user', 'facility'])->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'reservasi',
                'description' => "Reservasi baru {$item->facility->nama_fasilitas} oleh {$item->user->nama}",
                'created_at' => $item->created_at
            ];
        });

        $recentReports = Report::with(['reporter', 'facility'])->latest()->take(5)->get()->map(function ($item) {
            return [
                'type' => 'laporan',
                'description' => "Laporan kerusakan {$item->facility->nama_fasilitas} berstatus '{$item->status_laporan}'",
                'created_at' => $item->created_at
            ];
        });

        $activities = $recentReservations->concat($recentReports)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalPetugas',
            'totalFasilitas',
            'reservasiAktif',
            'reservasiMenunggu',
            'laporanAktif',
            'chartLabels',
            'chartData',
            'aktif',
            'perbaikan',
            'inaktif',
            'statusLaporan',
            'activities'
        ));
    }
}
