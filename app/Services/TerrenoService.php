<?php

namespace App\Services;

use App\Models\Terreno;
use App\Models\ActivoFijo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TerrenoService
{
    protected $activoFijoService;

    public function __construct(ActivoFijoService $activoFijoService)
    {
        $this->activoFijoService = $activoFijoService;
    }

    public function getAll()
    {
        return Terreno::with([
            'activoFijo.categoria',
            'activoFijo.departamento',
            'activoFijo.ubicacion',
            'activoFijo.estado',
            'activoFijo.responsable'
        ])->get();
    }

    public function getById($id)
    {
        return Terreno::with([
            'activoFijo.categoria',
            'activoFijo.departamento',
            'activoFijo.ubicacion',
            'activoFijo.marca',
            'activoFijo.modelo',
            'activoFijo.color',
            'activoFijo.fuente',
            'activoFijo.proveedor',
            'activoFijo.responsable',
            'activoFijo.estado',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Crear el activo fijo
            $activoFijo = ActivoFijo::create([
                'nombre' => $data['nombre'],
                'categoria_id' => $data['categoria_id'],
                'departamento_id' => $data['departamento_id'],
                'ubicacion_id' => $data['ubicacion_id'],
                'marca_id' => $data['marca_id'] ?? null,
                'modelo_id' => $data['modelo_id'] ?? null,
                'color_id' => $data['color_id'] ?? null,
                'fuente_id' => $data['fuente_id'] ?? null,
                'proveedor_id' => $data['proveedor_id'] ?? null,
                'responsable_id' => $data['responsable_id'] ?? null,
                'estado_id' => $data['estado_id'],
                'fecha_adquisicion' => $data['fecha_adquisicion'],
                'valor_adquisicion' => $data['valor_adquisicion'],
                'vida_util_anios' => $data['vida_util_anios'] ?? 0, // Terrenos no deprecian por defecto
                'valor_residual' => $data['valor_residual'] ?? $data['valor_adquisicion'],
                'codigo_inventario' => $data['codigo_inventario'] ?? $this->activoFijoService->generateCodigoInventario($data['categoria_id']),
                'numero_serie' => $data['numero_serie'] ?? null,
                'imagen_url' => $data['imagen_url'] ?? null,
                'user_creacion_id' => Auth::id(),
            ]);

            // Crear el terreno
            $terreno = Terreno::create([
                'activo_fijo_id' => $activoFijo->id,
                'numero_escritura' => $data['numero_escritura'],
                'area_metros_cuadrados' => $data['area_metros_cuadrados'],
                'frente_metros' => $data['frente_metros'] ?? null,
                'fondo_metros' => $data['fondo_metros'] ?? null,
                'lindero_norte' => $data['lindero_norte'] ?? null,
                'lindero_sur' => $data['lindero_sur'] ?? null,
                'lindero_este' => $data['lindero_este'] ?? null,
                'lindero_oeste' => $data['lindero_oeste'] ?? null,
                'coordenadas_gps' => $data['coordenadas_gps'] ?? null,
                'uso_suelo' => $data['uso_suelo'] ?? null,
                'zonificacion' => $data['zonificacion'] ?? null,
                'valor_catastral' => $data['valor_catastral'] ?? null,
            ]);

            return $terreno->load('activoFijo');
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $terreno = Terreno::findOrFail($id);
            $activoFijo = $terreno->activoFijo;

            // Actualizar el activo fijo
            $activoFijo->update([
                'nombre' => $data['nombre'],
                'categoria_id' => $data['categoria_id'],
                'departamento_id' => $data['departamento_id'],
                'ubicacion_id' => $data['ubicacion_id'],
                'marca_id' => $data['marca_id'] ?? null,
                'modelo_id' => $data['modelo_id'] ?? null,
                'color_id' => $data['color_id'] ?? null,
                'fuente_id' => $data['fuente_id'] ?? null,
                'proveedor_id' => $data['proveedor_id'] ?? null,
                'responsable_id' => $data['responsable_id'] ?? null,
                'estado_id' => $data['estado_id'],
                'fecha_adquisicion' => $data['fecha_adquisicion'],
                'valor_adquisicion' => $data['valor_adquisicion'],
                'vida_util_anios' => $data['vida_util_anios'] ?? 0,
                'valor_residual' => $data['valor_residual'] ?? $data['valor_adquisicion'],
                'codigo_inventario' => $data['codigo_inventario'] ?? null,
                'numero_serie' => $data['numero_serie'] ?? null,
                'imagen_url' => $data['imagen_url'] ?? $activoFijo->imagen_url,
                'user_modificacion_id' => Auth::id(),
            ]);

            // Actualizar el terreno
            $terreno->update([
                'numero_escritura' => $data['numero_escritura'],
                'area_metros_cuadrados' => $data['area_metros_cuadrados'],
                'frente_metros' => $data['frente_metros'] ?? null,
                'fondo_metros' => $data['fondo_metros'] ?? null,
                'lindero_norte' => $data['lindero_norte'] ?? null,
                'lindero_sur' => $data['lindero_sur'] ?? null,
                'lindero_este' => $data['lindero_este'] ?? null,
                'lindero_oeste' => $data['lindero_oeste'] ?? null,
                'coordenadas_gps' => $data['coordenadas_gps'] ?? null,
                'uso_suelo' => $data['uso_suelo'] ?? null,
                'zonificacion' => $data['zonificacion'] ?? null,
                'valor_catastral' => $data['valor_catastral'] ?? null,
            ]);

            return $terreno->load('activoFijo');
        });
    }

    public function delete($id)
    {
        $terreno = Terreno::findOrFail($id);
        // Al eliminar el activo fijo, el terreno se eliminará en cascada
        return $terreno->activoFijo->delete();
    }
}
