<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\Mantenimiento;
use App\Models\Tecnico;
use App\Models\Proveedor;
use App\Models\EstadoActivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MantenimientoController extends Controller
{
    public function create(ActivoFijo $activo)
    {
        return Inertia::render('Operaciones/Mantenimientos/Create', [
            'activo' => $activo,
            'tecnicos' => Tecnico::all(),
            'proveedores' => Proveedor::all(),
            'estados' => EstadoActivo::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activo_fijo_id' => 'required|exists:activo_fijos,id',
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
            'costo' => 'required|numeric|min:0',
            'tecnico_id' => 'nullable|exists:tecnicos,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'estado_id' => 'required|exists:estado_activos,id',
        ]);

        Mantenimiento::create([
            'activo_fijo_id' => $request->activo_fijo_id,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'costo' => $request->costo,
            'tecnico_id' => $request->tecnico_id,
            'proveedor_id' => $request->proveedor_id,
            'estado_id' => $request->estado_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('activos.show', $request->activo_fijo_id)->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function print(Mantenimiento $mantenimiento)
    {
        $mantenimiento->load(['activoFijo.categoria', 'tecnico', 'proveedor', 'estado']);
        return Inertia::render('Operaciones/Mantenimientos/Ficha', [
            'mantenimiento' => $mantenimiento,
        ]);
    }
}
