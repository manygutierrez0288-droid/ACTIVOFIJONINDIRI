<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BajaActivo extends Model
{
    protected $fillable = ['activo_fijo_id', 'fecha', 'motivo', 'user_id'];
    protected $casts = ['fecha' => 'date'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
