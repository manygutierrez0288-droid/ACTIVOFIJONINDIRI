<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalResponsable extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'cargo_id', 'numero_cedula', 'telefono', 'email', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}
