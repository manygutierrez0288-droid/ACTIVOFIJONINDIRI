<?php
namespace App\Repositories;
use App\Models\PersonalResponsable;

class PersonalResponsableRepository extends BaseRepository
{
    public function __construct(PersonalResponsable $model)
    {
        parent::__construct($model);
    }

    public function getAllWithCargo()
    {
        return $this->model->with('cargo')->get();
    }
}
