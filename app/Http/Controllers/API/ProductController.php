<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     *     security={{"token": {}}},
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
     *     security={{"token": {}}},
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
     *     security={{"token": {}}},
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
     *     security={{"token": {}}},
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
     *     security={{"token": {}}},
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

    /**
     * Upload products from file .csv
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post (
     *     path="/api/products/uploadFile",
     *     tags={"Products"},
     *     description="Upload products from .csv",
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *          name="products_file",
     *          description="Products file",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          )
     *      ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function uploadFile(Request $request)
    {
        $file = $request->file('products_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); // Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); // Get size of uploaded file in bytes
            // Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            // Where uploaded file will be stored on the server
            $location = 'uploads'; // Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database
            $filepath = public_path($location . "/" . $filename);
            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            // Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
                $num = count($filedata);
                Log::info(["NUM", $num]);
                Log::info(["filedata", $filedata]);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    Log::info($filedata[$c]);
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); // Close after reading
            $j = 0;
            // Log::info([$filename, $extension, $fileSize, $filepath]);
            Log::info($importData_arr);
            foreach ($importData_arr as $importData) {
                $name = $importData[0]; //Get user names
                Log::info($name);
                $j++;
                try {
                    DB::beginTransaction();
                    $product = new Product([
                        'name' => $importData[0],
                        'description' => $importData[1],
                        'price' => $importData[2],
                        'offer_price' => $importData[3],
                        'expiration_offer' => empty($importData[4]) ? null : $importData[4],
                        'image' => $importData[5]
                    ]);
                    $product->save();
                    DB::commit();
                } catch (\Exception $e) {
                    // throw $th;
                    Log::error($e);
                    DB::rollBack();
                }
            }
            return $this->sendResponse([], "$j records successfully uploaded");
        } else {
            return $this->sendError('No file was uploaded', ['file' => 'File not Found'], 400);
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); // Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize > $maxFileSize) {
                return $this->sendError('No file was uploaded', ['file' => 'File exceded is too large, max (2MB)'], 413);
            }
        } else {
            return $this->sendError('Invalid file extension', ['file' => 'Unsupported media file (.csv, .xlsx)'], 415);
        }
    }
}
