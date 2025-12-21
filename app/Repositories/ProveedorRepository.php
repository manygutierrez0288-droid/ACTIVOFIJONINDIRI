<?php
namespace App\Repositories;
use App\Models\Proveedor;

class ProveedorRepository extends BaseRepository
{
    public function __construct(Proveedor $model)
    {
        parent::__construct($model);
    }
}
