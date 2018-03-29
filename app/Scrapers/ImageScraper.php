<?php

namespace App\Scrapers;

use Image;
use Storage;
use Illuminate\Support\Facades\Log;

class ImageScraper
{
    /**
     * Snimi fotku
     *
     * @param  URL  $url
     * @return mixed
     */
    public function store($url)
    {
        $pathinfo       = pathinfo($url);
        $ext            = $pathinfo['extension']??"jpg";
        $ext            = ".".$ext;
        $name           = str_random(35);
        $savePath       = "uploads/".date("Y/m");

        // Kreiraj direktorij ako ne postoji
        if( !Storage::exists("public/".$savePath) ){
            Storage::makeDirectory("public/".$savePath);
        }

        // Pokusaj sacuvati fotku
        try{
            // Napravi image instancu od fotke
            $img = Image::make($url);
            // Smanji fotku na 300px sirine
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // Sacuvaj sliku
            $img->save( storage_path("app/public/".$savePath)."/".$name.$ext );

            return "storage/".$savePath."/".$name.$ext;
        }catch(\Exception $e){
            // Loguj gresku
            Log::error('Error saving image:'.$e->getMessage());
            return false;
        }
    }
}