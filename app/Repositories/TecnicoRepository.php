<?php
namespace App\Repositories;
use App\Models\Tecnico;

class TecnicoRepository extends BaseRepository
{
    public function __construct(Tecnico $model)
    {
        parent::__construct($model);
    }
}
