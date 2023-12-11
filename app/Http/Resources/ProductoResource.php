<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id_producto,
            'nombre' => $this->nombre,
            'imagen' => $this->imagen,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'cantidad' => $this->cantidad,
            'activo' => $this->estado,
            'categoria' => [
                    'id' => $this->categorium()->first()->id_categoria,
                    'nombre' => $this->categorium()->first()->nombre,
                ],
            'proveedor' => [
                'id' => $this->proveedor()->first()->id_proveedor,
                'nombre' => $this->proveedor()->first()->nombre,
            ],
        ];
    }

}
