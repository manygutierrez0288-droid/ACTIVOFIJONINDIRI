<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory;
    protected $fillable = ['placa', 'numero_motor', 'chasis', 'numero_circulacion', 'anio', 'activo_fijo_id', 'kilometraje', 'capacidad_pasajeros', 'tipo_combustible'];

    public function activoFijo(): BelongsTo
    {
        return $this->belongsTo(ActivoFijo::class);
    }
}
