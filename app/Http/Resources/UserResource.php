<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'dni' => $this->dni,
            'correo' => $this->correo,
            'estado' => $this->estado,
            'roles' => $this->roles->pluck('rol'),
            'roles_id' => $this->roles->pluck('id_rol')
        ];
    }
}
