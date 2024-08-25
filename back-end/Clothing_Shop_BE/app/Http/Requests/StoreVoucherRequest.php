<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            'code'=> 'required',
            'start_date'=> 'required|date|after:today',
            'end_date'=> 'required|date|after:start_date',
            'use_max'=> 'required|integer',
        ];
    }
    public function messages(): array
    {
      return [
        'code.required'=> 'Mã giảm giá không được để trống.',
        'start_date.required'=> 'Ngày bắt đầu không được để trống.',
        'start_date.after'=> 'Ngày bắt đầu sau ngày hôm nay',
        'end_date.required'=> 'Ngày kết thúc không được để trống.',
        'end_date.after'=> 'Ngày kết thúc sau ngày bắt đầu.',
        'use_max.required'=> 'Số lượt dùng tối đa không được để trống.',
        'use_max.integer'=> 'Số lượt dùng tối đa không hợp lệ.',
      ];
    }
}
