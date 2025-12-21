<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaActivo extends Model
{
    protected $fillable = ['activo_fijo_id', 'user_id', 'tipo', 'detalle', 'fecha'];
    protected $casts = ['fecha' => 'datetime'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
