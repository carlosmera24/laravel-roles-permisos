<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {

    Route::post('products/store', 'ProductController@store')->name('products.store')
                                                        ->middleware('permission:products.create');
    Route::get('products', 'ProductController@index')->name('products.index')
                                                        ->middleware('permission:products.index');
    Route::get('products/create', 'ProductController@create')->name('products.create')
                                                        ->middleware('permission:products.create');
    Route::put('products/{role}', 'ProductController@update')->name('products.update')
                                                        ->middleware('permission:products.edit');
    Route::get('products/{role}', 'ProductController@show')->name('products.show')
                                                        ->middleware('permission:products.show');
    Route::delete('products/{role}', 'ProductController@destroy')->name('products.destroy')
                                                        ->middleware('permission:products.destroy');
    Route::get('products/{role}/edit', 'ProductController@edit')->name('products.edit')
                                                        ->middleware('permission:products.edit');
});
