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

        $facilities = Facility::search($filters)
            ->orderBy('nama_fasilitas')
            ->paginate(12)
            ->withQueryString();

        // Data untuk dropdown filter
        $tipeList = Facility::distinct()->pluck('tipe')->filter()->sort()->values();
        $lokasiList = Facility::distinct()->pluck('lokasi')->filter()->sort()->values();

        return view('home', compact('facilities', 'filters', 'tipeList', 'lokasiList'));
    }
}