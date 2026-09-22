<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes; 

    protected $guarded = [];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}