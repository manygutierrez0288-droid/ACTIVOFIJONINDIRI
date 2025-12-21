<?php

namespace App\Repositories;

use App\Models\Departamento;

class DepartamentoRepository extends BaseRepository
{
    public function __construct(Departamento $model)
    {
        parent::__construct($model);
    }
}
