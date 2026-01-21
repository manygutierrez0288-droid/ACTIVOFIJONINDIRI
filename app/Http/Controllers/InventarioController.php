<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\Inventario;
use App\Models\InventarioDetalle;
use App\Models\EstadoActivo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index()
    {
        return Inertia::render('Operaciones/Inventarios/Index', [
            'inventarios' => Inventario::with('user')->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Operaciones/Inventarios/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $inventario = DB::transaction(function () use ($request) {
            $inv = Inventario::create([
                'nombre' => $request->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'user_id' => auth()->id(),
                'estado' => 'abierto',
                'observaciones' => $request->observaciones,
            ]);

            // Snapshots all current assets into this inventory session
            $activos = ActivoFijo::all();
            foreach ($activos as $activo) {
                InventarioDetalle::create([
                    'inventario_id' => $inv->id,
                    'activo_fijo_id' => $activo->id,
                    'verificado' => false,
                ]);
            }

            return $inv;
        });

        return redirect()->route('inventarios.show', $inventario->id)->with('success', 'Sesión de inventario iniciada correctamente.');
    }

    public function show(Inventario $inventario)
    {
        return Inertia::render('Operaciones/Inventarios/Show', [
            'inventario' => $inventario->load(['user', 'detalles.activoFijo.ubicacion', 'detalles.estadoFisico', 'detalles.verificadoPor']),
            'estados' => EstadoActivo::all(),
        ]);
    }

    public function verificar(Request $request, Inventario $inventario)
    {
        $request->validate([
            'activo_fijo_id' => 'required|exists:activo_fijos,id',
            'estado_fisico_id' => 'required|exists:estado_activos,id',
            'comentarios' => 'nullable|string',
        ]);

        $detalle = InventarioDetalle::where('inventario_id', $inventario->id)
            ->where('activo_fijo_id', $request->activo_fijo_id)
            ->firstOrFail();

        $detalle->update([
            'verificado' => true,
            'fecha_verificacion' => now(),
            'estado_fisico_id' => $request->estado_fisico_id,
            'comentarios' => $request->comentarios,
            'verificado_por' => auth()->id(),
        ]);

        return back()->with('success', 'Activo verificado correctamente.');
    }

    public function cerrar(Inventario $inventario)
    {
        $inventario->update([
            'estado' => 'cerrado',
            'fecha_fin' => now(),
        ]);

        return back()->with('success', 'Sesión de inventario finalizada.');
    }
}
