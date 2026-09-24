<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];
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

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

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

    public function canBeCancelled(): bool
    {
        if ($this->status_reservasi !== 'pending') {
            return false;
        }

        return now()->diffInHours($this->start_time, false) >= 2;
    }
}