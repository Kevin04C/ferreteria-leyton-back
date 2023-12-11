<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Usuario;
use App\Models\UsuariosRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        try {
            $password_hased = Hash::make($request->contrasena);

            $user = Usuario::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'dni' => $request->dni,
                'correo' => $request->correo,
                'contrasena' => $password_hased
            ]);

            UsuariosRole::create([
                'usuario_id' => $user->id,
                'rol_id' => 1
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'type' => 'success',
                'messages' => ['Usuario creado correctamente'],
                'data' => new UserResource($user),
                'token' => $token
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $credentials = request(['correo', 'contrasena']);
            $user = Usuario::where('correo', $credentials['correo'])->first();

            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['Usuario o contraseña incorrectos'],
                    'data' => []
                ], 400);
            }
            $match_password = Hash::check($credentials['contrasena'], $user->contrasena);

            if (!$match_password) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['Usuario o contraseña incorrectos'],
                    'data' => []
                ], 400);
            }

            $token = auth()->login($user);

            return response()->json([
                'type' => 'success',
                'messages' => [],
                'data' => new UserResource($user),
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'messages' => ['Error interno del servidor' . $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function renewToken(Request $request)
    {
        try {
            $header_token = $request->header('Authorization');
            if (!$header_token || is_null($header_token)) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['No existe token en la petición'],
                    'data' => []
                ], 401);
            }

            if (!str_starts_with($header_token, 'Bearer ')) {
                return response()->json([
                    'type' => 'error',
                    'messages' => ['Token no valido'],
                    'data' => []
                ], 401);
            }

            $user = auth()->user();
            $renew_token = auth()->refresh();
            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => new UserResource($user),
                'token' => $renew_token,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 400);
        }
    }
}
