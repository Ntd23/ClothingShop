<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable= ['code', 'min_price', 'reduction', 'start_date', 'end_date', 'use_max', 'used', 'status'];
}
