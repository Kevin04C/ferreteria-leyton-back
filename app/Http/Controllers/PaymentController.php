<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\CarritoProducto;
use App\Models\DetalleVentum;
use App\Models\Venta;
use App\services\MercadoPagoService;
use Http;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private MercadoPagoService $mercadoPagoService
    ) {

    }

    public function createPayment(Request $request)
    {
        try {
            $user = auth()->user();
            $cart = Carrito::where('usuario_id', $user->id)->first();
            if (!$cart) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro el carrito del usuario'],
                    'data' => []
                ], 404);
            }
            $shoppingCartDetails = CarritoProducto::where('carrito_id', $cart->id_carrito)->get();

            if ($shoppingCartDetails->count() === 0) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No hay productos en el carrito'],
                    'data' => []
                ], 400);
            }

            $preference = $this->mercadoPagoService->generatePreference($shoppingCartDetails);

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => [
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point,
                ],

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $e->getMessage()],
                'data' => [],
            ], 500);
        }
    }
    public function checkPayment(Request $request)
    {
        try {
            $payment_id = $request->payment_id;
            $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id?access_token=" . env('MP_ACCESS_TOKEN'));
            
            $response = json_decode($response);

            $user = auth()->user();


            if ($response->status !== 'approved') {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['El pago no fue aprobado'],
                    'data' => [],
                ], 400);
            }

            $sales = Venta::create([
                'dni' => $user->dni,
                'nombres' => $user->nombre,
                'apellidos' => $user->apellidos,
                'vendedor' => $user->id,
                'vendido' => false
            ]);

            $cart = Carrito::where('usuario_id', $user->id)->first();
            $shoppingCartDetails = CarritoProducto::where('carrito_id', $cart->id_carrito)->get();

            foreach ($shoppingCartDetails as $item) {
                DetalleVentum::create([
                    'venta_id' => $sales->id_venta,
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'total' => $item->cantidad * $item->producto->precio,
                ]);
            }

            $shoppingCartDetails->each->delete();

            return response()->json([
                'type' => 'success',
                'messages' => ['Pago Aprobado'],
                'data' => [
                    'payment_id' => $response->id,
                ],
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $e->getMessage()],
                'data' => [],
            ]);
        }
    }
}
