<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'numero_ruc', 'telefono', 'email', 'direccion', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
