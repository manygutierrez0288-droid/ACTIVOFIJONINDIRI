<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehiculo;
use App\Models\ActivoFijo;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DemoContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando creación de 5 registros de Vehículos con información completa...');

        // Authenticate a user to satisfy AuditoriaObserver
        $user = User::first() ?? User::factory()->create();
        Auth::login($user);

        // Create 5 Vehicles. 
        // The VehiculoFactory creates a parent ActivoFijo automatically.
        // The ActivoFijoFactory creates all its dependencies (Brand, Model, Category, etc) automatically.
        $vehiculos = Vehiculo::factory()->count(5)->create();

        foreach ($vehiculos as $vehiculo) {
            $activo = $vehiculo->activoFijo;
            $this->command->info("Vehículo creado: {$activo->nombre} (Placa: {$vehiculo->placa}) - Código Inventario: {$activo->codigo_inventario}");
        }

        $this->command->info('¡5 Registros creados exitosamente!');
    }
}
