<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'correo' => 'string|required|email',
            'estado' => 'boolean|required',
            'roles_id' => 'nullable|array'
        ];
    }

    public function messages()
    {
        return [
            'correo.required' => 'El correo es requerido',
            'correo.email' => 'El correo no es valido',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres',
            'estado.required' => 'El estado del usuario es requerido',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'type' => 'error',
            'messages' => [$validator->errors()],
            'data' => null
        ], 400));
    }
}
