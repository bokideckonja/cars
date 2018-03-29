<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
	/**
	 * Vozilo pripada kategoriji.
	 */
	public function category(){
	    return $this->belongsTo(Category::class);
	}

    /**
     * Odobrena vozila.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
