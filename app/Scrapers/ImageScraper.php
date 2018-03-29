<?php

namespace App\Scrapers;

use Image;
use Storage;
use Illuminate\Support\Facades\Log;

class ImageScraper
{
	    // Allowed IMAGE mimetypes
		// GD podrzava JPEG PNG GIF
	    // Imagick podrzava JPEG PNG GIF TIF BMP ICO PSD
	    private $imageMimes;

	    // Other allowed mimetypes
	    private $nonimageMimes;

	    // What image sizes should it create
	    private $imageSizes;

	    // Watermark png
	    private $watermark;

	    // All allowed mimetypes
	    private $allowedMimes;

	    public function __construct(){
	        // Get values from config file
	        $this->imageSizes       = config('cms.image_sizes');
	        $this->imageMimes       = config('cms.image_mimes');
	        $this->nonimageMimes    = config('cms.nonimage_mimes');
	        // Set all allowed mimetypes
	        $this->allowedMimes = array_merge($this->imageMimes, $this->nonimageMimes);

	        // Set watermark png
	        $this->watermark = storage_path("app/watermark.png");
	    }


	    /**
	     * Store a newly created resource in storage.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Http\Response
	     */
	    public function store($url, $author = "", $description = "")
	    {
	    	try{
		    	$img = Image::make($url);
		    }catch(\Exception $e){
		    	Log::info("Slika neuspjesno obradjena: ".$e->getMessage()." Url:".$url);
		    	return false;
		    }

	    	$pathinfo 		= pathinfo($url);
	    	$ext 			= $pathinfo['extension']??"jpg";
	    	$ext 			= ".".$ext;
	    	$mimeType 		= $img->mime();
	    	$originalName 	= strip_tags(str_limit( $pathinfo['filename'] ,30,"..."));
	    	$name 			= str_random(35);
	    	$savePath   	= "uploads/".date("Y/m");
	    	$dimensions 	= null;

	    	// CKECK IF IT IS A ALLOWED TYPE OF IMAGE AND PROCESS IT
	    	if( in_array($mimeType, $this->imageMimes) ) {
	    	    // If directory doesn't exist, create it (Image throws error if it's not created before saving)
	    	    if( !Storage::exists("public/".$savePath) ){
	    	        Storage::makeDirectory("public/".$savePath);
	    	    }

	    	    // Save image
	    	    $img->save( storage_path("app/public/".$savePath)."/".$name.$ext );
	    	    // Get width
	    	    $width = $img->width();
	    	    // Get Height
	    	    $height = $img->height();
	    	    // Backup image
	    	    $img->backup();

	    	    // Create all image sizes
	    	    foreach ($this->imageSizes as $k => $v) {
	    	        // Smjesti fotku u zadatu velicinu
	    	        $img->fit($v['w'], $v['h'], function ($constraint) {
	    	            // Sprjeci uvecavanje slike
	    	            $constraint->upsize();
	    	        });
	    	        // Ukoliko je fotografija preko 393214 piksela(768*512), primjeni watermark
	    	        // if($v['w']*$v['h'] > 393214){
	    	            // Insert watermark(ZA SADA ISKOMENTARISANO ZATO STO NE ZELIM DA PRIMJENJUJEM WATERMARK DOK NE VIDIM SA MIRKOM)
	    	            // $img->insert($this->watermark, "bottom-right",10,10);
	    	        // }
	    	        // Save image with suffix (ie hash_name-1024x768.jpeg)
	    	        $img->save( storage_path("app/public/".$savePath)."/".$name."-".$v['w']."x".$v['h'].$ext );
	    	        // Reset to original for next iteration
	    	        $img->reset();
	    	    }

	    	    $dimensions = $width."x".$height;

	    	// IF IT IS SOME OTHER ALLOWED FILE TYPE
	    	}

	        // Make media model
	        $media = new Media;

	        // Set media DB parameters
	        $media->admin_id    = 1;
	        $media->name        = $name.$ext;
	        $media->path        = "storage/".$savePath."/".$name.$ext;
	        $media->mime_type   = $mimeType;
	        $media->size        = Storage::size("public/".$savePath."/".$name.$ext);
	        $media->title       = $originalName;
	        $media->dimensions  = $dimensions;
	        $media->author 		= $author;
	        $media->description = $description;

	        // Save to DB
	        $media->save();

	        // Return response
	        return $media;
	    }
}