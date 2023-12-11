<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddProductToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'producto_id' => 'integer | required',
            'cantidad' => 'integer | required',
        ];
    }
    public function messages()
    {
        return [
            'producto_id.required' => 'El id del producto es requerido',
            'producto_id.integer' => 'El id del producto debe ser un string',
            'quantity.required' => 'La cantidad es requerida',
            'cantidad.integer' => 'La cantidad debe ser un numero entero',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'type' => 'error',
            'messages' => [$validator->errors()],
            'data' => []
        ], 400));
    }

}
