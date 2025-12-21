<?php
namespace App\Services;
use App\Repositories\PersonalResponsableRepository;

class PersonalResponsableService extends BaseService
{
    protected $repository;
    public function __construct(PersonalResponsableRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getAll()
    {
        return $this->repository->getAllWithCargo();
    }
    public function getById($id)
    {
        return $this->repository->getById($id);
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
