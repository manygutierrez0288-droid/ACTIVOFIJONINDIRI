<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    protected $fillable = ['activo_fijo_id', 'ubicacion_origen_id', 'ubicacion_destino_id', 'responsable_origen_id', 'responsable_destino_id', 'fecha', 'motivo', 'user_id'];
    protected $casts = ['fecha' => 'datetime'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
    public function ubicacionOrigen(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_origen_id');
    }
    public function ubicacionDestino(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_destino_id');
    }
    public function responsableOrigen(): BelongsTo
    {
        return $this->belongsTo(PersonalResponsable::class, 'responsable_origen_id');
    }
    public function responsableDestino(): BelongsTo
    {
        return $this->belongsTo(PersonalResponsable::class, 'responsable_destino_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
