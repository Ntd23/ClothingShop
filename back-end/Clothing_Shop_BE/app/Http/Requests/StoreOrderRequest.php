<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method'=> 'required',
            'receive_user'=> 'required',
            'email'=> 'required',
            'address'=> 'required',
            'phone'=> 'required|numeric',
        ];
    }
    public function messages(): array
    {
        return [
            'payment_method.required'=> 'Vui lòng chọn phương thức thanh toán.',
            'receive_user.required'=> 'Vui lòng nhập tên người nhận.',
            'email.required'=> 'Vui lòng nhập email.',
            'address.required'=> 'Vui lòng nhập địa chỉ nhận hàng.',
            'phone.required'=> 'Vui lòng nhập số điện thoại người nhận.',
            'phone.numeric'=> 'Số điện thoại không hợp lệ.',
        ];
    }
}
