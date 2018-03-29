<?php

namespace App\Http\Controllers;

use Image;
use Storage;
use App\Vehicle;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehiclesController extends Controller
{

    /**
     * Prikazi pretragu.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        
    }

    /**
     * Prikazi formu za unos novog vozila.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('vehicles.create', compact('categories'));
    }

    /**
     * Sacuvaj novo vozilo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // Provjeri inpute
        $this->validate($request, [
            "name"      => "required|max:255",
            "category"  => "required|exists:categories,id",
            "price"     => "required|integer",
            "year"      => "required|integer|min:1901|max:2155",
            "miles"     => "required|integer",
            "image"     => "required|file|mimes:jpeg,bmp,png,gif|max:10000"
        ]);


        // Uploadovani fajl
        $file       = $request->file('image');
        // Extenzija
        $ext        = ".".$file->extension();
        // Izgenerisi random string za ime
        $name       = str_random(35);
        // Relativna putanja do slike unutar app/public
        $savePath   = "uploads/".date("Y/m");

        // Kreiraj direktorij ako ne postoji
        if( !Storage::exists("public/".$savePath) ){
            Storage::makeDirectory("public/".$savePath);
        }

        // Pokusaj sacuvati fotku
        try{
            // Napravi image instancu od fotke
            $img = Image::make($file);
            // Smanji fotku na 300px sirine
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // Sacuvaj sliku
            $img->save( storage_path("app/public/".$savePath)."/".$name.$ext );
        }catch(\Exception $e){
            // Loguj gresku
            Log::error('Error saving image:'.$e->getMessage());
            // Vrati back uz error
            session()->flash('flash-message', 'Error saving image.');
            session()->flash('flash-level', 'danger');
            return back()->withInput();

        }

        // Napravi vehicle model
        $vehicle = new Vehicle;

        // Zadaj atribute
        $vehicle->name        = $request->name;
        $vehicle->slug        = str_slug($request->name);
        $vehicle->category_id = $request->category;
        $vehicle->price       = $request->price;
        $vehicle->year        = $request->year;
        $vehicle->miles       = $request->miles;
        $vehicle->image       = "storage/".$savePath."/".$name.$ext;

        // Sacuvaj u bazu
        try{
            $vehicle->save();
        }catch(\Exception $e){
            // Loguj gresku
            Log::error('Error saving vehicle:'.$e->getMessage());
            // Obrisi fotku a zatim vrati back uz error
            Storage::delete( 'public/'.$savePath."/".$name.$ext );
            session()->flash('flash-message', 'Error saving vehicle.');
            session()->flash('flash-level', 'danger');
            return back()->withInput();
        }
        

        // Flesuj uspjesan odgovor i vrati nazad
        session()->flash('flash-message', 'Vehicle successfully saved. It will be visible after administrator reviews it.');
        return back();
    }
}
