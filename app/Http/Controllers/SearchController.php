<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Search in ActivoFijo
        $activos = ActivoFijo::query()
            ->with(['categoria', 'departamento', 'ubicacion', 'vehiculo'])
            ->where('nombre', 'like', "%{$query}%")
            ->orWhere('codigo_inventario', 'like', "%{$query}%")
            ->orWhere('numero_serie', 'like', "%{$query}%")
            ->orWhereHas('vehiculo', function ($q) use ($query) {
                $q->where('placa', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        // Map results to a standard format
        $results = $activos->map(function ($activo) {
            $isVehicle = $activo->vehiculo !== null;
            $type = $isVehicle ? 'Vehículo' : 'Activo Fijo';

            // Determine URL
            $url = $isVehicle
                ? route('vehiculos.show', $activo->vehiculo->id)
                : route('activos.show', $activo->id);

            // Determine Icon (sent as string to be handled by frontend map)
            $icon = $isVehicle ? 'Truck' : 'Box';

            $subtitle = $activo->codigo_inventario;

            if ($isVehicle) {
                $subtitle .= ' • Placa: ' . $activo->vehiculo->placa;
            } elseif ($activo->ubicacion) {
                $subtitle .= ' • ' . $activo->ubicacion->nombre;
            }

            return [
                'id' => $activo->id,
                'title' => $activo->nombre,
                'subtitle' => $subtitle,
                'type' => $type,
                'url' => $url,
                'icon' => $icon
            ];
        });

        return response()->json($results);
    }
}
