<?php

namespace App\Scrapers;

use Goutte;
use App\Vehicle;
use App\Scrapers\ImageScraper;
use Illuminate\Support\Facades\Log;

class PolovniAutomobiliScraper
{
    private $urlVehicles;
    private $imageScraper;

    protected $localCategoryId = 0;

    function __construct()
    {
        // Postavi url
        $this->urlVehicles = "https://www.polovniautomobili.com/putnicka-vozila/pretraga?brand=38&model=&price_from=40000&price_to=&year_from=&year_to=&door_num=&submit_1=&without_price=1&date_limit=&showOldNew=all&modeltxt=&engine_volume_from=&engine_volume_to=&power_from=&power_to=&mileage_from=&mileage_to=&emission_class=&seat_num=&wheel_side=&registration=&country=&city=&page=&sort=";
        // Zadaj image scraper
        $this->imageScraper = new ImageScraper;
        // Dobavi kategoriju aurija
        // ...
    }

    public function scrape(){
    	// Dobavi poslednjih 10 informacija za uporedjivanje
    	// $vehicles = Vehicle::limit(10)->get();

    	// Start crawling  
	    $crawler = Goutte::request('GET', $this->urlVehicles);
	    $i = 0;
	    $crawler->filter('#search-results')->each(function ($node) use (&$i){
	        $text = trim($node->text());
	    });
    }
}