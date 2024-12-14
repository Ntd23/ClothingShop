<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MomoController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\AuthUser;
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
Route::get('category/product/{product_id}', [HomeController::class, 'getCategoryByProductId']);
Route::get('product/related/{name}/{id}', [HomeController::class, 'getProductRelated']);
Route::middleware('auth_user')->group(function () {
  Route::get('/carts/{user_id}', [CartController::class, 'getCartsByUser']);
  Route::post('/cart', [CartController::class, 'store']);
  Route::put('/cart', [CartController::class, 'update']);
  Route::delete('/cart/{id}', [CartController::class, 'destroy']);
  Route::delete('/cart/clear_all/{user_id}', [CartController::class, 'clear_all']);
  Route::get('check-voucher/{code}/{price}', [VoucherController::class, 'check']);
  Route::get('user-info/{user_id}', [OrderController::class, 'getUserInfo']);
  Route::get('products-info/{user_id}', [OrderController::class, 'getProductsInfo']);
  Route::post('/order', [OrderController::class, 'store']);
  //payment by momo
  Route::prefix('momo')->controller(MomoController::class)->group(function () {
    Route::post('/', 'store')->name('momo.store');
    Route::get('/', 'returnUrl')->name('momo.return');
    Route::get('success', 'success')->name('momo.success');
    Route::get('cancel', 'cancel')->name('momo.cancel');
  });
  //end
  Route::put('/re-password', [AuthController::class, 'rePassword']);
  Route::get('profile/address/{user_id}', [UserDetailController::class, 'index']);
  Route::post('profile/address', [UserDetailController::class, 'store']);
  Route::put('profile/address/{user_detail}', [UserDetailController::class, 'update']);
  Route::delete('profile/address/{user_detail}', [UserDetailController::class, 'destroy']);
  Route::get('profile/{user_id}/myorder', [OrderController::class, 'myorder']);
  Route::delete('order/{id}', [OrderController::class, 'destroy']);
  Route::get('order/{id}', [OrderController::class, 'show']);

  //CHAT
  Route::resource('messages/{user}', ChatController::class, ['only' => 'index, store']);
});
