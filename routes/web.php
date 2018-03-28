<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');
Route::get('/vehicles/create', 'VehicleController@create')->middleware('auth');
Route::post('/vehicles', 'VehicleController@store')->middleware('auth');

// Admin Authentication Routes...
Route::get('admin/login', 'Admin\Auth\LoginController@showLoginForm');
Route::post('admin/login', 'Admin\Auth\LoginController@login');
Route::post('admin/logout', 'Admin\Auth\LoginController@logout');

// Admin Registration Routes...
Route::get('admin/register', 'Admin\Auth\RegisterController@showRegistrationForm');
Route::post('admin/register', 'Admin\Auth\RegisterController@register');

// Admin Password Reset Routes...
Route::get('admin/password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('admin/password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('admin/password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm');
Route::post('admin/password/reset', 'Admin\Auth\ResetPasswordController@reset');

Auth::routes();

Route::get('/home', 'HomeController@index');
