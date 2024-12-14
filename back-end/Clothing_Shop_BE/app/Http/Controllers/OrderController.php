<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDetails;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function getUserInfo(string $user_id)
  {
    $user = User::findOrFail($user_id);
    return response()->json($user);
  }
  public function getProductsInfo(string $user_id)
  {
    $carts = Cart::with('product_carts')->where('user_id', $user_id)->get();
    // $carts->filter(function ($product_cart) {
    //     dd($product_cart);die;
    //     $product= Product::select()->where($product_cart->product_id);
    //     return $product;
    // });
    return response()->json($carts);
  }
  public function index()
  {
    $orders = Order::with(['user', 'order_details.product'])->get();

    return response()->json($orders);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreOrderRequest $request)
  {
    $msg = '';
    $user_id = $request->user_id;
    // save order
    $order = new Order;
    $order->id = Str::random(8);
    $order->amount = $request->amount;
    $order->order_date = Carbon::now('Asia/Ho_Chi_Minh');
    $order->receive_date = $order->order_date->addDays(3);
    $order->receive_user = $request->receive_user;
    $order->phone = $request->phone;
    $order->voucher_id = $request->voucher_id;
    $order->user_id = $user_id;
    if ($request->payment_method == 'direct_payment') {
      $order->payment_method = $request->payment_method;
      $order->status = 0;
      $order->save();
      return $this->handleOrder($order, $request, $user_id); //save to db and return data
    }
    if ($request->payment_method == 'momo') {
      $momoController = app()->make(MomoController::class);

      $response = $momoController->store($request);
      if ($response->status() == 200) {
        $data = json_decode($response->getContent(), true);
        $redirect = $data['redirect'];
        $order->payment_method = $request->payment_method;
        $order->status = 1;
        $order->save();
        $this->handleOrder($order, $request, $user_id); //save to db
        return response()->json(['redirect' => $redirect]);
      }
    }
  }
  public function handleOrder($order, $request, $user_id)
  {
    $voucher = Voucher::where('id', $order->voucher_id)->first();
    if ($voucher) {
      $voucher_used = $voucher->used + 1;
      $voucher->update(['used' => $voucher_used]);
    }
    //create user details
    $user = User::where('id', $user_id)->first();
    $user_details = $user->user_details()->create([
      'fullname' => $request->receive_user,
      'email' => $request->email,
      'phone' => $request->phone,
      'address' => $request->address
    ]);

    // order details & products
    $carts = Cart::where('user_id', $user_id)->get();
    $order_details = [];
    $product = [];
    foreach ($carts as $cart) {
      $product_cart = ProductCart::where('cart_id', $cart->id)->first();
      //store order details
      $order_details = $order->order_details()->create([
        'color' => $cart->color,
        'quantity' => $cart->quantity,
        'size' => $cart->size,
        'price' => $order->amount,
        'product_id' => $product_cart->product_id
      ]);
      // update inventory - quantity in products - product_details
      $product = Product::with('product_details')->where('id', $product_cart->product_id)->first();

      $order_quantity = OrderDetail::where('product_id', $product_cart->product_id)->first();
      $product_detail = ProductDetails::where('product_id', $product_cart->product_id)->first();
      $product_quantity = $product_detail->quantity - $order_quantity->quantity;
      $product->product_details()->update([ // quantity
        'quantity' => $product_quantity
      ]);
      $product->inventory = $product_quantity; // inventory
      if ($product->inventory < 1) {
        $product->status = 1;
      }
      $product->save();
      // delete all product by user id
      $cart->delete();
    }
    $msg = 'Khách hàng đã đặt hàng thành công';
    return response()->json([
      $order,
      $user_details,
      $order_details,
      $msg
    ], 201);
  }
  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $order = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
      ->join('products', 'order_details.product_id', '=', 'products.id')
      ->join('images', 'products.id', '=', 'images.product_id')
      ->where('orders.id', $id)
      ->select('orders.*', 'order_details.*', 'products.*', 'images.url')->get();
    return response()->json($order);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }
  public function updateStatus($id)
  {
    $order = Order::findOrFail($id);
    $order->update([
      'status' => 1
    ]);
    $msg = 'Đơn hàng sẽ được giao.';

    return response()->json([$order, $msg]);
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $order = Order::findOrFail($id);
    $order_quantity = $order->order_details->first()->quantity; // get quantity in order_details
    $product_id = $order->order_details->first()->product_id; // get product_id in order_details
    $product = Product::findOrFail($product_id); // get direct product
    $product_quantity = $product->product_details()->first()->quantity; // get quantity in product_details

    $product->product_details()->update([
      'quantity' => $order_quantity + $product_quantity
    ]); // update quantity in product_details
    $product->inventory = $product->product_details()->first()->quantity; // update inventory in products
    $product->save();
    //remove orders and order_details
    $order->order_details()->first()->delete();
    $order->delete();

    $msg = 'Đã hủy đơn hàng';
    return response()->json($msg);
  }
  public function myorder(int $user_id)
  {
    $order = Order::with('user.user_details')->where('user_id', $user_id)
      ->orderBy('created_at', 'desc')->get();

    return response()->json($order);
  }
}
