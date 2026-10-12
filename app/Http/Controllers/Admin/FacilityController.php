<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a dynamic listing of facilities with real-time search, filter, and sort capabilities.
     */
    public function index(Request $request): View
    {
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
            ->when($filters['status'] ?? null, fn($facilities, string $status) => $facilities->where('status_fasilitas', $status))
            ->when($filters['tipe'] ?? null, fn($facilities, string $type) => $facilities->where('tipe', $type));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $facilities = $facilitiesQuery
            ->orderBy('created_at', $sortDirection)
            ->orderBy('id', $sortDirection)
            ->paginate(10)
            ->withQueryString();

        $facilityTypes = Facility::query()
            ->whereNotNull('tipe')
            ->where('tipe', '!=', '')
            ->distinct()
            ->orderBy('tipe')
            ->pluck('tipe');

        return view('admin.facilities.index', compact('facilities', 'facilityTypes', 'filters'));
    }

    /**
     * Display detailed information for a single facility.
     */
    public function show($id)
    {
        $facility = Facility::findOrFail($id);

        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show form for creating a new facility.
     */
    public function create(): View
    {
        $tipeOptions = [
            'Ruang Kelas',
            'Aula',
            'Laboratorium',
            'Lapangan',
            'Ruang Rapat',
        ];

        return view('admin.facilities.create', compact('tipeOptions'));
    }

    /**
     * Store a newly created facility in PostgreSQL storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status_fasilitas' => ['required', 'in:active,inactive'],
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('facilities', 'public');
        }

        Facility::create([
            'nama_fasilitas' => $validated['nama_fasilitas'],
            'tipe' => $validated['tipe'],
            'lokasi' => $validated['lokasi'],
            'kapasitas' => $validated['kapasitas'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto_path' => $fotoPath,
            'status_fasilitas' => $validated['status_fasilitas'],
        ]);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    /**
     * Show form for editing an existing facility.
     */
    public function edit(int $id): View
    {
        $facility = Facility::findOrFail($id);

        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update an existing facility in PostgreSQL storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status_fasilitas' => ['sometimes', 'required', 'in:active,in_repair,inactive'],
        ]);

        $fotoPath = $facility->foto_path;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('facilities', 'public');
        }

        $status = $validated['status_fasilitas'] ?? $facility->status_fasilitas;

        $facility->update([
            'nama_fasilitas' => $validated['nama_fasilitas'],
            'tipe' => $validated['kategori'],
            'lokasi' => $validated['lokasi'],
            'kapasitas' => $validated['kapasitas'] ?? 0,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto_path' => $fotoPath,
            'status_fasilitas' => $status,
        ]);

        return redirect()->route('admin.facilities.show', $facility)->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified facility from storage (Soft Delete).
     */
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dinonaktifkan/dihapus.');
    }
}
