<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\Scrapers\PolovniAutomobiliScraper;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Request $request){
        $vehicles = Vehicle::approved()->with('category')->paginate(10);
        if($request->expectsJson()){
            return response()->json($vehicles);
        }
        // return response()->json($vehicles);
        return view('index', compact('vehicles'));
    }
}
