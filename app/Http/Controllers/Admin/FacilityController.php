<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacilityController extends Controller
{
    /**
     * Display a dynamic listing of facilities from MySQL database.
     */
    public function index(Request $request)
    {
        try {
            $query = Facility::query();

            // 1. Search Filter (nama_fasilitas & lokasi)
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('nama_fasilitas', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            // 2. Status Filter (is_active boolean)
            if ($request->filled('status') && $request->status !== 'Semua') {
                if ($request->status === 'aktif' || $request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'nonaktif' || $request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            // 3. Category Filter
            if ($request->filled('kategori') && $request->kategori !== 'Semua') {
                $query->where('kategori', $request->kategori);
            }

            // Paginate 6 items per page (fits 3x2 grid layout)
            $facilities = $query->latest()->paginate(6)->withQueryString();

        } catch (\Exception $e) {
            Log::error('FacilityController@index Database Exception: ' . $e->getMessage());
            $facilities = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6);
        }

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Display detailed information for a single facility.
     */
    public function show($id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.show', compact('facility'));
    }

    public function create()
    {
        $kategoriOptions = ['Ruang Kelas', 'Aula', 'Laboratorium', 'Lapangan', 'Ruang Rapat'];
        return view('admin.facilities.create', compact('kategoriOptions'));
    }

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

        Facility::create([
            'nama_fasilitas' => $validated['nama_fasilitas'],
            'kategori'       => $validated['kategori'],
            'lokasi'         => $validated['lokasi'],
            'kapasitas'      => $validated['kapasitas'] ?? 0,
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'foto'           => $fotoPath,
            'is_active'      => $request->has('is_active'),
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }
}