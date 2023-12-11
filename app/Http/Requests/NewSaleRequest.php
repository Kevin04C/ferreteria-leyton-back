<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewSaleRequest extends FormRequest
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
            "productos" => 'required|array',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'dni' => 'required|string',
            'vendedor_id' => 'nullable|integer',
        ];
    }
    public function messages()
    {
        return [
            'productos.required' => 'Los productos son requeridos',
            'nombres.required' => 'Debe ingresar un nombre de cliente',
            'nombres.string' => 'El nombre debe ser un string',
            'apellidos.required' => 'Debe ingresar un apellido de cliente',
            'apellidos.string' => 'El apellido debe ser un string',
            'dni.required' => 'Debe ingresar un DNI de cliente',
            'dni.string' => 'El DNI debe ser un string',
            'vendedor_id' => 'El vendedor debe ser un entero'
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
