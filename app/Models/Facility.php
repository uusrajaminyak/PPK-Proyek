<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facilities';

    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'status_fasilitas'
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'status_fasilitas' => 'string',
        ];
    }

    // ==========================================
    // Scopes
    // ==========================================
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

    // ==========================================
    // UI Helpers
    // ==========================================
    public function getStatusBadgeClass(): string
    {
        return match ($this->status_fasilitas) {
            'active' => 'bg-green-100 text-green-800',
            'in_repair' => 'bg-red-100 text-red-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status_fasilitas) {
            'active' => 'Tersedia',
            'in_repair' => 'Dalam Perbaikan',
            'inactive' => 'Tidak Aktif',
            default => 'Tidak Diketahui',
        };
    }

    // ==========================================
    // Business Logic Helpers
    // ==========================================
    public function isActive(): bool
    {
        return $this->status_fasilitas === 'active';
    }

    public function isInRepair(): bool
    {
        return $this->status_fasilitas === 'in_repair';
    }

    public function markInRepair(): bool
    {
        return $this->update(['status_fasilitas' => 'in_repair']);
    }

    public function markActive(): bool
    {
        return $this->update(['status_fasilitas' => 'active']);
    }

    // ==========================================
    // Relationships
    // ==========================================
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function activeReports(): HasMany
    {
        return $this->hasMany(Report::class)->whereIn('status_laporan', ['baru', 'diproses']);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
