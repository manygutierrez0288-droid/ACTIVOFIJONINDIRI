<?php

namespace App\Services;

use App\Repositories\VehiculoRepository;
use App\Models\Vehiculo;
use App\Models\ActivoFijo;
use Illuminate\Database\Eloquent\Collection;

class VehiculoService
{
    protected VehiculoRepository $repository;
    protected ActivoFijoService $activoFijoService;

    public function __construct(VehiculoRepository $repository, ActivoFijoService $activoFijoService)
    {
        $this->repository = $repository;
        $this->activoFijoService = $activoFijoService;
    }

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getById(string $id): ?Vehiculo
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): Vehiculo
    {
        // First create the ActivoFijo
        $activoFijo = ActivoFijo::create([
            'nombre' => $data['nombre'],
            'categoria_id' => $data['categoria_id'],
            'departamento_id' => $data['departamento_id'],
            'ubicacion_id' => $data['ubicacion_id'],
            'marca_id' => $data['marca_id'],
            'modelo_id' => $data['modelo_id'],
            'color_id' => $data['color_id'],
            'fuente_id' => $data['fuente_id'],
            'proveedor_id' => $data['proveedor_id'],
            'responsable_id' => $data['responsable_id'],
            'estado_id' => $data['estado_id'],
            'fecha_adquisicion' => $data['fecha_adquisicion'],
            'valor_adquisicion' => $data['valor_adquisicion'],
            'vida_util_anios' => $data['vida_util_anios'],
            'valor_residual' => $data['valor_residual'] ?? 0,
            'codigo_inventario' => $data['codigo_inventario'] ?? $this->activoFijoService->generateCodigoInventario($data['categoria_id']),
            'numero_serie' => $data['numero_serie'] ?? null,
            'imagen_url' => $data['imagen_url'] ?? null,
        ]);

        // Then create the Vehiculo
        return $this->repository->create([
            'activo_fijo_id' => $activoFijo->id,
            'placa' => $data['placa'],
            'numero_circulacion' => $data['numero_circulacion'] ?? null,
            'anio' => $data['anio'],
            'kilometraje' => $data['kilometraje'] ?? 0,
            'tipo_combustible' => $data['tipo_combustible'] ?? null,
            'capacidad_pasajeros' => $data['capacidad_pasajeros'] ?? null,
        ]);
    }

    public function update(string $id, array $data): bool
    {
        $vehiculo = $this->getById($id);

        // Update ActivoFijo
        $activoData = [
            'nombre' => $data['nombre'],
            'categoria_id' => $data['categoria_id'],
            'departamento_id' => $data['departamento_id'],
            'ubicacion_id' => $data['ubicacion_id'],
            'marca_id' => $data['marca_id'],
            'modelo_id' => $data['modelo_id'],
            'color_id' => $data['color_id'],
            'fuente_id' => $data['fuente_id'],
            'proveedor_id' => $data['proveedor_id'],
            'responsable_id' => $data['responsable_id'],
            'estado_id' => $data['estado_id'],
            'fecha_adquisicion' => $data['fecha_adquisicion'],
            'valor_adquisicion' => $data['valor_adquisicion'],
            'vida_util_anios' => $data['vida_util_anios'],
            'valor_residual' => $data['valor_residual'] ?? 0,
            'codigo_inventario' => $data['codigo_inventario'] ?? null,
            'numero_serie' => $data['numero_serie'] ?? null,
        ];

        if (array_key_exists('imagen_url', $data)) {
            $activoData['imagen_url'] = $data['imagen_url'];
        }

        $vehiculo->activoFijo->update($activoData);

        // Update Vehiculo
        return $this->repository->update($id, [
            'placa' => $data['placa'],
            'numero_circulacion' => $data['numero_circulacion'] ?? null,
            'anio' => $data['anio'],
            'kilometraje' => $data['kilometraje'] ?? 0,
            'tipo_combustible' => $data['tipo_combustible'] ?? null,
            'capacidad_pasajeros' => $data['capacidad_pasajeros'] ?? null,
        ]);
    }

    public function delete(string $id): bool
    {
        $vehiculo = $this->getById($id);
        $activoFijo = $vehiculo->activoFijo;

        // Delete vehiculo first
        $this->repository->delete($id);

        // Then delete the activo fijo
        return $activoFijo->delete();
    }
}
