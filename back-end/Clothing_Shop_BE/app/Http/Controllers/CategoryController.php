<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function getCategoryParent()
  {
    $categories = Category::with('children')->get(); // Lấy tất cả các danh mục cùng với con của chúng

    // dd($categories);die;
    // Lọc các danh mục để loại bỏ các danh mục con ở cấp nhỏ nhất
    $filteredCategories = $categories->filter(function ($category) {
      return is_null($category->parent_id) || $category->children->isNotEmpty();
    });
    return response()->json([
      'categories' => $filteredCategories,
    ], 200);
  }
  public function getCategoryChildren()
  {
    // Lấy tất cả danh mục cùng với danh mục cha của chúng -> Lọc ra các danh mục con nhỏ nhất
    $categories = Category::with('parent')->get()->filter(function ($category) {
      return $category->children->isEmpty();
    });

    // Tìm và gán tên danh mục cha lớn nhất
    $categories->each(function ($category) {
      $ancestor = $category;
      while ($ancestor->parent) {
        $ancestor = $ancestor->parent;
      }
      $category->ancestor_name = $ancestor->name;
    });
    return response()->json([
      'categories' => $categories
    ]);
  }
  public function index()
  {
    $categories = Category::with('parent')->latest()->get();
    return response()->json([
      'categories' => $categories,
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateCategoryRequest $request)
  {
    // dd($request->image);die;
    $category = new Category;


    $category->name = $request->name;
    if ($request->hasFile('image')) {
      $image = $request->file('image')->store('admin/category', 'public');
      $imageLink = 'http://127.0.0.1:8000' . Storage::url($image);
      $category->image = $imageLink;
    }
    $category->status = 0;
    $category->parent_id = $request->parent_id;
    $category->save();

    return response()->json([
      'message' => 'Thêm danh mục thành công!',
      'category' => $category,
    ], 201, [], JSON_UNESCAPED_UNICODE);
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category)
  {
    return response()->json(['category' => $category], 200);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCategoryRequest $request, Category $category)
  {
    $category->name = $request->name;
    if ($request->hasFile('image')) {
       //Delete Image At Storage (Local)
       $imageOld = $category->image;
       $imageOld = str_replace('http://127.0.0.1:8000/storage/', '', $imageOld);
       Storage::disk('public')->delete($imageOld);
      //Update Image At DB
      $image = $request->file('image')->store('admin/category', 'public');
      $imageLink = 'http://127.0.0.1:8000' . Storage::url($image);
      $category->image = $imageLink;
    }
    if($request->parent_id) {
      $category->parent_id= $request->parent_id;
    }
    $category->save();
    return response()->json([
      'message' => 'Cập nhật danh mục thành công!',
      'category' => $category
    ], 200, [], JSON_UNESCAPED_UNICODE);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    Storage::disk('public')->delete($category->image);
    $category->delete();

    return response()->json([
      'message' => 'Xóa danh mục thành công!'
    ], 200, [], JSON_UNESCAPED_UNICODE);
  }
  public function search($keywords) {
    $results= Category::where('name', 'LIKE' ,'%'.$keywords.'%')->get();
    return response()->json($results);
  }
  //filter
  public function getCateByParentIdIsNull() {
    $categories= Category::whereNull('parent_id')->get();
    return response()->json($categories);
  }
  public function getCateChildByCateParent($parent_id) {
    $categories= Category::with('parent')->where('parent_id', '=', $parent_id)->get();
    return response()->json($categories);
  }
}
