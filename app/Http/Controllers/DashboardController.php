<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\Categoria;
use App\Models\EstadoActivo;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total de Activos Fijos Registrados
        $totalActivos = ActivoFijo::count();

        // 2. Valor Total de los Activos (suma de valores de adquisición)
        $activos = ActivoFijo::all();
        $valorTotal = $activos->sum('valor_adquisicion');

        // 3. Valor Total de la Depreciación Acumulada (Calculada dinámicamente)
        $depreciacionTotal = $activos->sum('depreciacion_acumulada_calculada');

        // 4. Valor Neto de los Activos
        $valorNeto = $valorTotal - $depreciacionTotal;

        // 5. Número de Activos por Estado (Buen Estado / Regular / Mal Estado)
        $activosPorEstado = ActivoFijo::select('estado_id', DB::raw('count(*) as total'))
            ->groupBy('estado_id')
            ->with('estado')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $item->estado ? $item->estado->nombre : 'Sin Estado',
                    'total' => $item->total
                ];
            });

        // 6. Número de Activos por Categoría
        $activosPorCategoria = ActivoFijo::select('categoria_id', DB::raw('count(*) as total'))
            ->groupBy('categoria_id')
            ->with('categoria')
            ->get()
            ->map(function ($item) {
                return [
                    'categoria' => $item->categoria ? $item->categoria->nombre : 'Sin Categoría',
                    'total' => $item->total
                ];
            });

        // 7. Evolución mensual del valor de adquisición (Últimos 12 meses CALENDARIO)
        $mesesData = ActivoFijo::select(
            DB::raw("DATE_FORMAT(fecha_adquisicion, '%Y-%m') as mes_raw"),
            DB::raw('SUM(valor_adquisicion) as total')
        )
            ->where('fecha_adquisicion', '>=', \Carbon\Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('mes_raw')
            ->get()
            ->pluck('total', 'mes_raw');

        $evolucionMensual = collect();
        for ($i = 11; $i >= 0; $i--) {
            $fecha = \Carbon\Carbon::now()->subMonths($i);
            $mesKey = $fecha->format('Y-m');

            $evolucionMensual->push([
                'mes' => ucfirst($fecha->translatedFormat('M Y')),
                'total' => $mesesData->get($mesKey, 0)
            ]);
        }

        // 8. Tabla Reciente: Últimos 10 activos registrados
        $activosRecientes = ActivoFijo::with(['categoria', 'estado'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($activo) {
                return [
                    'id' => $activo->id,
                    'nombre' => $activo->nombre,
                    'valor_adquisicion' => $activo->valor_adquisicion,
                    'fecha_adquisicion' => $activo->fecha_adquisicion ? \Carbon\Carbon::parse($activo->fecha_adquisicion)->format('d/m/Y') : 'N/A',
                    'depreciacion_acumulada' => $activo->depreciacion_acumulada_calculada,
                    'valor_neto' => $activo->valor_neto_calculado,
                    'estado' => $activo->estado ? $activo->estado->nombre : 'N/A',
                    'categoria' => $activo->categoria ? $activo->categoria->nombre : 'N/A',
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_activos' => $totalActivos,
                'valor_total' => $valorTotal,
                'depreciacion_total' => $depreciacionTotal,
                'valor_neto' => $valorNeto,
            ],
            'activos_por_estado' => $activosPorEstado,
            'activos_por_categoria' => $activosPorCategoria,
            'evolucion_mensual' => $evolucionMensual,
            'activos_recientes' => $activosRecientes,
        ]);
    }
}
