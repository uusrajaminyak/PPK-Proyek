<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
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

        return redirect()->route('reports.create')->with('success', 'Laporan kerusakan berhasil dikirim! Petugas akan segera memverifikasi laporan Anda.');
    }
}

