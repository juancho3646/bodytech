<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     description="Show all products",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return $this->sendResponse(ProductResource::collection($products), "Products retrieved successfully");
    }

    /**
     * Create a Product.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     description="Create a product",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), Product::$rules, Product::$errorMessages);
        if ($validate->fails()) return $this->sendError("Bad request, something has wrong", $validate->errors(), 400);
        $product = Product::create($request->all());
        return $this->sendResponse(new ProductResource($product), "Product created successfully");
    }

    /**
     * Display an specified product
     * @param  integer $product
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     description="Home page",
     *     @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Retrieve data successful"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function show(int $id)
    {
        $product = Product::find($id);
        if (empty($product)) return $this->sendError("Product not found", [], 404);
        return $this->sendResponse(new ProductResource($product), "Product retrieved successfully");
    }

    /**
     * Update the specified product.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $product
     * @return \Illuminate\Http\Response
     *
     * @OA\Put (
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     description="Update a product",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function update(Request $request, int $id)
    {
        $validate = Validator::make($request->all(), Product::$rules, Product::$errorMessages);
        if ($validate->fails()) $this->sendError("Bad request, something has wrong", $validate->errors(), 400);
        $product = Product::find($id);
        if (empty($product)) return $this->sendError("Product not found", [], 404);
        $product->fill($request->all())->save();
        return $this->sendResponse(new ProductResource($product), "Product updated successfully");
    }

    /**
     * Remove the specified product from storage.
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete (
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     description="Remove product from storage",
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
    public function destroy(int $id)
    {
        $product = Product::find($id);
        if (empty($product)) return $this->sendError("Product not found", [], 404);
        $product->delete();
        return $this->sendResponse([], "Product deleted successfully");
    }
}
