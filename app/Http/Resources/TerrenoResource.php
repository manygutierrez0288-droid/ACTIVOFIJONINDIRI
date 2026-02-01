<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerrenoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activo_fijo_id' => $this->activo_fijo_id,
            'dominio' => $this->dominio,
            'numero_escritura' => $this->numero_escritura,
            'area_metros_cuadrados' => $this->area_metros_cuadrados,
            'frente_metros' => $this->frente_metros,
            'fondo_metros' => $this->fondo_metros,
            'lindero_norte' => $this->lindero_norte,
            'lindero_sur' => $this->lindero_sur,
            'lindero_este' => $this->lindero_este,
            'lindero_oeste' => $this->lindero_oeste,
            'coordenadas_gps' => $this->coordenadas_gps,
            'uso_suelo' => $this->uso_suelo,
            'zonificacion' => $this->zonificacion,
            'valor_catastral' => $this->valor_catastral,
            'area_calculada' => $this->area_calculada,
            'valor_por_metro_cuadrado' => $this->valor_por_metro_cuadrado,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Incluir datos del activo fijo relacionado
            'activo_fijo' => $this->whenLoaded('activoFijo', function () {
                return new ActivoFijoResource($this->activoFijo);
            }),
        ];
    }
}
