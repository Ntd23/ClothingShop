<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'content'=> 'required'
        ];
    }
    public function messages(): array
    {
      return [
        'content.required'=> 'Vui lòng nhập nội dung.'
      ];
    }
}
