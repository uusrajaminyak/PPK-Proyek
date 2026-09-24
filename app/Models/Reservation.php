<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'tujuan_penggunaan',
        'start_time',
        'end_time',
        'status_reservasi',
        'alasan_pembatalan',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    // ==========================================
    // UI Helpers
    // ==========================================
    public function getStatusBadgeClass(): string
    {
        return match ($this->status_reservasi) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'cancelled_by_user' => 'bg-gray-100 text-gray-700',
            'cancelled_by_officer' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status_reservasi) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'cancelled_by_user' => 'Dibatalkan Pengguna',
            'cancelled_by_officer' => 'Dibatalkan Petugas',
            default => 'Tidak Diketahui',
        };
    }

    // ==========================================
    // Business Logic Helpers
    // ==========================================
    public function canBeCancelled(): bool
    {
        if ($this->status_reservasi !== 'pending') {
            return false;
        }

        // FR-04: Allow cancellation up to 24 hours (or 2 hours based on your team's updated logic) before start_time
        return now()->diffInHours($this->start_time, false) >= 2;
    }

    // ==========================================
    // Relationships
    // ==========================================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
