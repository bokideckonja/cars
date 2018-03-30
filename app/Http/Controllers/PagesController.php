<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\Category;
use App\Scrapers\PolovniAutomobiliScraper;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Request $request){
        $categories = Category::all();

        $vehicles = Vehicle::approved()->with('category')
            ->where(function ($query) use ($request){
                if ( $request->has('category_id') ) {
                	$query->where('category_id', $request->category_id);
                }
            })->paginate(10);

        if($request->expectsJson()){
            return response()->json($vehicles);
        }

        return view('index', compact('vehicles','categories'));
    }
}
