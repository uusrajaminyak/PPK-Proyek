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

    /**
     * Get attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * The user who made the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The reserved facility.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}

