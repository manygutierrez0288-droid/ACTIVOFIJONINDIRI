<?php

namespace App\Repositories;

use App\Models\Ubicacion;

class UbicacionRepository extends BaseRepository
{
    public function __construct(Ubicacion $model)
    {
        parent::__construct($model);
    }
}
