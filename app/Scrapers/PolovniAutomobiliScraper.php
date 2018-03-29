<?php

namespace App\Scrapers;

use Goutte;
use Storage;
use App\Category;
use App\Vehicle;
use App\Scrapers\ImageScraper;
use Illuminate\Support\Facades\Log;

class PolovniAutomobiliScraper
{
    private $urlVehicles;
    private $imageScraper;
    private $urlDomain;

    protected $localCategoryId = 0;

    function __construct()
    {
        // Postavi url
        $this->urlVehicles = "https://www.polovniautomobili.com/putnicka-vozila/pretraga?brand=38&model=&price_from=40000&price_to=&year_from=&year_to=&door_num=&submit_1=&without_price=1&date_limit=&showOldNew=all&modeltxt=&engine_volume_from=&engine_volume_to=&power_from=&power_to=&mileage_from=&mileage_to=&emission_class=&seat_num=&wheel_side=&registration=&country=&city=&page=&sort=";
        // Domen
        $this->urlDomain = "https://www.polovniautomobili.com";
        // Zadaj image scraper
        $this->imageScraper = new ImageScraper;

        // Dobavi kategoriju aurija
        $this->localCategoryId = (Category::where('slug','audi')->firstOrFail())->id;
    }

    public function scrape(){
        // Preskoci scrape ako nije pronadjena kategorija audi
        if($this->localCategoryId == 0){
            return;
        }
    	// ID vozila, kako bi se izbjegli duplikati
    	$ids = Vehicle::where('category_id', $this->localCategoryId)->pluck('source_id')->toArray();

        // Pojedinacni linkovi
        $links = [];
        // Paginacijski linkovi
        $pages = [];

        // Start crawling  
        $crawler = Goutte::request('GET', $this->urlVehicles);

        // Dobavi paginacijske linkove
        $crawler->filter('#search-results .js-hide-on-filter .uk-pagination a.js-pagination-numeric')->eq(0)->each(function ($node) use (&$pages){
            $pages[] = $this->urlDomain . $node->extract(['href'])[0];
        });

        // Dobavi pojedinacne linkove za vozila na pocetnoj
	    $crawler->filter('#search-results .js-hide-on-filter .single-classified h3 a')->each(function ($node) use (&$links, &$ids){
            $link = $node->extract(['href'])[0];
            $id = explode("/", $link)[2];

            // Ako ne postoji taj link, dodaj ga
            if(!in_array($id, $ids)){
                $ids[] = $id;
                $links[$id] = $this->urlDomain . $link;
            }
	    });

        // Za svaki paginacijski link, otvoti stranu i povuci pojedinacne linkove
        foreach($pages as $page){
            $paginatedPage = Goutte::request('GET', $page);
            $paginatedPage->filter('#search-results .js-hide-on-filter .single-classified h3 a')->each(function ($node) use (&$links, &$ids){
                $link = $node->extract(['href'])[0];
                $id = explode("/", $link)[2];
                // Ako ne postoji taj link, dodaj ga
                if(!in_array($id, $ids)){
                    $ids[] = $id;
                    $links[$id] = $this->urlDomain . $link;
                }
            });
        }
        
        // Posjeti sve linkove i izvuci informacije
        foreach($links as $id => $link){
            $this->scrapeSingle($id,$link);
        }
    }

    public function scrapeSingle($id, $url){
        $crawler = Goutte::request('GET', $url);

        $vehicle = [];

        try{
            // Source_id
            $vehicle['source_id'] = $id;
            // Ime i godiste
            $crawler->filter('.body h1')->each(function ($node) use (&$vehicle){
                $vehicle['year'] = trim( str_before( $node->filter('small')->eq(0)->text(),'. godište' ) );
                $vehicle['name'] = trim( str_before( $node->text(), $vehicle['year'] ) );
            });
            // Cijena
            $vehicle['price'] = str_replace(".", "", trim( str_before( $crawler->filter('.price-item')->eq(0)->text(), '€' ) ) );

            // Kilometraza
            $crawler->filter('.classified-content .uk-grid div')->each(function($node) use (&$vehicle){
                if(trim($node->text()) == "Kilometraža"){
                    $vehicle['miles'] = str_replace(".", "", trim( rtrim( $node->nextAll()->eq(0)->text(), 'km' ) ) );
                }
            });
            // Slika
            $image = $crawler->filter('#image-gallery img')->eq(0)->extract(['src'])[0];
            $vehicle['image'] = $this->imageScraper->store($image);

            // Sacuvaj vozilo
        
            $this->saveVehicle($vehicle);
        }catch(\Exception $e){
            // Loguj gresku
            Log::error('Error scraping article:'.$e->getMessage());
            // Obrisi fotku
            if(isset($vehicle['image']) && $vehicle['image'] !== false){
                Storage::delete( 'public/'.ltrim($vehicle['image'], 'storage/') );
            }
        }
    }

    private function saveVehicle($vehicle){
        $newVehicle = new Vehicle;
        $newVehicle->name = $vehicle['name'];
        $newVehicle->slug = str_slug($vehicle['name']);
        $newVehicle->year = $vehicle['year'];
        $newVehicle->miles = $vehicle['miles'];
        $newVehicle->price = $vehicle['price'];
        $newVehicle->image = $vehicle['image'];
        $newVehicle->category_id = $this->localCategoryId;
        $newVehicle->status = 'approved';
        $newVehicle->source_id = $vehicle['source_id'];

        $newVehicle->save();
    }
}