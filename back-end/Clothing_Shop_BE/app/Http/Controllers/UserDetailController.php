<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class UserDetailController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(int $user_id)
  {
    $user_details= UserDetail::where('user_id', $user_id)->get();

    return response([$user_details], 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $rules = [
      'fullname' => 'required',
      'email' => 'required|email',
      'address' => 'required',
      'phone' => 'required'
    ];
    $errors = [
      'fullname.required' => 'Tên người nhận không để trống!',
      'email.required' => 'Email không để trống!',
      'email.email' => 'Email không hợp lệ!',
      'address.required' => 'Địa chỉ không để trống!',
      'phone.required' => 'Số điện thoại không để trống!'
    ];
    $request->validate($rules, $errors);
    $user_details= User::where('id', $request->user_id)->first()->user_details();
    $user_details->create([
      'fullname'=> $request->fullname,
      'email'=> $request->email,
      'phone'=> $request->phone,
      'address'=> $request->address,
    ]);
    $sucess_message = 'Thêm địa chỉ thành công!';
    return response()->json([$user_details,$sucess_message], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(UserDetail $userDetail)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, UserDetail $userDetail)
  {
    $rules = [
      'fullname' => 'required',
      'email' => 'required|email',
      'address' => 'required',
      'phone' => 'required'
    ];
    $errors = [
      'fullname.required' => 'Tên người nhận không để trống!',
      'email.required' => 'Email không để trống!',
      'email.email' => 'Email không hợp lệ!',
      'address.required' => 'Địa chỉ không để trống!',
      'phone.required' => 'Số điện thoại không để trống!'
    ];
    $request->validate($rules, $errors);
    $userDetail->update([
      'fullname'=> $request->fullname,
      'email'=> $request->email,
      'phone'=> $request->phone,
      'address'=> $request->address,
    ]);
    $msg= 'Cập nhật địa chỉ thành công!';
    return response()->json([$userDetail, $msg], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(UserDetail $userDetail)
  {
    $userDetail->delete();

    $msg= 'Đã xóa địa chỉ!';
    return response()->json($msg, 200);
  }
}
