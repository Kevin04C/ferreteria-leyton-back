<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getCustomerPurchases()
    {
        try {
            $result = DB::table('ventas as v')
                ->select('v.dni', 'v.nombres', 'v.apellidos', DB::raw('count(*) as compras'))
                ->groupBy('v.dni', 'v.nombres', 'v.apellidos')
                ->limit(10)
                ->get();
                
            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => $result
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }

    }
}
