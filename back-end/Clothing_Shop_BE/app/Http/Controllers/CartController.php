<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function getCartsByUser(string $user_id)
  {
    $carts = Cart::with(['product_carts.product.images', 'product_carts.product.product_details'])->where('user_id', $user_id)->latest()->get();
    $quantityCart= $carts->count();
    return response()->json([$carts, $quantityCart]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCartRequest $request)
  {
    $user_id = $request->user_id;
    $product_id = $request->product_id;
    $cart = new Cart;
    $cart->color = $request->color;
    $cart->size = $request->size;
    $cart->quantity = $request->quantity;
    $cart->user_id = $user_id;
    $cart->save();

    $product_cart= $cart->product_carts()->create([
      'product_id'=> $product_id,
    ]);
    $msg= 'Đã thêm vào giỏ hàng';
    return response()->json([$cart, $product_cart, $msg], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $data = $request->input('carts');
    foreach ($data as $item) {
      $cart = Cart::where('id', $item['cart_id'])->where('user_id', $item['user_id'])->first();
      $cart->update([
        'color' => $item['color'] ?? $cart->color,
        'size' => $item['size'] ?? $cart->size,
        'quantity' => $item['quantity'] ?? $cart->quantity
      ]);
    }


    return response()->json([$cart], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {

    Cart::findOrFail($id)->delete();

    return response()->json([
      'message' => 'Đã xóa sản phẩm này khỏi giỏ hàng'
    ], 200);
  }
  public function clear_all(string $user_id)
  {
    Cart::where('user_id', $user_id)->delete();

    return response()->json([
      'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng'
    ], 200);
  }
}
