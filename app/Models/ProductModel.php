<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = "product";

    protected $fillable = [
        'id',
        'code',
        'level',
        'name',
        'price',
        'image',
        'order',
        'price_num',
        'comment',
        'point',
        'status'
    ];


}
