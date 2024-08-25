<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   */
     public function index()
  {
    $products = Product::with(['category', 'images'])->latest()->get();
    return response()->json([
      $products,
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateProductRequest $request)
  {
    $product = new Product;
    $product_details= new ProductDetails;
    $product_details->quantity= $request->quantity;
    $product_details->size= json_encode($request->size);
    $product_details->color= json_encode($request->color);

    $imageStore = '';
    if ($request->hasFile('image')) {
      $imageStore = $request->file('image')->store('admin/product', 'public');
    }
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->cate_id = $request->cate_id;
    $product->save();

    $imageLink = 'http://127.0.0.1:8000' . Storage::url($imageStore);

    $product->images()->create([
      'url'=> $imageLink,
      'product_id'=> $product->id
    ]);
    // dd($product->id);die;

    $product_details->product_id= $product->id;
    $product_details->save();
    $product->update([
      'inventory'=> $product_details->quantity  // quantity - bought
    ]);

    return response()->json([
      'message' => 'Thêm mới sản phẩm thành công',
      'product' => $product,
      'category' => $product->category()->first()->name,
      'images' => $product->images()->first()->url,
    ], 201, [], JSON_UNESCAPED_UNICODE);
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    return response()->json([
      'product' => $product,
      'image'=> Image::where('product_id', $product->id)->get(),
      'category'=> Category::where('id', $product->cate_id)->get(),
      'product_details'=> ProductDetails::where('product_id',$product->id)->first(),
      'size'=> json_decode(ProductDetails::where('product_id',$product->id)->first()->size),
      'color'=> json_decode(ProductDetails::where('product_id',$product->id)->first()->color),
    ], 200);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateProductRequest $request, Product $product)
  {
    $product_details= ProductDetails::where('product_id',$product->id)->first();
    $product_details->update([
      'size'=> json_encode($request->size),
      'color'=> json_encode($request->color),
      'quantity'=> $request->quantity,
    ]);

    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->inventory = $product_details->quantity;
    $product->cate_id = $request->cate_id;
    $product->save();

    if ($request->hasFile('image')) {
      //Delete Image At Storage (Local)
      $imageOld = $product->images()->first()->url;
      $imageOld = str_replace('http://127.0.0.1:8000/storage/', '', $imageOld);
      Storage::disk('public')->delete($imageOld);

      //Update Image At DB
      $imageNew = $request->file('image')->store('admin/product', 'public');
      $imageLink = 'http://127.0.0.1:8000' . Storage::url($imageNew);
      $product->images()->update([
        'product_id'=> $product->id,
        'url' => $imageLink
      ]);
    }

    return response()->json([
      'message' => 'Cập nhật sản phẩm thành công!',
      'product' => $product,
      'category' => $product->category()->first()->name,
      'images' => $product->images()->first()->url,
    ], 200, [], JSON_UNESCAPED_UNICODE);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    if($product->images()->first()->url) {
      $imageOld = $product->images()->first()->url;
      $imageOld = str_replace('http://127.0.0.1:8000/storage/', '', $imageOld);
      Storage::disk('public')->delete($imageOld);
    }

    //Delete Image At DB
    Image::where('product_id', $product->id)->delete();

    //Delete Product
    $product->delete();

    return response()->json([
      'message' => 'Xóa sản phẩm thành công!'
    ], 200, [], JSON_UNESCAPED_UNICODE);
  }
  public function filterByCategory($parent_id) {
       // Truy vấn các danh mục con dựa trên parent_id
       $categoryIds = Category::where('parent_id', $parent_id)->pluck('id');

       // Truy vấn các sản phẩm dựa trên danh mục con
       $products = Product::with(['category', 'images'])
           ->whereIn('cate_id', $categoryIds)
           ->latest()->get();

       return response()->json($products);
  }
}
