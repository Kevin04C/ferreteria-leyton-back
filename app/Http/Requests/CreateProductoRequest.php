<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateProductoRequest extends FormRequest
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
            'cantidad' => 'required|numeric',
            'descripcion' => 'required|string|max:100',
            'precio' => 'required|numeric',
            'activo' => 'required|boolean',
            'categoria_id' => 'required|numeric',
            'provedor_id' => 'required|numeric',
            'imagen' => 'file|image|required',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.numeric' => 'La cantidad debe ser un numero',
            'descripcion.required' => 'La descripcion es requerida',
            'descripcion.string' => 'La descripcion debe ser una cadena de caracteres',
            'descripcion.max' => 'La descripcion debe tener maximo 100 caracteres',
            'precio.required' => 'El precio es requerido',
            'precio.numeric' => 'El precio debe ser un numero',
            'categoria_id.required' => 'La categoria es requerida',
            'categoria_id.numeric' => 'La categoria debe ser un numero',
            'provedor_id.required' => 'El proveedor es requerido',
            'provedor_id.numeric' => 'El proveedor debe ser un numero',
            'imagen.file' => 'La imagen debe ser un archivo',
            'imagen.image' => 'La imagen debe ser una imagen',
            'imagen.required' => 'La imagen es requerida',
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
