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

Route::get('/', function () {
    return view('vendor/welcome');
});
Route::get('/', 'HomePageController@index')->name('homePage');
Route::post('/', 'HomePageController@searchByNameProduct')->name('searchByNameProduct');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
