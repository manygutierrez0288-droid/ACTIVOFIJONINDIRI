<?php

namespace App\Repositories;

use App\Models\Categoria;

class CategoriaRepository extends BaseRepository
{
    public function __construct(Categoria $model)
    {
        parent::__construct($model);
    }
}
