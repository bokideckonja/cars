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

Route::get('/', 'DashboardController@index');
Route::post('logout', 'Auth\LoginController@logout');