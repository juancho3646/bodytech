<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItems extends Model
{
    protected $fillable = [
        'id_shopping_cart',
        'id_product',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'id_shopping_cart' => 'integer',
        'id_product' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'total_price' => 'integer',
    ];
}
