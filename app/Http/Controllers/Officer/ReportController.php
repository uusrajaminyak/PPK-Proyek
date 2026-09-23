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
     * Update the status and resolution note of a damage report (FR-11).
     */
    public function update(UpdateReportStatusRequest $request, Report $report): RedirectResponse
    {
        $validated = $request->validated();

        $report->update([
            'status_laporan' => $validated['status_laporan'],
            'catatan_resolusi' => $validated['catatan_resolusi'] ?? $report->catatan_resolusi,
        ]);

        $statusLabel = [
            'baru' => 'Baru',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ][$validated['status_laporan']] ?? $validated['status_laporan'];

        return redirect()->back()->with('success', "Status laporan #{$report->id} berhasil diperbarui menjadi \"{$statusLabel}\".");
    }
}

