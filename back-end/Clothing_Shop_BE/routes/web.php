<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('slide/show', [HomeController::class, 'showSlide']);
Route::get('mens', [HomeController::class, 'mens']);
Route::get('women', [HomeController::class, 'women']);
Route::get('best_selling', [HomeController::class, 'bestSelling']);
Route::get('categories', [HomeController::class, 'categories']);
Route::get('clothing/{name}', [HomeController::class, 'clothing']);
Route::get('category/{cate_id}', [HomeController::class, 'categoryById']);
Route::get('products/{cate_id}', [HomeController::class, 'products']);
Route::get('/search/{keywords}', [HomeController::class, 'search']);
Route::get('/filter/{min_price}/{max_price}', [HomeController::class, 'filterByPrice']);
Route::get('/vouchers', [HomeController::class, 'getVouchers']);
Route::get('/product/details/{id}', [HomeController::class, 'getProductDetails']);
