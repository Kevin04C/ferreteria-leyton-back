<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponseHtpp;
use App\Http\Requests\ActualizarProveedorRequest;
use App\Http\Requests\CrearProveedorRequest;
use App\Http\Resources\ProveedorCollection;
use App\Http\Resources\ProveedorResource;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{

    public function obtenerProveedores(Request $request)
    {
        $search = $request->query('search');
        $data = [];
        $messages = [];
        $type = 'success';
        $result = [];

        try {
            $proveedores = Proveedor::where('nombre', 'like', "%$search%")
                ->get();
            if ($proveedores->isEmpty()) {
                $type = 'warning';
                $messages[] = 'No se encontraron proveedores';
            } else {
                $result = new ProveedorCollection($proveedores);
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
    public function crearProveedor(CrearProveedorRequest $request)
    {
        try {
            $proveedor = Proveedor::create([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'estado' => true,
            ]);
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new ProveedorResource($proveedor),
            ], 201);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function actualizarProveedor(ActualizarProveedorRequest $request, int $id)
    {
        try {
            $proveedor = Proveedor::find($id);
            if (!$proveedor) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['El proveedor no existe'],
                    'data' => [],
                ]);
            }
            $proveedor->nombre = $request->nombre;
            $proveedor->telefono = $request->telefono;
            $proveedor->correo = $request->correo;
            $proveedor->direccion = $request->direccion;
            $proveedor->estado = $request->activo;

            $proveedor->save();

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new ProveedorResource($proveedor),
            ]);

        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function eliminarProveedor(Request $request, int $id)
    {
        try {
            $proveedor = Proveedor::find($id);
            if (!$proveedor) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['El proveedor no existe'],
                    'data' => [],
                ]);
            }
            $proveedor->delete();
            return response()->json([
                'type' => 'success',
                'messages' => ['Proveedor eliminado correctamente'],
                'data' => new ProveedorResource($proveedor),
            ]);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }

    public function getAllSuppliers()
    {
        try {
            $proveedores = Proveedor::all();
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new ProveedorCollection($proveedores),
            ]);
        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp(500);
        }
    }


}

