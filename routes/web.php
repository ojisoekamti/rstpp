<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Http\Request;

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

Route::get('/', function (Request $request) {
    $menu_item = DB::table('product_items')->get();
    $categories = DB::table('categories')->get();
    return view('welcome', ['menu_items' => $menu_item, 'menu_item' => $menu_item, 'categories' => $categories]);
})->middleware('check.order.session');

Route::get('/order-details', [OrderController::class, 'orderDetails'])->middleware('check.order.session');
Route::get('/print-order/{orderId}', [OrderController::class, 'printOrder']);
Route::post('/confirmationTable', [OrderController::class, 'confirmationTable']);
Route::get('/print-receipt', [PrintController::class, 'printReceipt']);
Route::get('/receipt', [ReceiptController::class, 'generateReceipt']);
Route::get('/confirmation', [OrderController::class, 'confirmationOrder']);
Route::post('/order/confirm', [OrderController::class, 'confirmOrder']);
Route::get('/session-timeout', function () {
    return view('session-timeout');
});

Route::get('/remove-session', function () {
    session()->flush();
    return response()->json(['success' => true, 'message' => 'Session cleared successfully']);
});

Route::get('/print-html', function () {
    $items = DB::table('order_items')->where("order_id", 17)->join('product_items', 'product_items.id', '=', 'order_items.product_item_id')->get();
    return view('print-order', ['items' => $items]);
});

Route::get('/orders/{id}', function () {
    $tables = DB::table('tables')->get();
    return view('confirmation-form', [
        'tables' => $tables,
        'order_id' => '#11',
        'order_date' => '16/11/2024 19:00:11',
        'table_number' => 'Rooms #101'
    ]);
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
