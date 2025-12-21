<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CambioEstado extends Model
{
    protected $fillable = ['activo_fijo_id', 'estado_anterior_id', 'estado_nuevo_id', 'fecha', 'user_id'];
    protected $casts = ['fecha' => 'datetime'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
    public function estadoAnterior(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_anterior_id');
    }
    public function estadoNuevo(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_nuevo_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
