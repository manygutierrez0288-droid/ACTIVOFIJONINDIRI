<?php

namespace App\Repositories;

use App\Models\ActivoFijo;

class ActivoFijoRepository extends BaseRepository
{
    public function __construct(ActivoFijo $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations()
    {
        return $this->model->with(['categoria', 'departamento', 'ubicacion'])->get();
    }

    public function getByIdWithRelations($id)
    {
        return $this->model->with(['categoria', 'departamento', 'ubicacion'])->find($id);
    }
}
