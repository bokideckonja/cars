<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Kreiraj jednog member korisnika
    	factory(App\User::class, 1)->create()->each(function($user){
    	    $user->name = "Jane Doe";
    	    $user->email = "member@gmail.com";
    	    $user->password = bcrypt('asdasd');
    	    $user->save();
    	});
    	// Kreiraj jednog admina
        factory(App\Admin::class, 1)->create()->each(function($admin){
            $admin->name = "John Doe";
            $admin->email = "admin@gmail.com";
            $admin->password = bcrypt('asdasd');
            $admin->save();
        });



        // Kreiraj audi kategoriju
        factory(App\Category::class, 1)->create()->each(function($category){
            $category->name = "Audi";
            $category->slug = "audi";
            $category->save();
        });


        // Kreiraj BMW kategoriju
        $bmw = factory(App\Category::class, 1)->create()->each(function($category){
            $category->name = "BMW";
            $category->slug = "bmw";
            $category->save();
        });
        // Kopiraj fotku
        $this->copyImage("bmw.jpeg");
        // Kreiraj vozilo u BMW kategoriji
        factory(App\Vehicle::class, 1)->create()->each(function($vehicle) use ($bmw){
            $vehicle->name = "BMW X5";
            $vehicle->slug = "bmw-x5";
            $vehicle->category_id = $bmw->first()->id;
            $vehicle->image = "storage/uploads/".date("Y/m")."/bmw.jpeg";
            $vehicle->save();
        });


        // Kreiraj VW kategoriju
        $vw = factory(App\Category::class, 1)->create()->each(function($category){
            $category->name = "Volkswagen";
            $category->slug = "volkswagen";
            $category->save();
        });
        // Kopiraj fotku
        $this->copyImage("vw.jpeg");
        // Kreiraj vozilo u VW kategoriji
        factory(App\Vehicle::class, 1)->create()->each(function($vehicle) use ($vw){
            $vehicle->name = "Volkswagen Touareg";
            $vehicle->slug = "volkswagen-touareg";
            $vehicle->category_id = $vw->first()->id;
            $vehicle->image = "storage/uploads/".date("Y/m")."/vw.jpeg";
            $vehicle->save();
        });

        // Kreiraj Renault kategoriju
        $renault = factory(App\Category::class, 1)->create()->each(function($category){
            $category->name = "Renault";
            $category->slug = "renault";
            $category->save();
        });
        // Kopiraj fotku
        $this->copyImage("renault.jpeg");
        // Kreiraj vozilo u Renault kategoriji
        factory(App\Vehicle::class, 1)->create()->each(function($vehicle) use ($renault){
            $vehicle->name = "Renault 5";
            $vehicle->slug = "renault-5";
            $vehicle->year = 1987;
            $vehicle->category_id = $renault->first()->id;
            $vehicle->image = "storage/uploads/".date("Y/m")."/renault.jpeg";
            $vehicle->save();
        });
    }

    public function copyImage($image){
        // Lokacija fajlova
        $savePath   = "uploads/".date("Y/m");

        // Kreiraj direktorij ako ne postoji
        if( !Storage::exists("public/".$savePath) ){
            Storage::makeDirectory("public/".$savePath);
        }

        if( !Storage::exists("public/".$savePath."/".$image) ){
            Storage::copy($image, "public/".$savePath."/".$image);
        }
    }
    
}
