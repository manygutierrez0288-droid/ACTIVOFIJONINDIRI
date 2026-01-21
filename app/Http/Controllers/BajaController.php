<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\BajaActivo;
use App\Models\EstadoActivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BajaController extends Controller
{
    public function create(ActivoFijo $activo)
    {
        return Inertia::render('Operaciones/Bajas/Create', [
            'activo' => $activo,
            'estados' => EstadoActivo::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activo_fijo_id' => 'required|exists:activo_fijos,id',
            'fecha' => 'required|date',
            'motivo' => 'required|string',
            'documento_respaldo' => 'nullable|string',
        ]);

        $baja = BajaActivo::create([
            'activo_fijo_id' => $request->activo_fijo_id,
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
            'documento_respaldo' => $request->documento_respaldo,
            'user_id' => auth()->id(),
        ]);

        // Automatically update asset status to "Baja" (ID 6)
        $activo = ActivoFijo::find($request->activo_fijo_id);
        if ($activo) {
            $activo->update(['estado_id' => 6]);
        }

        return redirect()->route('activos.index')->with('success', 'Activo dado de baja correctamente.');
    }

    public function actaBaja(BajaActivo $baja)
    {
        $baja->load(['activoFijo.categoria', 'activoFijo.ubicacion', 'activoFijo.responsable.cargo']);
        return Inertia::render('Operaciones/Bajas/ActaBaja', [
            'baja' => $baja,
            'fecha_emision' => now()->format('d/m/Y'),
        ]);
    }
}
