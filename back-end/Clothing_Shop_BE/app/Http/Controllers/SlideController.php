<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides= Slide::latest()->get();
        return response()->json($slides);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->file('image'));
        $rules= [
          'title'=> 'required',
          'image'=> 'required|mimes:jpg,jpeg,png,gif,webp|max:20000'
        ];
        $messages= [
          'title.required'=> 'Nhập tiêu đề slide',
          'image.required'=> 'Chọn hình ảnh slide',
          'image.mimes'=> 'Chỉ chọn file .png, .jpg, .jpeg, .gif, .webp',
          'image.max'=> 'Chỉ chọn file tối đa 20MB'
        ];
        $request->validate($rules, $messages);
        $slide= new Slide;
        $slide->title= $request->title;
        if ($request->hasFile('image')) {
          $image = $request->file('image')->store('admin/slide', 'public');
          $imageLink = 'http://127.0.0.1:8000' . Storage::url($image);
          $slide->image = $imageLink;
        }
        $slide->save();

        return response()->json([
          'message' => 'Thêm slide thành công!',
          'slide' => $slide,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slide $slide)
    {
        return response()->json($slide);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slide $slide)
    {
      $rules= [
        'image'=> 'mimes:jpg,jpeg,png,gif,webp|max:20000'
      ];
      $messages= [
        'image.mimes'=> 'Chỉ chọn file .png, .jpg, .jpeg, .gif, .webp',
        'image.max'=> 'Chỉ chọn file tối đa 20MB'
      ];
      $request->validate($rules, $messages);
      $slide->title= $request->title;
      if ($request->hasFile('image')) {
        //Delete Image At Storage (Local)
        $imageOld = $slide->image;
        $imageOld = str_replace('http://127.0.0.1:8000/storage/', '', $imageOld);
        Storage::disk('public')->delete($imageOld);
       //Update Image At DB
       $image = $request->file('image')->store('admin/slide', 'public');
       $imageLink = 'http://127.0.0.1:8000' . Storage::url($image);
       $slide->image = $imageLink;
     }
      $slide->save();

      return response()->json([
        'message' => 'Cập nhật slide thành công!',
        'slide' => $slide
      ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slide $slide)
    {
      Storage::disk('public')->delete($slide->image);
      $slide->delete();

      return response()->json([
        'message' => 'Xóa slide thành công!'
      ], 200, [], JSON_UNESCAPED_UNICODE);
    }

}
