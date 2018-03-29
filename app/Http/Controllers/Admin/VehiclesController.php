<?php

namespace App\Http\Controllers\Admin;

use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehiclesController extends Controller
{
    /**
     * Prikazi vozila.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$vehicles = Vehicle::with('category')->paginate(2);
    	return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Odobri vozilo.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve(Vehicle $vehicle)
    {
        $vehicle->status = 'approved';
        $vehicle->save();

        session()->flash('flash-message', 'Vehicle successfully approved.');
        return back();
    }

    /**
     * Obrisi vozilo.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
    	$vehicle->delete();

    	session()->flash('flash-message', 'Vehicle successfully deleted.');
    	return back();
    }
}
