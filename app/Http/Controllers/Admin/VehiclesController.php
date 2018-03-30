<?php

namespace App\Http\Controllers\Admin;

use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scrapers\PolovniAutomobiliScraper;

class VehiclesController extends Controller
{
    /**
     * Prikazi vozila.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$vehicles = Vehicle::with('category')->latest()->paginate(10);
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
     * Scrape-uj polovniautomobili.com za audi modele
     *
     * @return \Illuminate\Http\Response
     */
    public function scrapeAudi()
    {
        // Idealno bi bilo pomjeriti scraper u Job, i queuovat
        // Tako da se ne ceka izvrsenje
        (new PolovniAutomobiliScraper)->scrape();

        session()->flash('flash-message', 'Vehicles successfully fetched.');
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
