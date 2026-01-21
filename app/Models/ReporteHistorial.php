<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteHistorial extends Model
{
    protected $table = 'reporte_historial';

    protected $fillable = [
        'tipo_reporte',
        'codigo_generado',
        'user_id',
        'anio',
        'secuencial'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
