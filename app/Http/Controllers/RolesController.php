<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getRoles()
    {
        try {
            $roles = Role::all();
            return response()->json([
                'type' => 'success',
                'message' => [],
                'data' => $roles
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
