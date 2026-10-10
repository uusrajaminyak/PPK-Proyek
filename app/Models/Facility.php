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

    /**
     * Physical Mass Assignable Columns matching PostgreSQL schema.
     */
    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'foto',
        'is_active',
        'foto_path',
        'status_fasilitas',
    ];

    /**
     * Unified Attribute Casting.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'kapasitas' => 'integer',
            'status_fasilitas' => 'string',
            'kapasitas' => 'integer',
        ];
    }

    // ==========================================
    // Virtual Accessors (Schema & UI Compatibility Layer)
    // ==========================================

    public function getKategoriAttribute()
    {
        return $this->attributes['tipe'] ?? 'Ruang Kelas';
    }

    public function setKategoriAttribute($value)
    {
        $this->attributes['tipe'] = $value;
    }

    public function getFotoAttribute()
    {
        return $this->attributes['foto_path'] ?? null;
    }

    public function setFotoAttribute($value)
    {
        $this->attributes['foto_path'] = $value;
    }

    public function getIsActiveAttribute(): bool
    {
        if (! empty($params['nama'])) {
            $query->where('nama_fasilitas', 'like', "%{$params['nama']}%");
        }
        if (! empty($params['tipe'])) {
            $query->where('tipe', $params['tipe']);
        }
        if (! empty($params['kategori'])) {
            $query->where('kategori', $params['kategori']);
        }
        if (! empty($params['lokasi'])) {
            $query->where('lokasi', 'like', "%{$params['lokasi']}%");
        }
        if (! empty($params['kapasitas_min'])) {
            $query->where('kapasitas', '>=', $params['kapasitas_min']);
        }

        return $query;
        return ($this->attributes['status_fasilitas'] ?? 'active') === 'active';
    }

    public function getStatusFasilitasAttribute($value)
    {
        return $value ?: 'active';
    }

    // ==========================================
    // UI & Business Logic Helpers
    // ==========================================

    public function getStatusBadgeClass(): string
    {
        return match ($this->status_fasilitas) {
            'active' => 'bg-green-100 text-green-800',
            'in_repair' => 'bg-red-100 text-red-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
            'active'    => 'bg-green-100 text-green-800',
            'in_repair' => 'bg-amber-100 text-amber-800',
            'inactive'  => 'bg-gray-100 text-gray-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status_fasilitas) {
            'active' => 'Tersedia',
            'in_repair' => 'Dalam Perbaikan',
            'inactive' => 'Tidak Tersedia',
            default => 'Tidak Diketahui',
        };
    }

    public function isActive(): bool
    {
        return ($this->attributes['status_fasilitas'] ?? 'active') === 'active';
    }

    public function isInRepair(): bool
    {
        return ($this->attributes['status_fasilitas'] ?? '') === 'in_repair';
    }

    public function markInRepair(): void
    {
        $this->update(['status_fasilitas' => 'in_repair']);
    }

    public function markActive(): void
    {
        $this->update(['status_fasilitas' => 'active']);
    }

    public function markUnavailable(): void
    {
        $this->update(['status_fasilitas' => 'inactive']);
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
