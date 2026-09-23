<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query();

        // Search Filter
        if ($request->filled('search')) {
            $query->where('nama_fasilitas', 'like', '%' . $request->search . '%');
        }

        // Dropdown Filters
        if ($request->filled('tipe') && $request->tipe !== 'Semua') {
            $query->where('tipe', $request->tipe);
        }
        
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_fasilitas', $request->status);
        }

        $facilities = $query->paginate(9)->withQueryString();

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        $tipeOptions = ['Ruang Kelas', 'Aula Serbaguna', 'Laboratorium', 'Lapangan Olahraga', 'Ruang Rapat'];
        return view('admin.facilities.create', compact('tipeOptions'));
    }

    public function store(Request $request)
    {
        // Strict Server-Side Validation
        $validated = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
        ]);

        // Handle File Upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('facilities', 'public');
            $fotoPath = '/storage/' . $path;
        }

        // Store Facility
        Facility::create([
            'nama_fasilitas' => $validated['nama_fasilitas'],
            'tipe' => $validated['tipe'],
            'lokasi' => $validated['lokasi'],
            'kapasitas' => $validated['kapasitas'] ?? 0, 
            'deskripsi' => $validated['deskripsi'],
            'foto_path' => $fotoPath,
            'status_fasilitas' => $request->has('status_aktif') ? 'active' : 'inactive',
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }
}