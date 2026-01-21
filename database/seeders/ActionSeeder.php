<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivoFijo;
use App\Models\Movimiento;
use App\Models\Mantenimiento;
use App\Models\Ubicacion;
use App\Models\PersonalResponsable;
use App\Models\User;
use App\Models\Tecnico;
use App\Models\Proveedor;
use App\Models\EstadoActivo;

class ActionSeeder extends Seeder
{
    public function run(): void
    {
        $activos = ActivoFijo::all();
        $ubicaciones = Ubicacion::all();
        $responsables = PersonalResponsable::all();
        $user = User::first();
        $tecnico = Tecnico::first();
        $proveedor = Proveedor::first();
        $estado = EstadoActivo::first();

        if ($activos->isEmpty()) {
            $this->command->error("No hay activos registrados. Por favor registra activos primero.");
            return;
        }

        foreach ($activos->take(5) as $activo) {
            // Seed Movimientos
            if ($ubicaciones->count() >= 2 && $responsables->count() >= 2) {
                Movimiento::create([
                    'activo_fijo_id' => $activo->id,
                    'ubicacion_origen_id' => $ubicaciones[0]->id,
                    'ubicacion_destino_id' => $ubicaciones[1]->id,
                    'responsable_origen_id' => $responsables[0]->id,
                    'responsable_destino_id' => $responsables[1]->id,
                    'fecha' => now()->subDays(rand(1, 30)),
                    'motivo' => 'Traslado de oficina por remodelación',
                    'user_id' => $user?->id,
                ]);
            }

            // Seed Mantenimientos
            Mantenimiento::create([
                'activo_fijo_id' => $activo->id,
                'fecha' => now()->subDays(rand(1, 60)),
                'descripcion' => 'Mantenimiento preventivo general y limpieza',
                'costo' => rand(100, 500),
                'tecnico_id' => $tecnico?->id,
                'proveedor_id' => $proveedor?->id,
                'estado_id' => $estado?->id,
                'user_id' => $user?->id,
            ]);
        }

        $this->command->info("Se han generado movimientos y mantenimientos de prueba.");
    }
}
