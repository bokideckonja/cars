<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| This file is where I will define all my admin related routes. The 
| reason i separated it to a different file, is to not polute the
| main web routes file, but instead keep it clean for user routes.
|
*/

Route::post('logout', 'Auth\LoginController@logout');

Route::get('/', 'VehiclesController@index');
Route::put('/vehicles/{vehicle}/approve', 'VehiclesController@approve');
Route::get('/vehicles/scrape', 'VehiclesController@scrapeAudi');
Route::delete('/vehicles/{vehicle}', 'VehiclesController@destroy');
Route::resource('categories', 'CategoriesController')->except('show');