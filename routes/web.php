<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {    
    
    $menu_item = DB::table('product_items')->get();

    return view('welcome', ['menu_item' => $menu_item]);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
