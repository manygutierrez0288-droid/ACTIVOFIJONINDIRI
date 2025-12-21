<?php
namespace App\Repositories;
use App\Models\Modelo;

class ModeloRepository extends BaseRepository
{
    public function __construct(Modelo $model)
    {
        parent::__construct($model);
    }

    public function getAllWithMarca()
    {
        return $this->model->with('marca')->get();
    }
    public function getByMarca($marcaId)
    {
        return $this->model->where('marca_id', $marcaId)->get();
    }
}
