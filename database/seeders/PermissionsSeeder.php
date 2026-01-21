<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'catalogos',
                'description' => 'Gestión de catálogos (departamentos, ubicaciones, categorías, proveedores, marcas, modelos, estados, colores, fuentes, responsables, cargos, técnicos)'
            ],
            [
                'name' => 'activos',
                'description' => 'Gestión de activos fijos, movimientos, mantenimientos y bajas'
            ],
            [
                'name' => 'vehiculos',
                'description' => 'Gestión de vehículos'
            ],
            [
                'name' => 'terrenos',
                'description' => 'Gestión de terrenos'
            ],
            [
                'name' => 'reportes',
                'description' => 'Acceso a reportes del sistema'
            ],
            [
                'name' => 'usuarios',
                'description' => 'Gestión de usuarios y roles'
            ],
            [
                'name' => 'inventarios',
                'description' => 'Gestión de inventarios físicos'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        $this->command->info('Permisos creados exitosamente!');
    }
}
