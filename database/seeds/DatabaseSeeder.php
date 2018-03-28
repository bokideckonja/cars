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
        // Kreiraj vozilo u BMW kategoriji
        factory(App\Vehicle::class, 1)->create()->each(function($vehicle) use ($bmw){
            $vehicle->name = "BMW X5";
            $vehicle->slug = "bmw-x5";
            $vehicle->category_id = $bmw->first()->id;
            $vehicle->save();
        });


        // Kreiraj VW kategoriju
        $vw = factory(App\Category::class, 1)->create()->each(function($category){
            $category->name = "Volkswagen";
            $category->slug = "volkswagen";
            $category->save();
        });
        // Kreiraj vozilo u VW kategoriji
        factory(App\Vehicle::class, 1)->create()->each(function($vehicle) use ($vw){
            $vehicle->name = "Volkswagen Touareg";
            $vehicle->slug = "volkswagen-touareg";
            $vehicle->category_id = $vw->first()->id;
            $vehicle->save();
        });
    }
}
