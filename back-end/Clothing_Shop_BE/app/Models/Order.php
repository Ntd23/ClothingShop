<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = [
    'amount',
    'payment_method',
    'receiver_user',
    'phone',
    'status'
  ];
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
  public function voucher()
  {
    return $this->belongsTo(Voucher::class, 'voucher_id');
  }
  public function order_details()
  {
    return $this->hasMany(OrderDetail::class);
  }
}
