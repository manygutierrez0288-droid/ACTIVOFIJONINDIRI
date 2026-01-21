<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaActivo extends Model
{
    protected $fillable = [
        'activo_fijo_id',
        'usuario_id',
        'accion',
        'valores_anteriores',
        'valores_nuevos',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    public $timestamps = false;

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
