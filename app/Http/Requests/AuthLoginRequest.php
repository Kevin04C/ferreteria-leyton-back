<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
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
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'correo.required' => 'El correo es requerido',
            'correo.email' => 'El correo debe ser un correo valido',
            'contrasena.required' => 'La contraseña es requerida',
            'contrasena.string' => 'La contraseña debe ser un texto',
            'contrasena.min' => 'La contraseña debe tener minimo 8 caracteres'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'type' => 'error',
            'messages' => [$validator->errors()],
            'data' => []
        ], 400));
    }
}
