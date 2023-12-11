<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CrearProveedorRequest extends FormRequest
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
            'nombre' => 'required|string',
            'telefono' => 'required | int',
            'correo' => 'required | email',
            'direccion' => 'required | string',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres',
            'telefono.required' => 'El telefono es requerido',
            'telefono.int' => 'El telefono debe ser un numero',
            'correo.required' => 'El correo es requerido',
            'correo.email' => 'El correo debe ser un correo valido',
            'direccion.required' => 'La direccion es requerida',
            'direccion.string' => 'La direccion debe ser una cadena de caracteres',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'type' => 'error',
            'messages' => [$validator->errors()],
            'data' => []
        ], 400));
    }

}
