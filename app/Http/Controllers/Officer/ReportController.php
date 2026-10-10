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
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:baru,diproses,selesai,ditolak'],
            'jenis' => ['nullable', 'in:Kerusakan/Kendala Fasilitas,Kebersihan Fasilitas,Gangguan Internet'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'urut' => ['nullable', 'in:terbaru,terlama'],
        ]);

        $reportsQuery = Report::query()
            ->with(['facility', 'reporter'])
            ->when($filters['search'] ?? null, function ($reports, string $search): void {
                $search = trim($search);
                $hasFormattedId = preg_match('/^LP-(\d+)$/i', $search, $idMatch) === 1;
                $reportId = $hasFormattedId
                    ? (int) $idMatch[1]
                    : (ctype_digit($search) ? (int) $search : null);

                $reports->where(function ($reportQuery) use ($search, $reportId): void {
                    $reportQuery
                        ->where('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('reporter', fn ($reporters) => $reporters->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('facility', fn ($facilities) => $facilities->where('nama_fasilitas', 'like', "%{$search}%"));

                    if ($reportId !== null) {
                        $reportQuery->orWhereKey($reportId);
                    }
                });
            })
            ->when($filters['status'] ?? null, fn ($reports, string $status) => $reports->where('status_laporan', $status))
            ->when($filters['jenis'] ?? null, function ($reports, string $type): void {
                $legacyTypes = match ($type) {
                    'Kerusakan/Kendala Fasilitas' => ['Hardware', 'Fasilitas Umum', 'Kelistrikan', 'Struktur Bangunan', 'Lainnya'],
                    'Kebersihan Fasilitas' => ['Kebersihan'],
                    default => [],
                };

                $reports->where(function ($query) use ($type, $legacyTypes): void {
                    $query->where('kategori_laporan', $type);

                    if ($legacyTypes !== []) {
                        $query->orWhereIn('kategori_laporan', $legacyTypes);
                    }
                });
            })
            ->when($filters['tanggal_mulai'] ?? null, fn ($reports, string $date) => $reports->whereDate('created_at', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn ($reports, string $date) => $reports->whereDate('created_at', '<=', $date));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $reports = $reportsQuery
            ->orderBy('created_at', $sortDirection)
            ->orderBy('id', $sortDirection)
            ->paginate(8)
            ->withQueryString();

        return view('officer.reports.index', compact('reports', 'filters'));
    }

    /** Display a single report for the officer (FR-11). */
    public function show(Report $report): View
    {
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        $report->load(['facility', 'reporter']);

        return view('officer.officer-report-detail', compact('report'));
    }

    /**
     * Update the status and resolution note of a damage report (FR-11),
     * and optionally update facility status (FR-12).
     */
    public function update(UpdateReportStatusRequest $request, Report $report): RedirectResponse
    {
        abort_unless(auth()->user()?->isPetugas() || auth()->user()?->isAdmin(), 403);

        $validated = $request->validated();

        $report->update([
            'status_laporan' => $validated['status_laporan'],
            'catatan_resolusi' => $validated['catatan_resolusi'] ?? $report->catatan_resolusi,
        ]);

        // FR-12: Tandai status fasilitas terkait jika dipilih oleh petugas
        $facilityMessage = '';
        if (! empty($validated['update_facility_status'])) {
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
