<?php

namespace App\Http\Requests;

use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="RegisterRequest",
 *      description="Register user to access",
 *      type="object",
 *      required={"name", "email", "password", "repeat_password"}
 * )
 */
class RegisterRequest extends FormRequest
{
    /**
     * @OA\Property(title="name", example="Jhon", description="Name of user", property="name")
     * @var string,
     * @OA\Property(title="email", example="Jhon@email.com", description="Email of user", property="email")
     * @var string,
     * @OA\Property(title="password", example="Jh0n2022!", description="Password of user", property="password")
     * @var string,
     * @OA\Property(title="repeat_password", example="Jh0n2022!", description="Repeat password to validate", property="repeat_password")
     * @var string
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
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
            'name.required' => 'A name is required',
            'email.required' => 'An email is required',
            'email.email' => 'The email is an email format(jhon@email.com)',
            'email.unique' => 'The email exist',
            'password.required' => 'A password id required',
            'repeat_password.required' => 'A repeat_password is required',
            'repeat_password.same' => 'The password and repeat_password are not equals',
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
