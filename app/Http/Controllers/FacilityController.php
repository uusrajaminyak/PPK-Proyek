<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,in_repair,inactive'],
            'tipe' => ['nullable', 'string', 'max:100'],
            'urut' => ['nullable', 'in:terbaru,terlama'],
        ]);

        $facilitiesQuery = Facility::query()
            ->visibleToNonAdmins()
            ->when($filters['search'] ?? null, function ($facilities, string $search): void {
                $search = trim($search);
                $facilities->where(function ($query) use ($search): void {
                    $query->where('nama_fasilitas', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($facilities, string $status) => $facilities->where('status_fasilitas', $status))
            ->when($filters['tipe'] ?? null, fn ($facilities, string $type) => $facilities->where('tipe', $type));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $facilities = $facilitiesQuery
            ->orderBy('created_at', $sortDirection)
            ->orderBy('id', $sortDirection)
            ->paginate(6)
            ->withQueryString();

        // Data untuk dropdown filter
        $tipeList = Facility::query()
            ->whereNotNull('tipe')
            ->where('tipe', '!=', '')
            ->where('status_fasilitas', '!=', 'inactive')
            ->distinct()
            ->orderBy('tipe')
            ->pluck('tipe');

        return view('facilities', compact('facilities', 'filters', 'tipeList'));
    }
}
