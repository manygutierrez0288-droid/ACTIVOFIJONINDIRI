<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioDetalle extends Model
{
    protected $fillable = [
        'inventario_id',
        'activo_fijo_id',
        'verificado',
        'fecha_verificacion',
        'estado_fisico_id',
        'comentarios',
        'verificado_por'
    ];

    protected $casts = [
        'verificado' => 'boolean',
        'fecha_verificacion' => 'datetime',
    ];

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }

    public function estadoFisico(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_fisico_id');
    }

    public function verificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
