<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    protected $fillable = ['activo_fijo_id', 'fecha', 'descripcion', 'costo', 'tecnico_id', 'proveedor_id', 'estado_id', 'user_id'];
    protected $casts = ['fecha' => 'date', 'costo' => 'decimal:2'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Tecnico::class);
    }
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoActivo::class, 'estado_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
