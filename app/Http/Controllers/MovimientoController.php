<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\Movimiento;
use App\Models\Ubicacion;
use App\Models\PersonalResponsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function create(ActivoFijo $activo)
    {
        return Inertia::render('Operaciones/Movimientos/Create', [
            'activo' => $activo->load(['ubicacion', 'responsable']),
            'ubicaciones' => Ubicacion::all(),
            'responsables' => PersonalResponsable::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activo_fijo_id' => 'required|exists:activo_fijos,id',
            'ubicacion_destino_id' => 'required|exists:ubicacions,id',
            'responsable_destino_id' => 'required|exists:personal_responsables,id',
            'fecha' => 'required|date',
            'motivo' => 'required|string',
        ]);

        $activo = ActivoFijo::findOrFail($request->activo_fijo_id);

        Movimiento::create([
            'activo_fijo_id' => $activo->id,
            'ubicacion_origen_id' => $activo->ubicacion_id,
            'ubicacion_destino_id' => $request->ubicacion_destino_id,
            'responsable_origen_id' => $activo->responsable_id,
            'responsable_destino_id' => $request->responsable_destino_id,
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
            'user_id' => auth()->id(),
            'estado' => 'pendiente',
        ]);

        return redirect()->route('activos.show', $activo->id)->with('success', 'Solicitud de traslado registrada. Pendiente de autorización.');
    }

    public function pending()
    {
        $movimientos = Movimiento::with(['activoFijo', 'ubicacionOrigen', 'ubicacionDestino', 'responsableOrigen', 'responsableDestino', 'user'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Operaciones/Movimientos/Pending', [
            'movimientos' => $movimientos
        ]);
    }

    public function autorizar(Movimiento $movimiento)
    {
        if ($movimiento->estado !== 'pendiente') {
            return back()->with('error', 'Este movimiento ya ha sido procesado.');
        }

        DB::transaction(function () use ($movimiento) {
            $movimiento->update([
                'estado' => 'autorizado',
                'user_autorizador_id' => auth()->id(),
                'fecha_autorizacion' => now(),
            ]);

            $movimiento->activoFijo->update([
                'ubicacion_id' => $movimiento->ubicacion_destino_id,
                'responsable_id' => $movimiento->responsable_destino_id,
            ]);
        });

        return back()->with('success', 'Movimiento autorizado correctamente. El activo ha sido trasladado.');
    }

    public function rechazar(Request $request, Movimiento $movimiento)
    {
        if ($movimiento->estado !== 'pendiente') {
            return back()->with('error', 'Este movimiento ya ha sido procesado.');
        }

        $movimiento->update([
            'estado' => 'rechazado',
            'user_autorizador_id' => auth()->id(),
            'fecha_autorizacion' => now(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        return back()->with('success', 'Movimiento rechazado.');
    }

    public function actaTraslado(Movimiento $movimiento)
    {
        $movimiento->load([
            'activoFijo.categoria',
            'ubicacionOrigen',
            'ubicacionDestino',
            'responsableOrigen.cargo',
            'responsableDestino.cargo'
        ]);
        return Inertia::render('Operaciones/Movimientos/ActaTraslado', [
            'movimiento' => $movimiento,
            'fecha_emision' => now()->format('d/m/Y'),
        ]);
    }
}
