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
}