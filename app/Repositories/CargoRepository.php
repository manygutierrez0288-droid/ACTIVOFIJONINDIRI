<?php
namespace App\Repositories;
use App\Models\Cargo;

class CargoRepository extends BaseRepository
{
    public function __construct(Cargo $model)
    {
        parent::__construct($model);
    }
}
