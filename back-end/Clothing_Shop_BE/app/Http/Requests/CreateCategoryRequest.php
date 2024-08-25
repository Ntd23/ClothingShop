<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'name'=> 'required|max:255',
            'image'=> 'required|mimes:png,jpg,jpeg,gif|max:20000',
            'parent_id'=> 'nullable'
        ];
    }
    public function messages(): array {
      return [
        'name.required'=> 'Vui lòng nhập tên danh mục',
        'name.max'=> 'Tên danh mục không quá 255 ký tự',
        'image.required'=> 'Vui lòng chọn hình ảnh',
        'image.mimes'=> 'Chỉ chọn file .png, .jpg, .jpeg, .gif',
        'image.max'=> 'Chỉ chọn file tối đa 20MB'
      ];
    }
}
