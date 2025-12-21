<?php
namespace App\Repositories;
use App\Models\Fuente;

class FuenteRepository extends BaseRepository
{
    public function __construct(Fuente $model)
    {
        parent::__construct($model);
    }
}
