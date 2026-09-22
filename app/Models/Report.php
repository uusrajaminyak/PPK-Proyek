<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    protected $casts = [
        'foto_paths' => 'array',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}