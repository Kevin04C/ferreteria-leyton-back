<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;

class DNIController extends Controller
{
    public function searchUser(Request $request)
    {
        try {
            $dni = $request->query('dni');
            $response = Http::withHeaders([
                'Authorization' => "Bearer apis-token-4628.U6wSoPMOJoJ3fh5n44XVjJAKRJzfswrz",
            ])
                ->get("https://api.apis.net.pe/v2/reniec/dni?numero=$dni");

            if ($response->status() === 404) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No se encontro el usuario'],
                    'data' => []
                ], 404);
            }
            $response = json_decode($response->body());
            $name = $response->nombres;
            $lastName = $response->apellidoPaterno . ' ' . $response->apellidoMaterno;

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => [
                    'nombres' => $name,
                    'apellidos' => $lastName
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }
}
