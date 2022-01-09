<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      title="Product",
 *      description="Product model",
 *      type="object",
 *      required={"name", "description", "price", "image"}
 * )
 */
class Product extends Model
{
    /**
     * @OA\Property(title="id", example=1, description="Id of product", property="id")
     * @var integer,
     * @OA\Property(title="name", example="Barra", description="Name of product", property="name")
     * @var string,
     * @OA\Property(title="description", example="Barra para pecho", description="Description of product", property="description")
     * @var string,
     * @OA\Property(title="price", example=10000, description="Price of product", property="price")
     * @var integer,
     * @OA\Property(title="offer_price", example=8000, description="Offer price of product", property="offer_price")
     * @var integer,
     * @OA\Property(title="expiration_offer", example="2022-02-01", description="Expiration offer price of product", property="expiration_offer")
     * @var \DateTime,
     * @OA\Property(title="image", example="http://test.com/image.jpg", description="Image of product", property="image")
     * @var string,
     */

    protected $fillable = [
        'name',
        'description',
        'price',
        'offer_price',
        'expiration_offer',
        'image'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'price' => 'integer',
        'offer_price' => 'integer',
        'expiration_offer' => 'datetime',
        'image' => 'string',
    ];

    public static $rules = [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'offer_price' => 'numeric',
        'expiration_offer' => 'date',
        'image' => 'required'
    ];

    public static $errorMessages = [
        'name.required' => 'The name is required',
        'description.required' => 'The description is required',
        'price.required' => 'The price is required',
        'price.numeric' => 'The price is numeric',
        'offer_price.numeric' => 'The offer_price is numeric',
        'image.required' => 'The image is required',
        'expiration_offer.date' => 'The expiration_offer is date (YYYY-mm-dd)'
    ];
}
