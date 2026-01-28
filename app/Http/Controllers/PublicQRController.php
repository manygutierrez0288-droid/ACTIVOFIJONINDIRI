<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Http\Resources\ActivoFijoResource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicQRController extends Controller
{
    /**
     * Permite visualizar la ficha técnica de un activo de forma pública.
     * Diseñado específicamente para escaneo de QR en la defensa.
     */
    public function show(string $id)
    {
        Log::info('Acceso público QR intentado para ID: ' . $id);

        // Buscamos el activo de forma directa y simple
        $activo = ActivoFijo::with(['categoria', 'departamento', 'ubicacion', 'responsable', 'estado'])->find($id);

        if (!$activo) {
            Log::warning('Activo no encontrado en consulta pública QR ID: ' . $id);
            abort(404, 'Activo no encontrado');
        }

        Log::info('Activo encontrado: ' . $activo->nombre . ' (ID: ' . $activo->id . ')');

        // Usamos una vista de Blade pura para evitar redireccionamientos de seguridad de Inertia
        return view('public.show', [
            'activo' => (new ActivoFijoResource($activo))->resolve(),
        ]);
    }
}
