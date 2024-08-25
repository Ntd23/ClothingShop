<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// header('Access-Control-Allow-Origin: http://127.0.0.1:8080');
// header('Access-Control-Allow-Headers: origin, x-requested-with, content-type');
// header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Auth
Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

//Categories Management
Route::apiResource('categories',CategoryController::class)->middleware('auth:sanctum');
Route::get('parent-category', [CategoryController::class, 'getCategoryParent'])->middleware('auth:sanctum');
Route::get('child-category', [CategoryController::class, 'getCategoryChildren'])->middleware('auth:sanctum');
Route::get('search/category/{keywords}', [CategoryController::class, 'search'])->middleware('auth:sanctum');
Route::get('category/parent_id-is-null', [CategoryController::class, 'getCateByParentIdIsNull']);
Route::get('category/child_category/{parent_id}', [CategoryController::class, 'getCateChildByCateParent']);
//Products Management
Route::apiResource('products',ProductController::class)->middleware('auth:sanctum');
Route::get('product/filter/{parent_id}', [ProductController::class, 'filterByCategory']);
//Contact Management
Route::apiResource('contact',ContactController::class);
//Slider Management
Route::apiResource('slide',SlideController::class)->middleware('auth:sanctum');
//Voucher Management
Route::apiResource('voucher',VoucherController::class)->middleware('auth:sanctum');


