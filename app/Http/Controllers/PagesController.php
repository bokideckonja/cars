<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
    	$vehicles = Vehicle::approved()->with('category')->paginate(2);
    	return view('index', compact('vehicles'));
    }
}
