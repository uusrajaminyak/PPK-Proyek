<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of all damage reports for the officer queue (FR-11).
     */
    public function index(Request $request): View
    {
        $query = Report::with(['facility', 'reporter']);

        // Calculate count per status for dashboard counters
        $counts = [
            'all' => Report::count(),
            'baru' => Report::where('status_laporan', 'baru')->count(),
            'diproses' => Report::where('status_laporan', 'diproses')->count(),
            'selesai' => Report::where('status_laporan', 'selesai')->count(),
            'ditolak' => Report::where('status_laporan', 'ditolak')->count(),
        ];

        // Apply status filter if provided
        $status = $request->query('status');
        if ($status && in_array($status, ['baru', 'diproses', 'selesai', 'ditolak'])) {
            $query->where('status_laporan', $status);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('officer.reports.index', compact('reports', 'counts', 'status'));
    }

    /**
     * Update the status and resolution note of a damage report (FR-11),
     * and optionally update facility status (FR-12).
     */
    public function update(UpdateReportStatusRequest $request, Report $report): RedirectResponse
    {
        $validated = $request->validated();

        $report->update([
            'status_laporan' => $validated['status_laporan'],
            'catatan_resolusi' => $validated['catatan_resolusi'] ?? $report->catatan_resolusi,
        ]);

        // FR-12: Tandai status fasilitas terkait jika dipilih oleh petugas
        $facilityMessage = '';
        if (!empty($validated['update_facility_status'])) {
            if ($validated['update_facility_status'] === 'in_repair') {
                $report->facility->markInRepair();
                $facilityMessage = " dan fasilitas \"{$report->facility->nama_fasilitas}\" ditandai Dalam Perbaikan";
            } elseif ($validated['update_facility_status'] === 'active') {
                $report->facility->markActive();
                $facilityMessage = " dan fasilitas \"{$report->facility->nama_fasilitas}\" dikembalikan ke status Aktif";
            }
        }

        $statusLabel = [
            'baru' => 'Baru',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ][$validated['status_laporan']] ?? $validated['status_laporan'];

        return redirect()->back()->with('success', "Status laporan #{$report->id} berhasil diperbarui menjadi \"{$statusLabel}\"{$facilityMessage}.");
    }
}
