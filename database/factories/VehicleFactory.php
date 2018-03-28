<?php

use Faker\Generator as Faker;

$factory->define(App\Vehicle::class, function (Faker $faker) {
	$name = $faker->name;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'price'=> $faker->randomNumber(5),
        'year' => $faker->dateTimeThisCentury($max = 'now', $timezone = 'Europe/Belgrade')->format('Y'),
        'miles'=> $faker->randomNumber(6),
        'user_id'=>1,
        'category_id'=>1,
        'status'=>'approved'
    ];
});
