<?php

namespace App\Observers;

use App\Vehicle;

class VehicleObserver
{
    /**
     * Listen to the Vehicle deleting event.
     *
     * @param  \App\Vehicle  $vehicle
     * @return void
     */
    public function deleting(Vehicle $vehicle)
    {
        \Storage::delete( 'public/'.ltrim($vehicle->image, 'storage/') );
    }
}