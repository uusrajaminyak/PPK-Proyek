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
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,in_repair,inactive'],
            'tipe' => ['nullable', 'string', 'max:100'],
            'urut' => ['nullable', 'in:terbaru,terlama'],
        ]);

        $facilitiesQuery = Facility::query()
            ->when($filters['search'] ?? null, function ($facilities, string $search): void {
                $search = trim($search);
                $facilities->where(function ($query) use ($search): void {
                    $query->where('nama_fasilitas', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($facilities, string $status) => $facilities->where('status_fasilitas', $status))
            ->when($filters['tipe'] ?? null, fn ($facilities, string $type) => $facilities->where('tipe', $type));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $facilities = $facilitiesQuery
            ->orderBy('created_at', $sortDirection)
            ->orderBy('id', $sortDirection)
            ->paginate(6)
            ->withQueryString();

        $facilityTypes = Facility::query()
            ->whereNotNull('tipe')
            ->where('tipe', '!=', '')
            ->distinct()
            ->orderBy('tipe')
            ->pluck('tipe');

        return view('officer.facilities.index', compact('facilities', 'facilityTypes', 'filters'));
    }

    /** Display detailed facility information for officers (FR-12). */
    public function show(Facility $facility): View
    {
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        return view('officer.facilities.show', compact('facility'));
    }

    /**
     * Update the operational status of a facility (FR-12).
     */
    public function updateStatus(UpdateFacilityStatusRequest $request, Facility $facility): RedirectResponse
    {
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        $validated = $request->validated();
        $newStatus = $validated['status_fasilitas'];

        if ($newStatus === 'in_repair') {
            $facility->markInRepair();
        } else {
            $facility->markActive();
        }

        $message = $newStatus === 'in_repair'
            ? "Fasilitas \"{$facility->nama_fasilitas}\" berhasil ditandai Dalam Perbaikan (in_repair)."
            : "Fasilitas \"{$facility->nama_fasilitas}\" berhasil dikembalikan ke status Aktif (active).";

        return redirect()->back()->with('success', $message);
    }
}
