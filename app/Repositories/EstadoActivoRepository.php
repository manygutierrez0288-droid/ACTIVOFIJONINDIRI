<?php
namespace App\Repositories;
use App\Models\EstadoActivo;

class EstadoActivoRepository extends BaseRepository
{
    public function __construct(EstadoActivo $model)
    {
        parent::__construct($model);
    }
}
