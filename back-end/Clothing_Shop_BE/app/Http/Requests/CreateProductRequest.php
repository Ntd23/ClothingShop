<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name'=> 'required',
            'description'=> 'required',
            'price'=> 'required|numeric',
            'cate_id'=> 'required',
            'image'=> 'required|mimes:jpeg,png,jpg,gif,webp|max:20000',
            'quantity'=> 'required',
            'size'=> 'required',
            'color'=> 'required'
        ];
    }
    public function messages():array {
      return [
        'name.required'=> 'Tên sản phẩm không được trống',
        'description.required'=> 'Mô tả sản phẩm không được trống',
        'price.required'=> 'Giá sản phẩm không được trống',
        'price.numeric'=> 'Giá tiền sản phẩm phải là số',
        'cate_id.required'=> 'Danh mục sản phẩm không được bỏ trống',
        'image.required'=> 'Vui lòng chọn hình ảnh',
        'image.mimes'=> 'Hình ảnh phải là .jpeg, .jpg, .png, .gif, .webp',
        'image.max'=> 'Kích thước ảnh tối đa 20MB',
        'quantity.required'=> 'Vui lòng chọn số lượng',
        'size.required'=> 'Vui lòng chọn size',
        'color.required'=> 'Vui lòng chọn màu',
      ];
    }
}
