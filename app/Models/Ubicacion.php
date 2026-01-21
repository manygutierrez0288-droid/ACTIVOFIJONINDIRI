<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function activos(): HasMany
    {
        return $this->hasMany(ActivoFijo::class);
    }

    public function movimientosOrigen(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'ubicacion_origen_id');
    }

    public function movimientosDestino(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'ubicacion_destino_id');
    }
}
