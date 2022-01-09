<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AddItemToCartRequest;
use App\Http\Resources\ShoppingCartResource;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ShoppingCartController extends BaseController
{
    /**
     * Add Item to shopping cart.
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/shoppingCart/addItem",
     *     tags={"Shopping Cart"},
     *     description="Add item to shopping cart",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AddItemToCartRequest")
     *      ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="default", description="Bad Request")
     * )
     */
    public function addItemToShoppingCart(AddItemToCartRequest $request)
    {
        $userID = Auth::id();
        $shoppingCart = ShoppingCart::where('id_user', $userID)->where('status', 'open')->first();
        if (empty($shoppingCart)) {
            $shoppingCart = new ShoppingCart([
                "id_user" => $userID,
                "price" => 0,
                "tax" => 0,
                "total_price" => 0,
                "status" => "open"
            ]);
            $shoppingCart->save();
        }
        $product = Product::find($request->input('id_product'));
        if (empty($product)) return $this->sendError("Product not found", [], 404);
        $shoppingCartItem = ShoppingCartItems::where('id_shopping_cart', $shoppingCart['id'])->where('id_product', $product['id'])->first();
        if (!empty($shoppingCartItem)) return $this->sendError("Item exist", [], 400);
        $unitPrice = $product['offer_price'] != 0 ? $product['offer_price'] : $product['price'];
        $shoppingCartItem = new ShoppingCartItems([
            'id_shopping_cart' => $shoppingCart['id'],
            'id_product' => $product['id'],
            'quantity' => $request->input('quantity'),
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $request->input('quantity'),
        ]);
        $shoppingCartItem->save();
        return $this->sendResponse($shoppingCartItem, "Product added successfully");
    }

    /**
     * Remove existing item from shopping cart
     *
     * @OA\Delete (
     *     path="/api/shoppingCart/removeItem/{id}",
     *     tags={"Shopping Cart"},
     *     description="Remove item to cart",
     *     @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function removeItemFromShoppingCart(int $id)
    {
        $userID = Auth::id();
        $shoppingCart = ShoppingCart::where('id_user', $userID)->where('status', 'open')->first();
        $product = Product::find($id);
        if (empty($product)) return $this->sendError("Product not found", [], 404);
        $shoppingCartItem = ShoppingCartItems::where('id_shopping_cart', $shoppingCart['id'])->where('id_product', $id)->first();
        if (empty($shoppingCartItem)) return $this->sendError("Item not found", [], 404);
        $shoppingCartItem->delete();
        return $this->sendResponse($shoppingCartItem, "Product remove successfully");
    }

    /**
     * Add Item to shopping cart.
     *
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/shoppingCart/showMyCart",
     *     tags={"Shopping Cart"},
     *     description="Show my shopping cart and all Items",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="default", description="Bad Request")
     * )
     */
    public function showMyShoppingCart()
    {
        $userID = Auth::id();
        $shoppingCart = ShoppingCart::where('id_user', $userID)->where('status', 'open')->with('items')->first();
        if (empty($shoppingCart))  return $this->sendError("Shopping cart not found", [], 404);
        $total = 0;
        foreach ($shoppingCart['items'] as $item) {
            $total += $item['total_price'];
        }
        $shoppingCart['total_price'] = $total;
        $shoppingCart['tax'] = $total * 0.19;
        $shoppingCart['price'] = $total * 0.81;
        $shoppingCart->save();

        return $this->sendResponse(new ShoppingCartResource($shoppingCart), "Shopping cart retrieved successfully");
    }
}
