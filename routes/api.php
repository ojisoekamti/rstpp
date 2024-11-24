<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/order-list', [OrderController::class, 'placeOrder']);
Route::get('/get-orders/{id}', [OrderController::class, 'getOrders']);
Route::get('/get-latest-order', [OrderController::class, 'getLatestOrder']);
Route::post('/update-order-status/{id}', [OrderController::class, 'processOrder']);
Route::patch('/product-items/{id}/update-stock', [ProductItemController::class, 'updateStock']);
