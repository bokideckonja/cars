<?php

namespace App;

use App\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Kategorija moze imati vise vozila.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
