<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Terreno extends Model
{
    protected $fillable = [
        'activo_fijo_id',
        'numero_escritura',
        'area_metros_cuadrados',
        'frente_metros',
        'fondo_metros',
        'lindero_norte',
        'lindero_sur',
        'lindero_este',
        'lindero_oeste',
        'coordenadas_gps',
        'uso_suelo',
        'zonificacion',
        'valor_catastral',
    ];

    protected $casts = [
        'area_metros_cuadrados' => 'decimal:2',
        'frente_metros' => 'decimal:2',
        'fondo_metros' => 'decimal:2',
        'valor_catastral' => 'decimal:2',
    ];

    protected $appends = [
        'area_calculada',
        'valor_por_metro_cuadrado',
    ];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }

    // Cálculo automático del área si hay frente y fondo
    public function getAreaCalculadaAttribute()
    {
        if ($this->frente_metros && $this->fondo_metros) {
            return round($this->frente_metros * $this->fondo_metros, 2);
        }
        return null;
    }

    // Valor por metro cuadrado
    public function getValorPorMetroCuadradoAttribute()
    {
        if ($this->area_metros_cuadrados > 0 && $this->activoFijo) {
            return round($this->activoFijo->valor_adquisicion / $this->area_metros_cuadrados, 2);
        }
        return 0;
    }
}
