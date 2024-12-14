<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VoucherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $vouchers = Voucher::latest()->get();

    return response()->json($vouchers);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreVoucherRequest $request)
  {
    $voucher = new Voucher;
    $voucher->code = $request->code;
    $voucher->min_price = $request->min_price;
    $voucher->reduction = $request->reduction;
    $voucher->start_date = $request->start_date;
    $voucher->end_date = $request->end_date;
    $voucher->use_max = $request->use_max;
    $voucher->used = 0;
    $voucher->save();

    return response()->json([
      'message' => 'Thêm khuyến mãi thành công',
      $voucher
    ], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $voucher = Voucher::findOrFail($id);

    return response()->json($voucher);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateVoucherRequest $request, string $id)
  {
    $voucher = Voucher::findOrFail($id);
    $voucher->code = $request->code;
    $voucher->min_price = $request->min_price;
    $voucher->reduction = $request->reduction;
    $voucher->start_date = $request->start_date;
    $voucher->end_date = $request->end_date;
    $voucher->use_max = $request->use_max;
    $voucher->save();

    return response()->json([
      'message' => 'Cập nhật khuyến mãi thành công',
      $voucher
    ]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    Voucher::findOrFail($id)->delete();

    return response()->json([
      'message' => 'Xóa khuyến mãi thành công'
    ]);
  }
  public function check(Request $request,string $code, string $price)
  {
    $voucher = Voucher::where('code', $code)->first();
    $reduction= 0;
    $msg = '';
      if ($voucher) {
        if (Carbon::now()->gt(Carbon::parse(($voucher->end_date)))) {
          $msg = 'Voucher đã hết hạn.';
        }
        else if ($voucher->used > $voucher->use_max) {
          $msg = 'Voucher đã hết lượt sử dụng.';
        }
        else if ($voucher->min_price > (int)$price) {
          $msg = 'Đơn giá tối thiểu là ' . $voucher->min_price . ' VND';
        }
        else {
          $msg = 'Sử dụng voucher thành công';
          $reduction= $voucher->reduction;
        }
      } else $msg = 'Voucher không hợp lệ!';
    return response()->json([
      $reduction,
      $msg,
    ], 200);
  }
}
