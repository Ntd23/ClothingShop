<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers= Voucher::latest()->get();

        return response()->json($vouchers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        $voucher= new Voucher;
        $voucher->code= $request->code;
        $voucher->start_date= $request->start_date;
        $voucher->end_date= $request->end_date;
        $voucher->use_max= $request->use_max;
        $voucher->used= 0;
        $voucher->save();

        return response()->json([
          'message'=> 'Thêm khuyến mãi thành công',
          $voucher
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher= Voucher::findOrFail($id);

        return response()->json($voucher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, string $id)
    {
        $voucher= Voucher::findOrFail($id);
        $voucher->code= $request->code;
        $voucher->start_date= $request->start_date;
        $voucher->end_date= $request->end_date;
        $voucher->use_max= $request->use_max;
        $voucher->save();

        return response()->json([
          'message'=> 'Cập nhật khuyến mãi thành công',
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
          'message'=> 'Xóa khuyến mãi thành công'
        ]);
    }
}
