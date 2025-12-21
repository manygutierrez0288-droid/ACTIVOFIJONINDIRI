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
            'marca' => $this->marca ? $this->marca->nombre : null,
            'modelo' => $this->modelo ? $this->modelo->nombre : null,
            'color' => $this->color ? $this->color->nombre : null,
            'proveedor' => $this->proveedor ? $this->proveedor->nombre : null,
            'responsable' => $this->responsable ? $this->responsable->nombre : null,
            'estado_nombre' => $this->estado ? $this->estado->nombre : null,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'valor_adquisicion' => $this->valor_adquisicion,
            'categoria_id' => $this->categoria_id,
            'departamento_id' => $this->departamento_id,
            'ubicacion_id' => $this->ubicacion_id,
            'marca_id' => $this->marca_id,
            'modelo_id' => $this->modelo_id,
            'color_id' => $this->color_id,
            'fuente_id' => $this->fuente_id,
            'proveedor_id' => $this->proveedor_id,
            'responsable_id' => $this->responsable_id,
            'estado_id' => $this->estado_id,
        ];
    }
}
