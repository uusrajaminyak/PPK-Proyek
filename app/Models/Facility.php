<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facilities';
    
    protected $fillable = [
        'nama_fasilitas', 'tipe', 'lokasi', 
        'kapasitas', 'deskripsi', 'status_fasilitas'
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'status_fasilitas' => 'string',
    ];

    // Scope untuk filter di home page
    public function scopeSearch($query, $params)
    {
        if (!empty($params['nama'])) {
            $query->where('nama_fasilitas', 'like', "%{$params['nama']}%");
        }
        if (!empty($params['tipe'])) {
            $query->where('tipe', $params['tipe']);
        }
        if (!empty($params['lokasi'])) {
            $query->where('lokasi', 'like', "%{$params['lokasi']}%");
        }
        if (!empty($params['kapasitas_min'])) {
            $query->where('kapasitas', '>=', $params['kapasitas_min']);
        }
        return $query;
    }

    // Helper untuk badge status
    public function getStatusBadgeClass(): string
    {
        return match($this->status_fasilitas) {
            'active' => 'bg-green-100 text-green-800',
            'in_repair' => 'bg-red-100 text-red-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status_fasilitas) {
            'active' => 'Tersedia',
            'in_repair' => 'Dalam Perbaikan',
            'inactive' => 'Tidak Aktif',
            default => 'Tidak Diketahui',
        };
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}