<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivoFijo extends Model
{
    protected $fillable = [
        'nombre',
        'categoria_id',
        'departamento_id',
        'ubicacion_id',
        'marca_id',
        'modelo_id',
        'color_id',
        'fuente_id',
        'proveedor_id',
        'responsable_id',
        'estado_id',
        'fecha_adquisicion',
        'valor_adquisicion',
        'vida_util_anios',
        'valor_residual',
        'depreciacion_acumulada',
        'user_creacion_id',
        'user_modificacion_id'
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor_adquisicion' => 'decimal:2',
        'valor_residual' => 'decimal:2',
        'depreciacion_acumulada' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
    public function modelo(): BelongsTo
    {
        return $this->belongsTo(Modelo::class);
    }
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
    public function fuente(): BelongsTo
    {
        return $this->belongsTo(Fuente::class);
    }
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(PersonalResponsable::class, 'responsable_id');
    }
    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_id');
    }
    public function userCreacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_creacion_id');
    }
    public function userModificacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_modificacion_id');
    }

    public function vehiculo(): HasOne
    {
        return $this->hasOne(Vehiculo::class);
    }
    public function bajas(): HasMany
    {
        return $this->hasMany(BajaActivo::class);
    }
    public function cambiosEstado(): HasMany
    {
        return $this->hasMany(CambioEstado::class);
    }
    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }
    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class);
    }
    public function auditorias(): HasMany
    {
        return $this->hasMany(AuditoriaActivo::class);
    }
}
