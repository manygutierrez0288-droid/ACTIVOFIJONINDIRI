<?php

namespace App\Services;

use App\Repositories\ActivoFijoRepository;

class ActivoFijoService extends BaseService
{
    protected $repository;

    public function __construct(ActivoFijoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAllWithRelations();
    }
    public function getById($id)
    {
        return $this->repository->getByIdWithRelations($id);
    }
    public function create(array $data)
    {
        return $this->repository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }
    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
