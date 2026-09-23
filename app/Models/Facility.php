<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'status_fasilitas',
    ];

    /**
     * Reports associated with this facility.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Reservations associated with this facility.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if facility is active.
     */
    public function isActive(): bool
    {
        return $this->status_fasilitas === 'active';
    }

    /**
     * Check if facility is under repair.
     */
    public function isInRepair(): bool
    {
        return $this->status_fasilitas === 'in_repair';
    }
}

