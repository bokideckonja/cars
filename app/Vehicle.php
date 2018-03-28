<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    // Vozila koja su odobrena
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
