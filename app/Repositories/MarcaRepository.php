<?php
namespace App\Repositories;
use App\Models\Marca;

class MarcaRepository extends BaseRepository
{
    public function __construct(Marca $model)
    {
        parent::__construct($model);
    }
}
