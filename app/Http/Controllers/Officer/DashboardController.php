<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isPetugas() || auth()->user()->isAdmin(), 403);

        $pendingReservationsCount = Reservation::where('status_reservasi', 'pending')->count();
        $incomingReportsCount = Report::whereIn('status_laporan', ['baru', 'diproses'])->count();
        $facilitiesInRepairCount = Facility::where('status_fasilitas', 'in_repair')->count();

        $pendingReservations = Reservation::query()
            ->with(['user:id,name', 'facility:id,nama_fasilitas,lokasi'])
            ->where('status_reservasi', 'pending')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('officer.dashboard', compact(
            'pendingReservationsCount',
            'incomingReportsCount',
            'facilitiesInRepairCount',
            'pendingReservations',
        ));
    }
}
