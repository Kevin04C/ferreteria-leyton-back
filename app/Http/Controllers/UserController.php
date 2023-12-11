<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponseHtpp;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Usuario;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        try {
            $search = $request->query('search');
            $users = Usuario::where('nombre', 'LIKE', "%{$search}%")
                ->orWhere('correo', 'LIKE', "%{$search}%")
                ->get();
            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => new UserCollection($users)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        try {
            $id = $request->id;
            $user = Usuario::find($id);

            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => ['El usuario no existe'],
                    'data' => []
                ], 404);
            }

            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => new UserResource($user),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $idUser = $request->id;
            $user = Usuario::find($idUser);
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => ['El usuario no existe'],
                    'data' => []
                ], 404);
            }
            if (auth()->user()->id === $user->id) {
                return response()->json([
                    'type' => 'error',
                    'message' => ['No se puede eliminar a si mismo'],
                    'data' => []
                ], 400);
            }

            $user->delete();
            return response()->json([
                'type' => 'success',
                'message' => ['Usuario eliminado correctamente'],
                'data' => new UserResource($user),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor', $th->getMessage()],
                'data' => []
            ], 500);
        }
    }

    public function updateUser(UserUpdateRequest $request)
    {
        try {
            $id = $request->id;
            $user = Usuario::find($id);

            // si el usuario no existe retornamos un error
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => ['El Usuario no existe'],
                    'data' => []
                ], 404);
            }

            $user->correo = $request->correo;
            $user->estado = $request->estado;

            // si vienen nuevos roles se los agregamos al usuario
            if (isset($request->roles_id)) {
                $user->roles()->sync($request->roles_id);
            }

            $user->save();

            return response()->json([
                'type' => 'success',
                'messages' => ['Usuario actualizado correctamentea'],
                'data' => new UserResource($user),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        try {
            $id = $request->id;
            $user = Usuario::find($id);
            // si el usuario no existe retornamos un error
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => ['El Usuario no existe'],
                    'data' => []
                ], 404);
            }
            $user->contrasena = Hash::make($request->contrasena);
            $user->save();
            return response()->json([
                'type' => 'success',
                'messages' => ['Contraseña actualizada correctamente'],
                'data' => new UserResource($user),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }
    public function createUser(CreateUserRequest $request)
    {
        try {
            $password_hased = Hash::make($request->contrasena);

            $user = Usuario::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'dni' => $request->dni,
                'correo' => $request->correo,
                'contrasena' => $password_hased,
                'estado' => true
            ]);

            $roles = $request->roles_id;
            $user->roles()->sync($roles);


            return response()->json([
                'type' => 'success',
                'message' => ['Usuario creado correctamente'],
                'data' => new UserResource($user),
            ], 201);

        } catch (\Throwable $th) {
            throw new ErrorResponseHtpp();
        }
    }

    public function getTotalUser()
    {
        try {
            $total = Usuario::count();
            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => $total
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => ['Error interno del servidor'],
                'data' => []
            ], 500);
        }
    }
}
