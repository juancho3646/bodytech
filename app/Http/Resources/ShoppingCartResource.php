<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_user' => $this->id_user,
            'price' => $this->price,
            'tax' => $this->tax,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'items' => ShoppingCartItemsResource::collection($this->items),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d')
        ];
    }
}
