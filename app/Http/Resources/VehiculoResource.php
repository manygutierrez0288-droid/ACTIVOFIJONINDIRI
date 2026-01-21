<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activo_fijo_id' => $this->activo_fijo_id,
            'placa' => $this->placa,
            'numero_circulacion' => $this->numero_circulacion,
            'anio' => $this->anio,
            'kilometraje' => $this->kilometraje,
            'tipo_combustible' => $this->tipo_combustible,
            'capacidad_pasajeros' => $this->capacidad_pasajeros,

            // ActivoFijo data
            'nombre' => $this->activoFijo->nombre,
            'codigo_inventario' => $this->activoFijo->codigo_inventario,
            'numero_serie' => $this->activoFijo->numero_serie,
            'categoria' => $this->activoFijo->categoria ? $this->activoFijo->categoria->nombre : null,
            'departamento' => $this->activoFijo->departamento ? $this->activoFijo->departamento->nombre : null,
            'ubicacion' => $this->activoFijo->ubicacion ? $this->activoFijo->ubicacion->nombre : null,
            'marca' => $this->activoFijo->marca ? $this->activoFijo->marca->nombre : null,
            'modelo' => $this->activoFijo->modelo ? $this->activoFijo->modelo->nombre : null,
            'color' => $this->activoFijo->color ? $this->activoFijo->color->nombre : null,
            'estado' => $this->activoFijo->estado ? $this->activoFijo->estado->nombre : null,
            'responsable' => $this->activoFijo->responsable ? $this->activoFijo->responsable->nombre : null,
            'fecha_adquisicion' => $this->activoFijo->fecha_adquisicion ? \Carbon\Carbon::parse($this->activoFijo->fecha_adquisicion)->format('Y-m-d') : null,
            'valor_adquisicion' => $this->activoFijo->valor_adquisicion,
            'valor_neto' => $this->activoFijo->valor_neto_calculado,
            'imagen_url' => $this->activoFijo->imagen_url ? asset('storage/' . $this->activoFijo->imagen_url) : null,

            // IDs for editing
            'categoria_id' => $this->activoFijo->categoria_id,
            'departamento_id' => $this->activoFijo->departamento_id,
            'ubicacion_id' => $this->activoFijo->ubicacion_id,
            'marca_id' => $this->activoFijo->marca_id,
            'modelo_id' => $this->activoFijo->modelo_id,
            'color_id' => $this->activoFijo->color_id,
            'fuente_id' => $this->activoFijo->fuente_id,
            'proveedor_id' => $this->activoFijo->proveedor_id,
            'responsable_id' => $this->activoFijo->responsable_id,
            'estado_id' => $this->activoFijo->estado_id,
            'vida_util_anios' => $this->activoFijo->vida_util_anios,
            'valor_residual' => $this->activoFijo->valor_residual,
        ];
    }
}
