<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_fasilitas',
        'kategori',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas' => 'integer',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}