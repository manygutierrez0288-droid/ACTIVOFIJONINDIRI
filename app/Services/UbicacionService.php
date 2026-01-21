<?php

namespace App\Services;

use App\Repositories\UbicacionRepository;

class UbicacionService extends BaseService
{
    protected $repository;

    public function __construct(UbicacionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
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
        $ubicacion = $this->repository->getById($id);

        if ($ubicacion) {
            if (
                $ubicacion->activos()->exists() ||
                $ubicacion->movimientosOrigen()->exists() ||
                $ubicacion->movimientosDestino()->exists()
            ) {
                throw new \Exception("No se puede eliminar esta ubicación porque tiene activos o movimientos asociados.");
            }
        }

        return $this->repository->delete($id);
    }
}
