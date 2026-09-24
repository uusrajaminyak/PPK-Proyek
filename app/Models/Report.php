<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'facility_id',
        'kategori_laporan',
        'deskripsi',
        'foto_paths',
        'status_laporan',
        'catatan_resolusi',
    ];

    protected function casts(): array
    {
        return [
            'foto_paths' => 'array',
        ];
    }

    // ==========================================
    // Relationships
    // ==========================================
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
