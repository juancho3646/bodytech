<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="ReportRequest",
 *      description="Request to reports",
 *      type="object",
 *      required={"date_from", "date_to"}
 * )
 */
class ReportRequest extends FormRequest
{
    /**
     * @OA\Property(title="date_from", example="2022-01-01", description="Initial date to Report", property="date_from")
     * @var \DateTime,
     * @OA\Property(title="date_to", example="2022-01-01", description="End date to Report", property="date_to")
     * @var \DateTime,
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
            'date_from' => 'required|date',
            'date_to' => 'required|date'
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
            'date_from.required' => 'A date_from is required',
            'date_from.date' => 'A date_from is dateformat (Y-m-d)',
            'date_to.required' => 'A date_to is required',
            'date_to.date' => 'A date_to is dateformat (Y-m-d)'
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
