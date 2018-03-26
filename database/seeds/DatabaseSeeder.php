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
    	factory(App\User::class, 1)->create()->each(function($a){
    	    $a->name = "Jane Doe";
    	    $a->email = "member@gmail.com";
    	    $a->password = bcrypt('asdasd');
    	    $a->save();
    	});

    	// Kreiraj jednog admina
        factory(App\Admin::class, 1)->create()->each(function($a){
            $a->name = "John Doe";
            $a->email = "admin@gmail.com";
            $a->password = bcrypt('asdasd');
            $a->save();
        });
    }
}
