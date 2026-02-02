<?php

namespace App\Repositories;

use App\Models\ActivoFijo;

class ActivoFijoRepository extends BaseRepository
{
    public function __construct(ActivoFijo $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations($filters = [])
    {
        $query = $this->model->with([
            'categoria',
            'departamento',
            'ubicacion',
            'marca',
            'modelo',
            'color',
            'fuente',
            'proveedor',
            'responsable',
            'estado',
            'bajas'
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('codigo_inventario', 'like', "%{$search}%")
                    ->orWhere('numero_serie', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['categoria'])) {
            $query->where('categoria_id', $filters['categoria']);
        }

        if (!empty($filters['departamento'])) {
            $query->where('departamento_id', $filters['departamento']);
        }

        if (!empty($filters['ubicacion'])) {
            $query->where('ubicacion_id', $filters['ubicacion']);
        }

        if (!empty($filters['estado'])) {
            $query->where('estado_id', $filters['estado']);
        }

        return $query->get();
    }

    public function getByIdWithRelations($id)
    {
        return $this->model->with([
            'categoria',
            'departamento',
            'ubicacion',
            'marca',
            'modelo',
            'color',
            'fuente',
            'proveedor',
            'responsable',
            'estado',
            'bajas'
        ])->find($id);
    }
}
