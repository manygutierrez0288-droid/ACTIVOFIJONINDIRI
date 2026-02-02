<?php

namespace App\Services;

use App\Repositories\ActivoFijoRepository;
use App\Models\Categoria;
use App\Models\ActivoFijo;
use Carbon\Carbon;

class ActivoFijoService extends BaseService
{
    protected $repository;

    public function __construct(ActivoFijoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters = [])
    {
        return $this->repository->getAllWithRelations($filters);
    }
    public function getById($id)
    {
        return $this->repository->getByIdWithRelations($id);
    }

    public function generateCodigoInventario($categoriaId): string
    {
        $year = Carbon::now()->format('y');
        $categoria = Categoria::find($categoriaId);
        $catCode = str_pad($categoria->codigo ?? '00', 2, '0', STR_PAD_LEFT);

        // Find last sequence for this year and category
        $lastActivo = ActivoFijo::where('categoria_id', $categoriaId)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastActivo && $lastActivo->codigo_inventario) {
            // Extract last 4 digits (handling potential suffix or different formats)
            $parts = explode('-', $lastActivo->codigo_inventario);
            $lastPart = end($parts);
            $lastSequence = is_numeric($lastPart) ? (int) $lastPart : 0;
            $sequence = $lastSequence + 1;
        }

        return $year . '-' . $catCode . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data)
    {
        if (empty($data['codigo_inventario'])) {
            $data['codigo_inventario'] = $this->generateCodigoInventario($data['categoria_id']);
        }

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
