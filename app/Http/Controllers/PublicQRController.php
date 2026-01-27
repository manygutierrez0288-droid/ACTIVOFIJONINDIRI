<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Http\Resources\ActivoFijoResource;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PublicQRController
{
    /**
     * Permite visualizar la ficha técnica de un activo de forma pública.
     * Diseñado específicamente para escaneo de QR en la defensa.
     */
    public function show(string $id)
    {
        // Buscamos el activo de forma directa y simple
        $activo = ActivoFijo::with(['categoria', 'departamento', 'ubicacion', 'responsable', 'estado'])->find($id);

        if (!$activo) {
            abort(404, 'Activo no encontrado');
        }

        // Usamos una vista de Blade pura para evitar redireccionamientos de seguridad de Inertia
        return view('public.show', [
            'activo' => (new ActivoFijoResource($activo))->resolve(),
        ]);
    }
}
