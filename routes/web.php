<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatainvestasiController;

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

// returns the home page with all posts
Route::get('/', DatainvestasiController::class .'@index')->name('data_investasi.index');
// returns the form for adding a post
Route::get('/data_investasi/create', DatainvestasiController::class . '@create')->name('data_investasi.create');
// adds a post to the database
Route::post('/data_investasi', DatainvestasiController::class .'@store')->name('data_investasi.store');
// returns a page that shows a full post
Route::get('/data_investasi/{data_investasi}', DatainvestasiController::class .'@show')->name('data_investasi.show');
// returns the form for editing a post
Route::get('/data_investasi/{data_investasi}/edit', DatainvestasiController::class .'@edit')->name('data_investasi.edit');
// updates a post
Route::put('/data_investasi/{data_investasi}', DatainvestasiController::class .'@update')->name('data_investasi.update');
// deletes a post
Route::delete('/data_investasi/{data_investasi}', DatainvestasiController::class .'@destroy')->name('data_investasi.destroy');
