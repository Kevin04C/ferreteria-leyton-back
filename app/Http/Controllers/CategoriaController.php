<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponseHtpp;
use App\Http\Requests\ActualizarCategoriaRequest;
use App\Http\Requests\CrearCategoriaRequest;
use App\Http\Resources\CategoriaCollection;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{


    public function obtenerCategorias(Request $request)
    {
        $search = $request->query('search');
        $data = [];
        $messages = [];
        $type = 'success';
        $result = [];
        try {
            $categoria = Categoria::where('nombre', 'like', "%$search%")
                ->get();
            if ($categoria->isEmpty()) {
                $type = 'warning';
                $messages[] = 'No se encontraron categorias ';
            } else {
                $result = new CategoriaCollection($categoria);
            }
        } catch (\Exception $e) {
            $type = 'error';
            $messages[] = 'Error interno del servidor: ' . $e->getMessage();
            return response()->json(['messages' => $messages, 'type' => $type], 500);
        }

        $data['messages'] = $messages;
        $data['type'] = $type;
        $data['data'] = $result;

        return response()->json($data);
    }

    public function crearCategoria(CrearCategoriaRequest $request)
    {
        try {
            $categoria = Categoria::create([
                'nombre' => $request->nombre,
                'estado' => true
            ]);
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new CategoriaResource($categoria),
            ], 201);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function obtenerCategoria(Request $request, int $id)
    {
        try {
            $categoria = Categoria::find($id);
            if (!$categoria) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['La categoria no existe'],
                    'data' => [],
                ], 404);
            }
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new CategoriaResource($categoria),
            ], 200);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request, int $id)
    {
        try {
            $categoria = Categoria::find($id);
            if (!$categoria) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro la categoria'],
                    'data' => [],
                ], 404);
            }

            $categoria->nombre = $request->nombre;
            $categoria->estado = $request->activo;

            $categoria->save();

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new CategoriaResource($categoria),
            ], 200);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }

    }

    public function eliminarCategoria(Request $request, int $id)
    {
        try {
            $categoria = Categoria::find($id);
            if (!$categoria) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro la categoria'],
                    'data' => [],
                ], 404);
            }
            $categoria->delete();
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new CategoriaResource($categoria),
            ], 200);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function getAllActiveCategories()
    {
        try {
            $categorias = Categoria::where('estado', true)
                ->get();
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new CategoriaCollection($categorias),
            ], 200);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }


}
