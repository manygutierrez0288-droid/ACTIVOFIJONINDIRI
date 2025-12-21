<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivoFijoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria ? $this->categoria->nombre : null,
            'departamento' => $this->departamento ? $this->departamento->nombre : null,
            'ubicacion' => $this->ubicacion ? $this->ubicacion->nombre : null,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'valor_adquisicion' => $this->valor_adquisicion,
            'estado' => $this->estado,
            'categoria_id' => $this->categoria_id,
            'departamento_id' => $this->departamento_id,
            'ubicacion_id' => $this->ubicacion_id,
        ];
    }
}
