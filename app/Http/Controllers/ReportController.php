<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of damage reports submitted by the current user (FR-07).
     */
    public function index(Request $request): View
    {
        $reporterId = Auth::id() ?? User::where('role', 'pengguna')->first()?->id ?? 1;

        // Base query for user's reports
        $query = Report::with('facility')->where('reporter_id', $reporterId);

        // Calculate count per status for filter tabs
        $counts = [
            'all' => (clone $query)->count(),
            'baru' => (clone $query)->where('status_laporan', 'baru')->count(),
            'diproses' => (clone $query)->where('status_laporan', 'diproses')->count(),
            'selesai' => (clone $query)->where('status_laporan', 'selesai')->count(),
            'ditolak' => (clone $query)->where('status_laporan', 'ditolak')->count(),
        ];

        // Apply status filter if provided
        $status = $request->query('status');
        if ($status && in_array($status, ['baru', 'diproses', 'selesai', 'ditolak'])) {
            $query->where('status_laporan', $status);
        }

        $reports = $query->latest()->paginate(8)->withQueryString();

        return view('reports.index', compact('reports', 'counts', 'status'));
    }

    /**
     * Show the form for creating a new damage report (FR-06).
     */
    public function create(): View
    {
        $facilities = Facility::where('status_fasilitas', '!=', 'inactive')
            ->orderBy('nama_fasilitas')
            ->get();

        $categories = [
            'Hardware',
            'Fasilitas Umum',
            'Kelistrikan',
            'Kebersihan',
            'Struktur Bangunan',
            'Lainnya',
        ];

        return view('reports.create', compact('facilities', 'categories'));
    }

    /**
     * Store a newly created damage report in storage (FR-06).
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $fotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                // Store in storage/app/public/reports
                $path = $foto->store('reports', 'public');
                $fotoPaths[] = $path;
            }
        }

        // Reporter ID: authenticated user or fallback for seamless testing
        $reporterId = Auth::id() ?? User::where('role', 'pengguna')->first()?->id ?? 1;

        $report = Report::create([
            'reporter_id' => $reporterId,
            'facility_id' => $validated['facility_id'],
            'kategori_laporan' => $validated['kategori_laporan'],
            'deskripsi' => $validated['deskripsi'],
            'foto_paths' => !empty($fotoPaths) ? $fotoPaths : null,
            'status_laporan' => 'baru',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan kerusakan berhasil dikirim! Petugas akan segera memverifikasi laporan Anda.');
    }
}
