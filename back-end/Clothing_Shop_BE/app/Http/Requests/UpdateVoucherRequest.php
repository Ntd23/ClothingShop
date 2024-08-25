<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
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
            'start_date'=> 'date|after:today',
            'end_date'=> 'date|after:start_date',
            'use_max'=> 'integer',
        ];
    }
    public function messages(): array
    {
      return [
        'start_date.after'=> 'Ngày bắt đầu sau ngày hôm nay',
        'end_date.after'=> 'Ngày kết thúc sau ngày bắt đầu.',
        'use_max.integer'=> 'Số lượt dùng tối đa không hợp lệ.',
      ];
    }
}
