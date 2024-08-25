<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $rules = [
      'name' => 'required|max:255',
      'email' => 'required|email|unique:users,email',
      'phone' => 'required|string|max:12|unique:users,sodienthoai',
      'password' => 'required|string|min:8|confirmed',
    ];
    $customErrors = [
      'name.required' => 'Please enter your username.',
      'name.max' => 'Username is too long.',
      'email.required' => 'Please enter your email.',
      'email.email' => 'Email is invalid.',
      'email.unique' => 'Your email has been registered.',
      'phone.required' => 'Please enter your phone number.',
      'phone.max' => 'Phone number maximum 12 digits.',
      'phone.unique' => 'Your number phone has been registered.',
      'password.required' => 'Please enter your password.',
      'password.min' => 'Password minimum 8 characters.',
      'password.confirmed' => 'Password confirm was wrong.',
    ];
    $request->validate($rules, $customErrors);
    $user = new User;
    $user->tendangnhap = $request->name;
    $user->email = $request->email;
    $user->sodienthoai = $request->phone;
    $user->matkhau = Hash::make($request->password);
    $user->save();
    $token = $user->createToken('authToken')->plainTextToken;
    $message_success = 'Khách hàng đăng ký tài khoản thành công! Vui lòng đăng nhập lại.';
    return response()->json([
      'user' => $user,
      'token'=> $token,
      'message_success'=> $message_success
    ], 201, [], JSON_UNESCAPED_UNICODE);
  }
  public function login(LoginRequest $request)
  {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->matkhau)) {
      return response([
        'message_error' => 'Email hoặc mật không chính xác!'
      ], 401);
    }
    $token = $user->createToken('authToken')->plainTextToken;
    $role= 0;
    if($user->vaitro==1) $role=1;
    return response()->json([
      'user' => $user,
      'role'=> $role,
      'token' => $token,
      'message_success'=> 'Chúc mừng! Khách hàng đã đăng nhập thành công.'
    ], 201, [], JSON_UNESCAPED_UNICODE);
  }
  public function logout(Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message'=> 'Logged out']);
  }
}
