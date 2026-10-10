<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    private int $slotDuration = 30;
    private string $operatingStart = '07:00';
    private string $operatingEnd = '20:00';

    public function create(Facility $facility)
    {
        if ($facility->status_fasilitas !== 'active') {
            return redirect()->route('home')->with('errors', collect(['Fasilitas sedang tidak aktif atau dalam perbaikan.']));
        }

        $timeOptions = $this->getTimeOptions();

        return view('reservations.create', compact('facility', 'timeOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'slot_start' => ['required', 'date_format:H:i'],
            'slot_end' => ['required', 'date_format:H:i', 'after:slot_start'],
            'tujuan_penggunaan' => ['required', 'string', 'max:500'],
        ], [
            'slot_end.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
        ]);

        $facility = Facility::findOrFail($request->facility_id);

        if ($facility->status_fasilitas !== 'active') {
            return back()->withErrors(['facility_id' => 'Fasilitas tidak tersedia.'])->withInput();
        }

        $start = Carbon::parse($request->tanggal . ' ' . $request->slot_start);
        $end = Carbon::parse($request->tanggal . ' ' . $request->slot_end);

        if ($start->isPast()) {
            return back()->withErrors(['slot_start' => 'Tidak bisa memilih waktu yang sudah lewat.'])->withInput();
        }

        $durationMinutes = $start->diffInMinutes($end);
        if ($durationMinutes < 30 || $durationMinutes % 30 !== 0) {
            return back()->withErrors(['slot_end' => 'Durasi pemesanan harus dalam kelipatan 30 menit (misal: 30 menit, 60 menit, 90 menit, dst).'])->withInput();
        }

        // Cek anti-bentrok
        $isConflict = Reservation::where('facility_id', $facility->id)
            ->whereIn('status_reservasi', ['pending', 'approved'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });
            })
            ->exists();

        if ($isConflict) {
            return back()->withErrors(['slot_start' => 'Rentang waktu ini bertabrakan dengan jadwal yang sudah dipesan. Silakan pilih waktu lain.'])->withInput();
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $facility->id,
            'tujuan_penggunaan' => $request->tujuan_penggunaan,
            'start_time' => $start,
            'end_time' => $end,
            'status_reservasi' => 'pending',
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil diajukan! Menunggu persetujuan.');
    }

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('facility')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if (!$reservation->canBeCancelled()) {
            return back()->withErrors(['cancel' => 'Reservasi hanya bisa dibatalkan maksimal 24 jam sebelum jadwal (H-1) dan berstatus pending.']);
        }

        $reservation->update([
            'status_reservasi' => 'cancelled_by_user',
            'alasan_pembatalan' => $request->input('alasan_pembatalan', 'Dibatalkan oleh pemohon'),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibatalkan.');
    }

    private function getTimeOptions(): array
    {
        $times = [];
        $current = Carbon::parse($this->operatingStart);
        $end = Carbon::parse($this->operatingEnd);

        while ($current <= $end) {
            $times[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $times;
    }
}