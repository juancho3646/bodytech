<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="AddItemToCartRequest",
 *      description="Add item to shopping cart",
 *      type="object",
 *      required={"id_product", "quantity"}
 * )
 */
class AddItemToCartRequest extends FormRequest
{
    /**
     * @OA\Property(title="id_product", example=1, description="Id of product to add", property="id_product")
     * @var integer,
     * @OA\Property(title="quantity", example=2, description="Quantity of product to add", property="quantity")
     * @var integer
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_product' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id_product.required' => 'A product is required',
            'id_product.numeric' => 'A product is numeric',
            'id_product.exists' => 'The product not found',
            'quantity.required' => 'A quantity is required',
            'quantity.numeric' => 'A quantity is numeric',
        ];
    }

    /**
     * Return the error response if validation is failed.
     *
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'success' => false,
            'message' => 'Bad request, something was wrong',
            'data' => $errors->messages(),
        ], 400);

        throw new HttpResponseException($response);
    }
}
