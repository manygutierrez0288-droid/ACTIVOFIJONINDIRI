<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaActivo;
use App\Models\ActivoFijo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditoriaController extends Controller
{
    /**
     * Display audit history for a specific asset
     */
    public function show(string $activoId)
    {
        $activo = ActivoFijo::findOrFail($activoId);

        $auditorias = AuditoriaActivo::where('activo_fijo_id', $activoId)
            ->with('usuario')
            ->orderBy('fecha_hora', 'desc')
            ->paginate(20);

        // Transformar auditorías para que los IDs sean nombres legibles
        $auditorias->getCollection()->transform(function ($auditoria) {
            $auditoria->valores_anteriores = $this->resolveIdsToNames($auditoria->valores_anteriores);
            $auditoria->valores_nuevos = $this->resolveIdsToNames($auditoria->valores_nuevos);
            return $auditoria;
        });

        return Inertia::render('Activos/AuditHistory', [
            'activo' => $activo,
            'auditorias' => $auditorias
        ]);
    }

    /**
     * Get audit history for display in asset detail page
     */
    public function getHistory(string $activoId)
    {
        $auditorias = AuditoriaActivo::where('activo_fijo_id', $activoId)
            ->with('usuario')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($auditoria) {
                return [
                    'id' => $auditoria->id,
                    'accion' => $auditoria->accion,
                    'usuario' => $auditoria->usuario ? $auditoria->usuario->name : 'Sistema',
                    'fecha_hora' => $auditoria->fecha_hora->format('d/m/Y H:i:s'),
                    'valores_anteriores' => $this->resolveIdsToNames($auditoria->valores_anteriores),
                    'valores_nuevos' => $this->resolveIdsToNames($auditoria->valores_nuevos),
                ];
            });

        return response()->json($auditorias);
    }

    /**
     * Resuelve IDs técnicos a nombres legibles y filtra campos del sistema
     */
    private function resolveIdsToNames($valores)
    {
        if (!$valores)
            return null;

        // Si es un string (por datos viejos), intentar decodificarlo
        if (is_string($valores)) {
            $valores = json_decode($valores, true);
        }

        if (!is_array($valores))
            return $valores;

        $camposAExcluir = ['id', 'updated_at', 'created_at', 'user_creacion_id', 'user_modificacion_id', 'activo_fijo_id'];
        $resultado = [];

        foreach ($valores as $campo => $valor) {
            if (in_array($campo, $camposAExcluir))
                continue;

            // Resolución de IDs a Nombres
            if ($valor !== null) {
                switch ($campo) {
                    case 'ubicacion_id':
                        $rel = \App\Models\Ubicacion::find($valor);
                        $campo = 'ubicacion_id'; // Mantenemos el nombre del campo para el label del frontend
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'responsable_id':
                        $rel = \App\Models\PersonalResponsable::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'categoria_id':
                        $rel = \App\Models\Categoria::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'departamento_id':
                        $rel = \App\Models\Departamento::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'marca_id':
                        $rel = \App\Models\Marca::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'modelo_id':
                        $rel = \App\Models\Modelo::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'color_id':
                        $rel = \App\Models\Color::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'proveedor_id':
                        $rel = \App\Models\Proveedor::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'fuente_id':
                        $rel = \App\Models\Fuente::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                    case 'estado_id':
                        $rel = \App\Models\EstadoActivo::find($valor);
                        $valor = $rel ? $rel->nombre : $valor;
                        break;
                }
            }

            $resultado[$campo] = $valor;
        }

        return $resultado;
    }
}
