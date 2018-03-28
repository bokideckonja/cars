<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index(){
    	$vehicles = Vehicle::approved()->paginate(1);
    	return view('index', compact('vehicles'));
    }
}
