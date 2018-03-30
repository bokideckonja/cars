<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\Category;
use App\Scrapers\PolovniAutomobiliScraper;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Request $request){
    	// Kategorije za select-a
        $categories = Category::all();

        // Vozila za prikaz
        $vehicles = Vehicle::approved()->latest()->with('category')
            ->where(function ($query) use ($request){
                if ( $request->has('category_id') ) {
                	$query->where('category_id', $request->category_id);
                }
            })->paginate(10);

        // Ako je ajax upit, posalji json
        if($request->expectsJson()){
            return response()->json($vehicles);
        }

        // Ako ne, renderuj normalno stranicu
        return view('index', compact('vehicles','categories'));
    }
}
