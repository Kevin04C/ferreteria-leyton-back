<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewSaleRequest;
use App\Http\Resources\SalesCollection;
use App\Http\Resources\SalesResource;
use App\Models\DetalleVentum;
use App\Models\Producto;
use App\Models\Venta;
use App\services\CloudinaryService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function __construct(
        private CloudinaryService $cloudinaryService
    ) {
    }
    public function newSale(NewSaleRequest $request)
    {

        try {
            // $user = auth()->user();
            $sale = new Venta();
            $sale->dni = $request->dni;
            $sale->nombres = $request->nombres;
            $sale->apellidos = $request->apellidos;
            $sale->fecha = now();
            $sale->vendedor = $request->vendedor_id;
            $sale->save();

            $products = $request->productos;

            $productsData = [];
            $total = 0;

            foreach ($products as $product) {
                $productDB = Producto::find($product['id']);

                $productsData[] = [
                    'name' => $productDB->nombre,
                    'price' => $productDB->precio,
                    'quantity' => $product['cantidad'],
                    'total' => $productDB->precio * $product['cantidad']
                ];
                $total += $productDB->precio * $product['cantidad'];

                DetalleVentum::create([
                    'venta_id' => $sale->id_venta,
                    'producto_id' => $product['id'],
                    'cantidad' => $product['cantidad'],
                    'total' => $productDB->precio * $product['cantidad']
                ]);
                $productDB->cantidad -= $product['cantidad'];
                $productDB->save();
            }
            $pdf = Pdf::loadView('pdfs.sale', [
                'customer' => $request->nombres . " " . $request->apellidos,
                'dni' => $request->dni,
                'products' => $productsData,
                'total' => $total,
                'date' => date('d-m-Y H:i', strtotime($sale->fecha))
            ]);

            $pdf->setPaper('a4', 'portrait');
            $pdf->render();
            $file = $pdf->output();
            $result = $this->cloudinaryService->uploadPDF($file);

            \App\Models\Pdf::create([
                'ventas_id' => $sale->id_venta,
                'pdf_url' => $result['url'],
            ]);

            return response()->json([
                'type' => 'success',
                'messages' => ['Venta registrada correctamente'],
                'data' => new SalesResource($sale),
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function getSales(Request $request)
    {
        try {
            $search = $request->query('search');
            $paginator = Venta::where('nombres', 'like', "%$search%")
                ->paginate(10);

            return response()->json([
                'type' => 'success',
                'messages' => ['Ventas obtenidas correctamente'],
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'count' => $paginator->count(),
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'first_page' => $paginator->onFirstPage(),
                'last_page' => $paginator->onLastPage(),
                'data' => new SalesCollection($paginator->items())
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function getTotalSales()
    {
        try {
            $total = Venta::count();
            return response()->json([
                'type' => 'success',
                'messages' => ['Total de ventas obtenidas correctamente'],
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
}
