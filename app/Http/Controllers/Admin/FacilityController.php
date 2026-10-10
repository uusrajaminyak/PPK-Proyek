<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a dynamic listing of facilities with real-time search, filter, and sort capabilities.
     */
    public function index(Request $request)
    {
        $query = Facility::query();

        // 1. Search Filter (Case-insensitive ILIKE for PostgreSQL)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_fasilitas', 'ilike', "%{$search}%")
                    ->orWhere('lokasi', 'ilike', "%{$search}%");
            });
        }

        // 2. Status Filter (Queries physical PostgreSQL column: status_fasilitas)
        if ($request->filled('status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            if ($request->status === 'aktif') {
                $query->where('status_fasilitas', 'active');
            } else {
                $query->whereIn('status_fasilitas', ['inactive', 'in_repair']);
            }
        }

        // 3. Category Filter (Queries physical PostgreSQL column: tipe)
        if ($request->filled('kategori') && $request->kategori !== '') {
            $query->where('tipe', $request->kategori);
        }

        // 4. Sort Direction Toggle (Default ASC)
        $sortDir = strtolower($request->get('sort', 'asc')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy('nama_fasilitas', $sortDir);

        // Paginate 6 items per page with preserved query parameters
        $facilities = $query->paginate(6)->withQueryString();

        return view('admin.facilities.index', compact('facilities', 'sortDir'));
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
    public function create()
    {
        $kategoriOptions = ['Ruang Kelas', 'Aula', 'Laboratorium', 'Lapangan', 'Ruang Rapat'];
        return view('admin.facilities.create', compact('kategoriOptions'));
    }

    /**
     * Store a newly created facility in PostgreSQL storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'kategori'       => ['required', 'string', 'max:255'],
            'lokasi'         => ['required', 'string', 'max:255'],
            'kapasitas'      => ['nullable', 'integer', 'min:0'],
            'deskripsi'      => ['nullable', 'string'],
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('facilities', 'public');
        }

        $status = $request->has('is_active') || $request->input('status_fasilitas') === 'active' ? 'active' : 'inactive';

        Facility::create([
            'nama_fasilitas'   => $validated['nama_fasilitas'],
            'tipe'             => $validated['kategori'],
            'lokasi'           => $validated['lokasi'],
            'kapasitas'        => $validated['kapasitas'] ?? 0,
            'deskripsi'        => $validated['deskripsi'] ?? null,
            'foto_path'        => $fotoPath,
            'status_fasilitas' => $status,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    /**
     * Show form for editing an existing facility.
     */
    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        $kategoriOptions = ['Ruang Kelas', 'Aula', 'Laboratorium', 'Lapangan', 'Ruang Rapat'];

        return view('admin.facilities.edit', compact('facility', 'kategoriOptions'));
    }

    /**
     * Update an existing facility in PostgreSQL storage.
     */
    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'kategori'       => ['required', 'string', 'max:255'],
            'lokasi'         => ['required', 'string', 'max:255'],
            'kapasitas'      => ['nullable', 'integer', 'min:0'],
            'deskripsi'      => ['nullable', 'string'],
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPath = $facility->foto_path;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('facilities', 'public');
        }

        $status = $request->has('is_active') || $request->input('status_fasilitas') === 'active' ? 'active' : 'inactive';

        $facility->update([
            'nama_fasilitas'   => $validated['nama_fasilitas'],
            'tipe'             => $validated['kategori'],
            'lokasi'           => $validated['lokasi'],
            'kapasitas'        => $validated['kapasitas'] ?? 0,
            'deskripsi'        => $validated['deskripsi'] ?? null,
            'foto_path'        => $fotoPath,
            'status_fasilitas' => $status,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Data fasilitas berhasil diperbarui.');
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
