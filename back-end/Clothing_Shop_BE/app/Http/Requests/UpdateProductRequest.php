<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
      'import_price' => 'numeric',
      'selling_price' => 'numeric',
      'image' => 'mimes:jpeg,png,jpg,gif,webp|max:20000',
    ];
  }
  public function message()
  {
    return [
      'import_price.numeric' => 'Giá nhập không đúng định dạng',
      'selling_price.numeric' => 'Giá nhập không đúng định dạng',
      'image.mimes' => 'Hình ảnh phải là .jpeg, .jpg, .png, .gif, .webp',
      'iamge.max' => 'Kích thước ảnh tối đa 20MB'
    ];
  }
}
