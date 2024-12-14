<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RePasswordRequest extends FormRequest
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
            'current_password'=> 'required',
            'new_password'=> 'required|min:8|confirmed'
        ];
    }
    public function messages(): array
    {
        return [
            'current_password.required'=> 'Nhập mật khẩu hiện tại',
            'new_password.required'=> 'Nhập mật khẩu mới',
            'new_password.min'=> 'Mật khẩu tối thiểu 8 kí tự',
            'new_password.confirmed'=> 'Mật khẩu không trùng khớp'
        ];
    }
}
