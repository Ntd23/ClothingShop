<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable= [
      'name', 'description', 'price', 'inventory', 'status', 'cate_id'
    ];
    public function category() {
      return $this->belongsTo(Category::class, 'cate_id');
    }
    public function images() {
      return $this->hasMany(Image::class);
    }
    public function product_details() {
      return $this->hasMany(ProductDetails::class);
    }
}
