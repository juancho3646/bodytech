<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = [
        'id_user',
        'price',
        'tax',
        'total_price',
        'status'
    ];

    protected $casts = [
        'id_user' => 'integer',
        'price' => 'integer',
        'tax' => 'integer',
        'total_price' => 'integer',
        'status' => 'string',
    ];
}
