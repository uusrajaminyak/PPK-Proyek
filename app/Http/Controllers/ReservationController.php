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

    public function create(Request $request, Facility $facility)
    {
        $canReserve = Auth::check() && $facility->status_fasilitas === 'active';

        $timeOptions = $this->getTimeOptions();

        // Offset minggu: 0 = minggu berjalan saat ini, 1 = minggu depan, dst.
        // Tidak boleh bernilai negatif (tidak bisa melihat riwayat minggu lalu)
        $weekOffset = max(0, (int) $request->query('week_offset', 0));

        // Tentukan rentang Senin s/d Minggu berdasarkan offset
        $startOfWeek = now()->startOfWeek()->addWeeks($weekOffset);
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // Susun 7 hari dalam minggu tersebut
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'date_ymd' => $day->format('Y-m-d'),
                'date_dmy' => $day->format('d/m/Y'),
                'label' => $day->isoFormat('ddd, D MMM'),
                'is_past' => $day->isPast() && ! $day->isToday(),
            ];
        }

        // Ambil reservasi aktif hanya di rentang minggu yang ditampilkan
        $bookedReservations = Reservation::where('facility_id', $facility->id)
            ->whereIn('status_reservasi', ['pending', 'approved'])
            ->where('start_time', '<=', $endOfWeek)
            ->where('end_time', '>=', $startOfWeek)
            ->get(['start_time', 'end_time']);

        return view('reservations.create', compact(
            'facility',
            'canReserve',
            'timeOptions',
            'bookedReservations',
            'weekOffset',
            'startOfWeek',
            'weekDays'
        ));
    }

    public function getScheduleData(Request $request, Facility $facility)
    {
        $today = now()->startOfDay();
        $currentMonthStart = now()->startOfMonth();

        if ($request->filled('start_date')) {
            try {
                $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
            } catch (\Exception $e) {
                $startDate = now()->startOfWeek();
            }
        } else {
            $startDate = now()->startOfWeek();
        }

        $thisWeekMonday = now()->startOfWeek();
        if ($startDate->lt($thisWeekMonday)) {
            $startDate = $thisWeekMonday->copy();
        }

        $endDate = $startDate->copy()->addDays(6)->endOfDay();

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $weekDays[] = [
                'date_ymd' => $day->format('Y-m-d'),
                'date_dmy' => $day->format('d/m/Y'),
                'day_name' => $day->isoFormat('dddd'),
                'day_num' => $day->format('d'),
                'label' => $day->isoFormat('ddd, D MMM'),
                'is_past' => $day->lt($today),
                'is_today' => $day->isToday(),
            ];
        }

        $bookedReservations = Reservation::where('facility_id', $facility->id)
            ->whereIn('status_reservasi', ['pending', 'approved'])
            ->where('start_time', '<=', $endDate)
            ->where('end_time', '>=', $startDate)
            ->get(['start_time', 'end_time'])
            ->map(function ($r) {
                return [
                    'start' => $r->start_time->format('Y-m-d H:i'),
                    'end' => $r->end_time->format('Y-m-d H:i'),
                ];
            });

        // Gunakan hari ke-4 dari rentang hari aktif untuk menentukan bulan yang sedang dilihat
        $targetMonth = $startDate->copy()->addDays(3);
        $monthLabel = $targetMonth->isoFormat('MMMM Y');

        // Navigasi bulan: Langsung lompat ke tanggal 1 di bulan berikutnya / sebelumnya
        $nextMonthDate = $targetMonth->copy()->addMonth()->startOfMonth();
        $prevMonthDate = $targetMonth->copy()->subMonth()->startOfMonth();

        // Pastikan tidak melompat ke tanggal lampau dari minggu berjalan saat ini
        if ($prevMonthDate->lt($thisWeekMonday)) {
            $prevMonthDate = $thisWeekMonday->copy();
        }

        $canPrevDays = $startDate->gt($thisWeekMonday);
        $canPrevMonth = $targetMonth->copy()->startOfMonth()->gt($currentMonthStart);

        return response()->json([
            'month_label' => $monthLabel,
            'current_month_ym' => $targetMonth->format('Y-m'),
            'start_date' => $startDate->format('Y-m-d'),
            'prev_week_date' => $startDate->copy()->subDays(7)->format('Y-m-d'),
            'next_week_date' => $startDate->copy()->addDays(7)->format('Y-m-d'),
            'prev_month_date' => $prevMonthDate->format('Y-m-d'),
            'next_month_date' => $nextMonthDate->format('Y-m-d'),
            'can_prev_days' => $canPrevDays,
            'can_prev_month' => $canPrevMonth,
            'week_days' => $weekDays,
            'time_options' => $this->getTimeOptions(),
            'booked' => $bookedReservations,
        ]);
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

        $start = Carbon::parse($request->tanggal.' '.$request->slot_start);
        $end = Carbon::parse($request->tanggal.' '.$request->slot_end);

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

        if (! $reservation->canBeCancelled()) {
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
