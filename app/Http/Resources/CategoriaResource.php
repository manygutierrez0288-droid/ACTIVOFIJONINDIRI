<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'activo' => $this->activo,
            'porcentaje_valor_residual' => $this->porcentaje_valor_residual,
            'vida_util_anios' => $this->vida_util_anios,
            'grupo_categoria' => $this->grupo_categoria,
            'subcategoria' => $this->subcategoria,
            'clase' => $this->clase,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
