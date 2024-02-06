<?php

use Illuminate\Support\Facades\Route;
use Kizi\Shopify\Http\Controllers\ShopifyController;

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
// Route::get('/', function () {
//     return view('welcome');
// })
// ->name('application');

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/call-back', function () {
//     return view('welcome');
// });


Route::get('/', [ShopifyController::class, 'redirect'])->name('shopify.redirect');
