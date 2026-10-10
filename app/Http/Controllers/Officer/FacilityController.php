<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFacilityStatusRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display operational status of facilities for officers (FR-12).
     */
    public function index(Request $request): View
    {
        $query = Facility::withCount([
            'reports',
            'reports as active_reports_count' => function ($q) {
                $q->whereIn('status_laporan', ['baru', 'diproses']);
            }
        ])->with(['activeReports' => function ($q) {
            $q->latest();
        }]);

        // Filter by status if provided
        $status = $request->query('status');
        if ($status && in_array($status, ['active', 'in_repair', 'inactive'])) {
            $query->where('status_fasilitas', $status);
        }

        $facilities = $query->orderBy('nama_fasilitas')->get();

        $counts = [
            'all' => Facility::count(),
            'active' => Facility::where('status_fasilitas', 'active')->count(),
            'in_repair' => Facility::where('status_fasilitas', 'in_repair')->count(),
            'inactive' => Facility::where('status_fasilitas', 'inactive')->count(),
        ];

        return view('officer.facilities.index', compact('facilities', 'counts', 'status'));
    }

    /**
     * Update the operational status of a facility (FR-12).
     */
    public function updateStatus(UpdateFacilityStatusRequest $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validated();
        $newStatus = $validated['status_fasilitas'];

        $facility->update([
            'status_fasilitas' => $newStatus,
        ]);

        $message = $newStatus === 'in_repair'
            ? "Fasilitas \"{$facility->nama_fasilitas}\" berhasil ditandai Dalam Perbaikan (in_repair)."
            : "Fasilitas \"{$facility->nama_fasilitas}\" berhasil dikembalikan ke status Aktif (active).";

        return redirect()->back()->with('success', $message);
    }
}

