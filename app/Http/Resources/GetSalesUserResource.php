<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSalesUserResource extends JsonResource
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
            'product_name' => $this->producto->nombre,
            'product_imagen' => $this->producto->imagen,
            'product_precio' => $this->producto->precio,
            'cantidad' => $this->cantidad,
            'total' => $this->total,
        ];
    }
}
