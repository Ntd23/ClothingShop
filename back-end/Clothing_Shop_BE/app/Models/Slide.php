<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Slide extends Model
{
    use HasFactory;
    // use HasApiTokens, Notifiable;
    protected $fillable= ['title', 'image'];
}
