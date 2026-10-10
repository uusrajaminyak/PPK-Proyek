<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isPetugas() || auth()->user()->isAdmin(), 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,approved'],
            'tipe' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'urut' => ['nullable', 'in:terbaru,terlama'],
        ]);

        $query = Reservation::query()
            ->with([
                'user:id,name,fakultas',
                'facility:id,nama_fasilitas,tipe,lokasi',
            ])
            ->where(function (Builder $reservations): void {
                $reservations
                    ->where('status_reservasi', 'pending')
                    ->orWhere(function (Builder $approvedReservations): void {
                        $approvedReservations
                            ->where('status_reservasi', 'approved')
                            ->where('end_time', '>', now());
                    });
            })
            ->when($filters['search'] ?? null, function ($reservations, string $search): void {
                $search = trim($search);
                $hasFormattedId = preg_match('/^RSV-\d{8}-(\d+)$/i', $search, $idMatch) === 1;
                $reservationId = $hasFormattedId
                    ? (int) $idMatch[1]
                    : (ctype_digit($search) ? (int) $search : null);

                $reservations->where(function ($reservationQuery) use ($search, $reservationId): void {
                    $reservationQuery
                        ->where('tujuan_penggunaan', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($users) => $users->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('facility', fn ($facilities) => $facilities->where('nama_fasilitas', 'like', "%{$search}%"));

                    if ($reservationId !== null) {
                        $reservationQuery->orWhere('id', $reservationId);
                    }
                });
            })
            ->when($filters['status'] ?? null, fn ($reservations, string $status) => $reservations->where('status_reservasi', $status))
            ->when($filters['tipe'] ?? null, fn ($reservations, string $type) => $reservations->whereHas('facility', fn ($facilities) => $facilities->where('tipe', $type)))
            ->when($filters['tanggal_mulai'] ?? null, fn ($reservations, string $date) => $reservations->whereDate('start_time', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn ($reservations, string $date) => $reservations->whereDate('start_time', '<=', $date));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $reservations = $query
            ->orderBy('created_at', $sortDirection)
            ->paginate(6)
            ->withQueryString();

        $facilityTypes = Facility::query()
            ->whereNotNull('tipe')
            ->distinct()
            ->orderBy('tipe')
            ->pluck('tipe');

        return view('officer.officer-reservations-management', compact('reservations', 'facilityTypes', 'filters'));
    }

    public function history(Request $request): View
    {
        abort_unless(auth()->user()->isPetugas() || auth()->user()->isAdmin(), 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:completed,cancelled,rejected'],
            'tipe' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'urut' => ['nullable', 'in:terbaru,terlama'],
        ]);

        $query = Reservation::query()
            ->with([
                'user:id,name,fakultas',
                'facility:id,nama_fasilitas,tipe,lokasi',
            ])
            ->where(function ($historyQuery): void {
                $historyQuery
                    ->where(function ($completedQuery): void {
                        $completedQuery
                            ->where('status_reservasi', 'approved')
                            ->where('end_time', '<=', now());
                    })
                    ->orWhere('status_reservasi', 'cancelled_by_user')
                    ->orWhere('status_reservasi', 'cancelled_by_officer');
            })
            ->when($filters['search'] ?? null, function ($reservations, string $search): void {
                $search = trim($search);
                $hasFormattedId = preg_match('/^RSV-\d{8}-(\d+)$/i', $search, $idMatch) === 1;
                $reservationId = $hasFormattedId
                    ? (int) $idMatch[1]
                    : (ctype_digit($search) ? (int) $search : null);

                $reservations->where(function ($reservationQuery) use ($search, $reservationId): void {
                    $reservationQuery
                        ->where('tujuan_penggunaan', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($users) => $users->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('facility', fn ($facilities) => $facilities->where('nama_fasilitas', 'like', "%{$search}%"));

                    if ($reservationId !== null) {
                        $reservationQuery->orWhere('id', $reservationId);
                    }
                });
            })
            ->when($filters['status'] ?? null, function ($reservations, string $status): void {
                if ($status === 'completed') {
                    $reservations->where('status_reservasi', 'approved')->where('end_time', '<=', now());
                } elseif ($status === 'cancelled') {
                    $reservations->where('status_reservasi', 'cancelled_by_user');
                } else {
                    $reservations->where('status_reservasi', 'cancelled_by_officer');
                }
            })
            ->when($filters['tipe'] ?? null, fn ($reservations, string $type) => $reservations->whereHas('facility', fn ($facilities) => $facilities->where('tipe', $type)))
            ->when($filters['tanggal_mulai'] ?? null, fn ($reservations, string $date) => $reservations->whereDate('start_time', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn ($reservations, string $date) => $reservations->whereDate('start_time', '<=', $date));

        $sortDirection = ($filters['urut'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $reservations = $query
            ->orderBy('created_at', $sortDirection)
            ->paginate(6)
            ->withQueryString();

        $facilityTypes = Facility::query()
            ->whereNotNull('tipe')
            ->distinct()
            ->orderBy('tipe')
            ->pluck('tipe');

        return view('officer.officer-reservation-history', compact('reservations', 'facilityTypes', 'filters'));
    }

    public function show(Reservation $reservation): View
    {
        abort_unless(auth()->user()->isPetugas() || auth()->user()->isAdmin(), 403);

        $reservation->load(['user', 'facility']);

        return view('officer.officer-reservation-detail', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        abort_unless(auth()->user()->isPetugas() || auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'status_reservasi' => ['required', 'in:approved,cancelled_by_officer'],
            'alasan_pembatalan' => ['required_if:status_reservasi,cancelled_by_officer', 'nullable', 'string', 'max:1000'],
            'from' => ['nullable', 'in:dashboard,history'],
        ]);

        $statusLabel = $validated['status_reservasi'] === 'approved' ? 'disetujui' : 'ditolak';
        $attributes = [
            'status_reservasi' => $validated['status_reservasi'],
            'alasan_pembatalan' => $validated['status_reservasi'] === 'cancelled_by_officer'
                ? $validated['alasan_pembatalan']
                : null,
        ];

        $detailRouteParameters = array_filter([
            'reservation' => $reservation,
            'from' => $validated['from'] ?? null,
        ]);

        $updated = Reservation::query()
            ->whereKey($reservation->getKey())
            ->where('status_reservasi', 'pending')
            ->update($attributes);

        if ($updated === 0) {
            return redirect()
                ->route('officer.reservations.show', $detailRouteParameters)
                ->with('error', 'Reservasi ini sudah diproses sebelumnya.');
        }

        return redirect()
            ->route('officer.reservations.show', $detailRouteParameters)
            ->with('success', "Reservasi berhasil {$statusLabel}.");
    }
}
