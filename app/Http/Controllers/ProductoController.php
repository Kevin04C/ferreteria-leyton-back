<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductoRequest;
use App\Http\Resources\ProductoCollection;
use App\Http\Resources\ProductoResource;
use App\Models\DetalleVentum;
use App\Models\Producto;
use App\services\CloudinaryService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

    public function obtenerProductosPaginados(Request $request)
    {
        try {
            $search = $request->query('search');
            $maxPrice = $request->query('maxPrice');
            $minPrice = $request->query('minPrice', 0);

            $paginator = Producto::where('nombre', 'like', "%$search%")
                ->paginate(10);

            // trae los productos que esten en el rango de precio
            if ($maxPrice) {
                $paginator = Producto::where('nombre', 'like', "%$search%")
                    ->whereBetween('precio', [$minPrice, $maxPrice])
                    ->paginate(10);
            }


            return response()->json([
                'type' => 'success',
                'messages' => [],
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'count' => $paginator->count(),
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'first_page' => $paginator->onFirstPage(),
                'last_page' => $paginator->onLastPage(),
                'data' => new ProductoCollection($paginator->items()),
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Internal server error' . $th->getMessage()],
                'data' => []

            ], 500);
        }
    }
    public function obtenerProductoPorId(Request $request, $id)
    {
        try {
            $product = Producto::find($id);
            if (!$product) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontró el producto con el ID especificado'],
                    'data' => []
                ], 404);
            }
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new ProductoResource($product)
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Internal server error'],
                'data' => []
            ], 500);
        }
    }


    public function crearProducto(CreateProductoRequest $request)
    {
        try {
            $image = $request->file('imagen');
            $result = CloudinaryService::uploadImage($image);

            $product = new Producto();
            $product->nombre = $request->nombre;
            $product->cantidad = $request->cantidad;
            $product->precio = $request->precio;
            $product->imagen = $result['url'];
            $product->estado = $request->activo;
            $product->imagen_id = $result['public_id'];
            $product->descripcion = $request->descripcion;
            $product->categoria_id = $request->categoria_id;
            $product->provedor_id = $request->provedor_id;
            $product->save();


            return response()->json([
                'type' => 'success',
                'messages' => ['Producto creado correctamente'],
                'data' => new ProductoResource($product)
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }
    public function actualizarProducto(Request $request, $id)
    {
        try {
            $product = Producto::find($id);
            if (!$product) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontró el producto con el ID especificado'],
                    'data' => []
                ], 404);
            }

            $image = $request->file('imagen');

            $product->nombre = $request->nombre;
            $product->cantidad = $request->cantidad;
            $product->precio = $request->precio;

            if ($image) {
                // eliminamos la imagen anterior
                if ($product->imagen_id) {
                    CloudinaryService::deleteImage($product->imagen_id);
                }
                $result = CloudinaryService::uploadImage($image);
                $product->imagen = $result['url'];
                $product->imagen = $result['url'];
                $product->imagen_id = $result['public_id'];
            }

            $product->estado = $request->activo;
            $product->descripcion = $request->descripcion;
            $product->categoria_id = $request->categoria_id;
            $product->provedor_id = $request->provedor_id;
            $product->save();


            return response()->json([
                'type' => 'success',
                'messages' => ['Producto Actualizado correctamente'],
                'data' => new ProductoResource($product)
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }
    public function eliminarProducto(Request $request, int $id)
    {
        try {
            $product = Producto::find($id);
            if (!$product) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontró el producto con el ID especificado'],
                    'data' => []
                ], 404);
            }

            // verifica si el producto ha sido vendido
            $sales = DetalleVentum::where('producto_id', $id)->count();
            if ($sales > 0) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se puede eliminar el producto tiene ventas registradas'],
                    'data' => []
                ], 400);
            }
            CloudinaryService::deleteImage($product->imagen_id);
            $product->delete();

            return response()->json([
                'type' => 'success',
                'messages' => ['Producto eliminado correctamente'],
                'data' => new ProductoResource($product)
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function getTotalProducts()
    {
        try {
            $total = Producto::count();
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => $total
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }

    public function getAllProductActive()
    {
        try {
            $products = Producto::where('estado', 1)->get();
            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new ProductoCollection($products)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor'],
                'data' => []
            ], 500);

        }
    }


}
