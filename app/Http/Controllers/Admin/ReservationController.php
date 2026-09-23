<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility; 
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // get data for facility dropdown filter
        $facilities = Facility::select('id', 'nama_fasilitas')->orderBy('nama_fasilitas')->get();

        // basic query for reservations with eager loading of related user and facility
        $query = Reservation::with(['user', 'facility']);

        // search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('tujuan_penggunaan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // facility filter
        if ($request->filled('fasilitas') && $request->fasilitas !== 'Semua') {
            $query->where('facility_id', $request->fasilitas);
        }

        // status filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // time filter
        $sortOrder = $request->waktu === 'terlama' ? 'asc' : 'desc';
        $query->orderBy('start_time', $sortOrder);

        // Executing the query and paginating results
        $reservations = $query->paginate(10)->withQueryString();

        return view('admin.reservations.index', compact('reservations', 'facilities'));
    }
}
