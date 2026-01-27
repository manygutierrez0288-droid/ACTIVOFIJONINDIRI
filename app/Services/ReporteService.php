<?php

namespace App\Services;

use App\Models\AuditoriaActivo;
use App\Models\ActivoFijo;
use App\Models\Mantenimiento;
use App\Models\Movimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReporteService
{
    public function getReportData(string $type, array $filters = [])
    {
        $query = ActivoFijo::with(['categoria', 'departamento', 'ubicacion', 'responsable', 'estado']);

        $this->applyFilters($query, $filters);

        switch ($type) {
            case 'depreciacion':
                return $query->get()->map(function ($activo) {
                    return [
                        'Codigo' => $activo->id, // Adjust if you have a specific code field
                        'Nombre' => $activo->nombre,
                        'Fecha Adquisicion' => $activo->fecha_adquisicion ? $activo->fecha_adquisicion->format('d/m/Y') : 'N/A',
                        'Valor Adquisicion' => number_format((float) $activo->valor_adquisicion, 2),
                        'Valor Residual' => number_format((float) $activo->valor_residual, 2),
                        'Vida Util' => $activo->vida_util_anios,
                        'Depreciacion Acumulada' => number_format((float) $activo->depreciacion_acumulada_calculada, 2),
                        'Valor Neto' => number_format((float) $activo->valor_neto_calculado, 2),
                    ];
                });

            case 'acciones':
                // Get IDs of filtered assets
                $assetIds = $query->pluck('id');

                $movimientos = Movimiento::whereIn('activo_fijo_id', $assetIds)
                    ->with(['activoFijo.responsable'])
                    ->get()
                    ->map(function ($mov) {
                        return [
                            'Fecha' => $mov->fecha ? $mov->fecha->format('Y-m-d') : $mov->created_at->format('Y-m-d'),
                            'Tipo' => 'MOVIMIENTO',
                            'Activo' => $mov->activoFijo->nombre ?? 'N/A',
                            'Detalle' => $mov->motivo ?? 'Movimientos de activo',
                            'Responsable' => $mov->activoFijo->responsable->nombre ?? 'N/A'
                        ];
                    });

                $mantenimientos = Mantenimiento::whereIn('activo_fijo_id', $assetIds)
                    ->with(['activoFijo.responsable'])
                    ->get()
                    ->map(function ($mant) {
                        return [
                            'Fecha' => $mant->fecha ? $mant->fecha->format('Y-m-d') : 'N/A',
                            'Tipo' => 'MANTENIMIENTO',
                            'Activo' => $mant->activoFijo->nombre ?? 'N/A',
                            'Detalle' => $mant->descripcion ?? 'Mantenimiento realizado',
                            'Responsable' => $mant->activoFijo->responsable->nombre ?? 'N/A'
                        ];
                    });

                return $movimientos->concat($mantenimientos)->sortByDesc('Fecha')->values();

            case 'auditoria_sistema':
                $queryAuditoria = AuditoriaActivo::with(['usuario', 'activoFijo']);

                if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
                    $queryAuditoria->whereBetween('fecha_hora', [$filters['fecha_inicio'] . ' 00:00:00', $filters['fecha_fin'] . ' 23:59:59']);
                }

                return $queryAuditoria->orderBy('fecha_hora', 'desc')->get()->map(function ($audit) {
                    return [
                        'Fecha' => $audit->fecha_hora->format('d/m/Y H:i'),
                        'Usuario' => $audit->usuario->name ?? 'Sistema',
                        'Accion' => match (strtolower($audit->accion)) {
                            'created' => 'CREADO',
                            'updated' => 'MODIFICADO',
                            'deleted' => 'ELIMINADO',
                            default => strtoupper($audit->accion)
                        },
                        'Activo' => $audit->activoFijo->nombre ?? 'N/A',
                        'ID Activo' => $audit->activo_fijo_id,
                        'Resumen' => $this->summarizeChanges($audit->valores_anteriores, $audit->valores_nuevos, $audit->accion)
                    ];
                });

            case 'categoria':
                return $query->get()->groupBy('categoria.nombre')->map(function ($items, $categoria) {
                    return [
                        'Categoria' => $categoria,
                        'Cantidad' => $items->count(),
                        'Total Valor' => number_format($items->sum('valor_adquisicion'), 2),
                    ];
                })->values();

            case 'libro_activos':
                return $query->get()->map(function ($activo) {
                    return [
                        'Codigo' => $activo->codigo_inventario,
                        'Nombre' => $activo->nombre,
                        'Categoria' => $activo->categoria->nombre ?? 'N/A',
                        'Marca/Modelo' => ($activo->marca->nombre ?? 'N/A') . ' / ' . ($activo->modelo->nombre ?? 'N/A'),
                        'Serie' => $activo->numero_serie,
                        'Fecha Adquisicion' => $activo->fecha_adquisicion ? $activo->fecha_adquisicion->format('d/m/Y') : 'N/A',
                        'Valor Original' => number_format((float) $activo->valor_adquisicion, 2),
                        'Deprec. Acumulada' => number_format((float) $activo->depreciacion_acumulada_calculada, 2),
                        'Valor Neto' => number_format((float) $activo->valor_neto_calculado, 2),
                        'Responsable' => $activo->responsable->nombre ?? 'N/A',
                    ];
                });

            case 'resumen_mensual':
                return $query->get()->groupBy('categoria.nombre')->map(function ($items, $categoria) {
                    return [
                        'Cuenta Contable' => $categoria,
                        'Cant.' => $items->count(),
                        'Valor Bruto' => number_format($items->sum('valor_adquisicion'), 2),
                        'Deprec. Mes' => number_format($items->sum('depreciacion_mensual'), 2),
                        'Deprec. Acum.' => number_format($items->sum('depreciacion_acumulada_calculada'), 2),
                        'Valor Neto' => number_format($items->sum('valor_neto_calculado'), 2),
                    ];
                })->values();

            case 'inventario':
            default:
                return $query->get()->map(function ($activo) {
                    return [
                        'ID' => $activo->id,
                        'Nombre' => $activo->nombre,
                        'Categoria' => $activo->categoria->nombre ?? 'N/A',
                        'Departamento' => $activo->departamento->nombre ?? 'N/A',
                        'Ubicacion' => $activo->ubicacion->nombre ?? 'N/A',
                        'Responsable' => $activo->responsable->nombre ?? 'N/A',
                        'Estado' => $activo->estado->nombre ?? 'N/A',
                        'Valor' => number_format((float) $activo->valor_adquisicion, 2),
                    ];
                });
            case 'vehiculos':
                return $query->has('vehiculo')->with('vehiculo')->get()->map(function ($activo) {
                    return [
                        'Codigo' => $activo->codigo_inventario,
                        'Placa' => $activo->vehiculo->placa ?? 'N/A',
                        'Marca' => $activo->marca->nombre ?? 'N/A',
                        'Modelo' => $activo->modelo->nombre ?? 'N/A',
                        'Chasis' => $activo->vehiculo->chasis ?? 'N/A',
                        'Motor' => $activo->vehiculo->numero_motor ?? 'N/A',
                        'Color' => $activo->color->nombre ?? 'N/A',
                        'Estado' => $activo->estado->nombre ?? 'N/A',
                        'Conductor' => $activo->responsable->nombre ?? 'N/A',
                    ];
                });

            case 'mantenimientos':
                $assetIds = $query->pluck('id');
                return Mantenimiento::whereIn('activo_fijo_id', $assetIds)
                    ->with(['activoFijo', 'tecnico', 'proveedor', 'estado'])
                    ->get()
                    ->map(function ($mant) {
                        return [
                            'Fecha' => $mant->fecha ? $mant->fecha->format('d/m/Y') : 'N/A',
                            'Activo' => $mant->activoFijo->nombre ?? 'N/A',
                            'Codigo' => $mant->activoFijo->codigo_inventario ?? 'N/A',
                            'Descripcion' => $mant->descripcion,
                            'Tecnico' => $mant->tecnico->nombre ?? 'N/A',
                            'Proveedor' => $mant->proveedor->nombre ?? 'N/A',
                            'Estado' => $mant->estado->nombre ?? 'N/A',
                            'Costo' => number_format((float) $mant->costo, 2),
                        ];
                    });

        }
    }

    protected function applyFilters(Builder $query, array $filters)
    {
        if (!empty($filters['categoria_id'])) {
            $query->where('categoria_id', $filters['categoria_id']);
        }
        if (!empty($filters['departamento_id'])) {
            $query->where('departamento_id', $filters['departamento_id']);
        }
        if (!empty($filters['ubicacion_id'])) {
            $query->where('ubicacion_id', $filters['ubicacion_id']);
        }
        if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
            $query->whereBetween('fecha_adquisicion', [$filters['fecha_inicio'], $filters['fecha_fin']]);
        }
    }

    private function summarizeChanges($anteriores, $nuevos, $accion = 'updated')
    {
        if (!$nuevos)
            return 'N/A';

        $anteriores = is_array($anteriores) ? $anteriores : (json_decode((string) $anteriores, true) ?: []);
        $nuevos = is_array($nuevos) ? $nuevos : (json_decode((string) $nuevos, true) ?: []);

        // Simplificar para creación
        if (strtolower($accion) === 'created') {
            return "REGISTRO INICIAL COMPLETO | CÓDIGO: [" . ($nuevos['codigo_inventario'] ?? 'N/A') . "]";
        }

        $cambios = [];
        $ignore = ['updated_at', 'created_at', 'id', 'user_id', 'user_creacion_id', 'user_modificacion_id', 'activo_fijo_id', 'valor_residual_automatico'];

        foreach ($nuevos as $key => $val) {
            if (in_array($key, $ignore))
                continue;

            $oldVal = $anteriores[$key] ?? 'NUEVO';
            if ($oldVal != $val) {
                // Traducir nombre de campo y quitar _id
                $label = strtoupper(str_replace('_id', '', $key));

                // Resolver IDs a nombres
                $resolvedOld = $this->resolveId($key, $oldVal);
                $resolvedNew = $this->resolveId($key, $val);

                $cambios[] = "$label: [$resolvedOld] -> [$resolvedNew]";
            }
        }

        return count($cambios) > 0 ? implode(" | ", $cambios) : 'Sin cambios detectables';
    }

    private function resolveId($campo, $valor)
    {
        if ($valor === 'NUEVO' || $valor === null || $valor === '')
            return $valor ?: 'NINGUNO';
        if (!str_ends_with($campo, '_id'))
            return $valor;

        try {
            switch ($campo) {
                case 'categoria_id':
                    return \App\Models\Categoria::find($valor)->nombre ?? $valor;
                case 'departamento_id':
                    return \App\Models\Departamento::find($valor)->nombre ?? $valor;
                case 'ubicacion_id':
                    return \App\Models\Ubicacion::find($valor)->nombre ?? $valor;
                case 'responsable_id':
                    return \App\Models\PersonalResponsable::find($valor)->nombre ?? $valor;
                case 'marca_id':
                    return \App\Models\Marca::find($valor)->nombre ?? $valor;
                case 'modelo_id':
                    return \App\Models\Modelo::find($valor)->nombre ?? $valor;
                case 'color_id':
                    return \App\Models\Color::find($valor)->nombre ?? $valor;
                case 'estado_id':
                    return \App\Models\EstadoActivo::find($valor)->nombre ?? $valor;
                case 'fuente_id':
                    return \App\Models\Fuente::find($valor)->nombre ?? $valor;
                case 'proveedor_id':
                    return \App\Models\Proveedor::find($valor)->nombre ?? $valor;
            }
        } catch (\Exception $e) {
            return $valor;
        }
        return $valor;
    }

    public function generateCsv($data)
    {
        if (count($data) === 0) {
            return '';
        }

        $headers = array_keys((array) $data[0]);
        $output = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 compatibility
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($output, $headers, ';');

        foreach ($data as $row) {
            fputcsv($output, (array) $row, ';');
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
