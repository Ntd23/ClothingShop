<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Voucher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function showSlide() {
    $images= Slide::latest()->pluck('image')->all();
    return response()->json($images);
  }
  public function mens() {
    $mens= Product::with(['images'])
    ->where('name', 'LIKE', '%nam%')
    ->where('inventory', '>', 0)
    ->limit(5)
    ->inRandomOrder()
    ->latest()->get();

    return response()->json($mens);
  }
  public function women() {
    $women= Product::with(['images'])
    ->where('name', 'LIKE', '%nu%')
    ->where('inventory', '>', 0)
    ->limit(5)
    ->inRandomOrder()
    ->latest()->get();

    return response()->json($women);
  }
  public function bestSelling() {
    $best_selling= Product::with('images')->orderBy('inventory')->limit(5)->get();

    return response()->json($best_selling);
  }
  public function categories() {
    $categories= Category::with('children')->where('parent_id', null)->get();

    return response()->json($categories);
  }
  public function clothing($name) {
    $name= str_replace('Quần áo', '', $name);
    $clothings= Product::with('images')->where('name', 'LIKE', '%'.$name.'%')->limit(20)->latest()->get();

    return response()->json($clothings);
  }
  public function categoryById($cate_id) {
    $category= Category::where('id', $cate_id)->pluck('name');

    return response()->json($category);
  }
  public function products($cate_id) {
    $products= Product::with('images')->where('cate_id', $cate_id)->latest()->get();

    return response()->json($products);
  }
  public function search($keywords) {
    $results= Product::with('images')->where('name', 'LIKE', '%'.$keywords.'%')->get();

    return response()->json($results);
  }
  public function filterByPrice($min_price, $max_price) {
    $results= Product::with('images')
      ->where('price', '>=', $min_price)
      ->where('price', '<=', $max_price)
      ->get();

    return response()->json($results);
  }
  public function getVouchers() {
    $vouchers= Voucher::latest()->get();

    return response()->json($vouchers);
  }
  public function getProductDetails($id) {
    $product_details= Product::with(['images', 'product_details'])->where('id', $id)->get();

    return response()->json($product_details);
  }
}
