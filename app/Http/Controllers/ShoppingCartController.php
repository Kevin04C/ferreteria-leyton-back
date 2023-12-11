<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductToCartRequest;
use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\Producto;
use Request;

class ShoppingCartController extends Controller
{
    public function addProductToCart(AddProductToCartRequest $request)
    {
        try {
            $shoppingCart = null;
            $user = auth()->user();
            $cart = Carrito::where('usuario_id', $user->id)->first();

            if (!$cart) {
                $cart = Carrito::create([
                    'usuario_id' => $user->id,
                ]);
            }

            $product = Producto::find($request->producto_id);

            if ($request->cantidad > $product->cantidad) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No hay suficiente stock'],
                    'data' => []
                ], 400);
            }

            // verifica que el producto no este en carrito
            $productInCart = CarritoProducto::where('carrito_id', $cart->id_carrito)
                ->where('producto_id', $product->id_producto)
                ->first();

            if (!$productInCart) {
                $shoppingCart = CarritoProducto::create([
                    'carrito_id' => $cart->id_carrito,
                    'producto_id' => $product->id_producto,
                    'cantidad' => $request->cantidad,
                    'total' => $product->precio * $request->cantidad,
                ]);
            }

            if ($productInCart) {
                if ($request->cantidad > $product->cantidad) {
                    return response()->json([
                        'type' => 'error',
                        'messages' => ['No hay suficiente stock'],
                        'data' => []
                    ], 400);
                }
                if ($request->cantidad === 1 && !$request->increment) {
                    $productInCart->cantidad = 1;
                    $productInCart->total = $product->precio * $request->cantidad;
                    $productInCart->save();
                }
                $newQuantity = $productInCart->cantidad + $request->cantidad;
                if (
                    $request->cantidad === 1 &&
                    $newQuantity <= $product->cantidad &&
                    $request->increment
                ) {
                    $productInCart->cantidad += 1;
                    $productInCart->save();
                } else {
                    $productInCart->cantidad = $request->cantidad;
                    $productInCart->save();
                }
            }
            return response()->json([
                'type' => 'success',
                'messages' => ['Producto agregado al carrito'],
                'data' => [
                    'carrito_producto_id' => $shoppingCart
                        ? $shoppingCart->id
                        : $productInCart->id,
                    'product_id' => $product->id_producto,
                    'producto_nombre' => $product->nombre,
                    'producto_precio' => $product->precio,
                    'producto_stock' => $product->cantidad,
                    'producto_imagen' => $product->imagen,
                    'cantidad' => $shoppingCart
                        ? $shoppingCart->cantidad
                        : $productInCart->cantidad,
                    'total' => $shoppingCart
                        ? $shoppingCart->cantidad * $product->precio
                        : $productInCart->cantidad * $product->precio,
                ],
                'addProduct' => $productInCart ? false : true
            ]);



        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }
    public function getUserCart(Request $request)
    {
        try {
            $user = auth()->user();
            $cart = Carrito::where('usuario_id', $user->id)->first();
            // dd($cart->id_carrito);
            if (!$cart) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro el carrito del usuario'],
                    'data' => []
                ], 404);
            }
            $shoppingCartDetails = CarritoProducto::where('carrito_id', $cart->id_carrito)->get();

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => $shoppingCartDetails->map(function ($data) {
                    return [
                        'carrito_producto_id' => $data->id,
                        'producto_id' => $data->producto_id,
                        'producto_nombre' => $data->producto->nombre,
                        'producto_precio' => $data->producto->precio,
                        'producto_stock' => $data->producto->cantidad,
                        'producto_imagen' => $data->producto->imagen,
                        'cantidad' => $data->cantidad,
                        'total' => $data->cantidad * $data->producto->precio,
                    ];
                }),
                'totalProducts' => $shoppingCartDetails->count(),
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor', $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function deleteProductToCart(Request $request, int $id)
    {
        try {
            $shoppingCartDetail = CarritoProducto::find($id);
            if (!$shoppingCartDetail) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro el producto en el carrito'],
                    'data' => []
                ], 404);
            }

            $product = Producto::find($shoppingCartDetail->producto_id);

            $shoppingCartDetail->delete();

            return response()->json([
                'type' => 'success',
                'messages' => ['Producto eliminado del carrito'],
                'data' => [
                    'carrito_producto_id' => $shoppingCartDetail->id,
                    'producto_id' => $product->id,
                    'producto_nombre' => $product->name,
                    'producto_precio' => $product->price,
                    'producto_stock' => $product->stock,
                    'producto_imagen' => $product->image,
                ],
                'totalProducts' => CarritoProducto::where('carrito_id', $shoppingCartDetail->carrito_id)->count(),
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor', $th->getMessage()],
                'data' => []
            ], 500);

        }
    }


}
