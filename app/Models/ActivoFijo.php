<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\AuditoriaObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy([AuditoriaObserver::class])]
class ActivoFijo extends Model
{
    use HasFactory;
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
        'user_modificacion_id',
        'codigo_inventario',
        'imagen_url',
        'valor_residual_automatico',
        'numero_serie'
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor_adquisicion' => 'decimal:2',
        'valor_residual' => 'decimal:2',
        'depreciacion_acumulada' => 'decimal:2',
        'valor_residual_automatico' => 'boolean',
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

    public function terreno(): HasOne
    {
        return $this->hasOne(Terreno::class);
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
    protected $appends = [
        'depreciacion_anual',
        'depreciacion_mensual',
        'depreciacion_acumulada_calculada',
        'valor_neto_calculado',
        'meses_depreciados'
    ];

    // Accessors
    public function getDepreciacionAnualAttribute()
    {
        if ($this->vida_util_anios > 0) {
            return ($this->valor_adquisicion - $this->valor_residual) / $this->vida_util_anios;
        }
        return 0;
    }

    public function getDepreciacionMensualAttribute()
    {
        if ($this->vida_util_anios > 0) {
            return $this->depreciacion_anual / 12;
        }
        return 0;
    }

    public function getMesesDepreciadosAttribute()
    {
        if (!$this->fecha_adquisicion) {
            return 0;
        }
        $fechaAdquisicion = \Carbon\Carbon::parse($this->fecha_adquisicion);
        $hoy = \Carbon\Carbon::now();

        if ($hoy->lessThan($fechaAdquisicion)) {
            return 0;
        }

        return (int) floor($fechaAdquisicion->diffInMonths($hoy));
    }

    public function getDepreciacionAcumuladaCalculadaAttribute()
    {
        $maxDepreciacion = round($this->valor_adquisicion - $this->valor_residual, 2);
        $depreciacion = round($this->depreciacion_mensual * $this->meses_depreciados, 2);

        // Ensure it doesn't exceed the depreciable amount
        if ($depreciacion > $maxDepreciacion) {
            return $maxDepreciacion;
        }
        return $depreciacion;
    }

    public function getValorNetoCalculadoAttribute()
    {
        return round($this->valor_adquisicion - $this->depreciacion_acumulada_calculada, 2);
    }
}
