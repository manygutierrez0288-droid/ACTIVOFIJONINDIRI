<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'activo',
        'porcentaje_valor_residual',
        'vida_util_anios',
        'grupo_categoria',
        'subcategoria',
        'clase'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'porcentaje_valor_residual' => 'decimal:2',
        'vida_util_anios' => 'integer',
    ];
}
