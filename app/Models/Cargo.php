<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    public function personalResponsables()
    {
        return $this->hasMany(PersonalResponsable::class);
    }
}
