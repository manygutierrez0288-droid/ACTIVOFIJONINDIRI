<?php

namespace App\Repositories;

use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Collection;

class VehiculoRepository
{
    public function getAll(): Collection
    {
        return Vehiculo::with(['activoFijo.marca', 'activoFijo.modelo', 'activoFijo.estado'])->get();
    }

    public function getById(string $id): ?Vehiculo
    {
        return Vehiculo::with([
            'activoFijo.categoria',
            'activoFijo.departamento',
            'activoFijo.ubicacion',
            'activoFijo.marca',
            'activoFijo.modelo',
            'activoFijo.color',
            'activoFijo.estado',
            'activoFijo.responsable'
        ])->findOrFail($id);
    }

    public function create(array $data): Vehiculo
    {
        return Vehiculo::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $vehiculo = $this->getById($id);
        return $vehiculo->update($data);
    }

    public function delete(string $id): bool
    {
        $vehiculo = $this->getById($id);
        return $vehiculo->delete();
    }
}
