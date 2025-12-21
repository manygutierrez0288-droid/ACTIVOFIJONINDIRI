<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResponsableResource extends JsonResource
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
            'cargo_id' => $this->cargo_id,
            'cargo_nombre' => $this->cargo ? $this->cargo->nombre : null,
            'numero_cedula' => $this->numero_cedula,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'activo' => $this->activo,
        ];
    }
}
