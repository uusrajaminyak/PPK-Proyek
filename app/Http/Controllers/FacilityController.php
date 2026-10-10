<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['nama', 'tipe', 'lokasi', 'kapasitas_min']);
        
        // Hapus filter kosong
        $filters = array_filter($filters, fn($v) => $v !== '' && $v !== null);

        $facilitiesQuery = Facility::query();

        if (! empty($filters['nama'])) {
            $facilitiesQuery->where('nama_fasilitas', 'like', "%{$filters['nama']}%");
        }

        if (! empty($filters['tipe'])) {
            $facilitiesQuery->where('tipe', $filters['tipe']);
        }

        if (! empty($filters['lokasi'])) {
            $facilitiesQuery->where('lokasi', 'like', "%{$filters['lokasi']}%");
        }

        if (! empty($filters['kapasitas_min'])) {
            $facilitiesQuery->where('kapasitas', '>=', $filters['kapasitas_min']);
        }

        $facilities = $facilitiesQuery
            ->orderBy('nama_fasilitas')
            ->paginate(12)
            ->withQueryString();

        // Data untuk dropdown filter
        $tipeList = Facility::distinct()->pluck('tipe')->filter()->sort()->values();
        $lokasiList = Facility::distinct()->pluck('lokasi')->filter()->sort()->values();

        return view('facilities', compact('facilities', 'filters', 'tipeList', 'lokasiList'));
    }
}