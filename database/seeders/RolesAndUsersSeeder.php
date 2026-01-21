<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que los permisos existan primero
        $this->call(PermissionsSeeder::class);

        // Obtener todos los permisos
        $allPermissions = Permission::all();
        $reportesPermission = Permission::where('name', 'reportes')->first();

        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Acceso completo al sistema']
        );

        // Asignar todos los permisos al administrador
        if ($adminRole->permissions()->count() === 0) {
            $adminRole->permissions()->attach($allPermissions->pluck('id'));
        }

        $reporteriaRole = Role::firstOrCreate(
            ['name' => 'Reporteria'],
            ['description' => 'Acceso solo a reportes y dashboard']
        );

        // Asignar solo permiso de reportes
        if ($reporteriaRole->permissions()->count() === 0 && $reportesPermission) {
            $reporteriaRole->permissions()->attach($reportesPermission->id);
        }

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@siafnin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asignar rol de administrador si no lo tiene
        if (!$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole->id);
        }

        // Crear usuario de reportería
        $reporter = User::firstOrCreate(
            ['email' => 'reporteria@siafnin.com'],
            [
                'name' => 'Usuario Reportería',
                'password' => Hash::make('reporteria123'),
            ]
        );

        // Asignar rol de reportería si no lo tiene
        if (!$reporter->roles->contains($reporteriaRole->id)) {
            $reporter->roles()->attach($reporteriaRole->id);
        }

        // Crear usuario de prueba sin rol específico
        $testUser = User::firstOrCreate(
            ['email' => 'test@siafnin.com'],
            [
                'name' => 'Usuario de Prueba',
                'password' => Hash::make('test123'),
            ]
        );

        $this->command->info('Roles y usuarios de prueba creados exitosamente!');
        $this->command->info('');
        $this->command->info('Usuarios creados:');
        $this->command->info('1. Admin - admin@siafnin.com / admin123 (Rol: Administrador - Todos los permisos)');
        $this->command->info('2. Reportería - reporteria@siafnin.com / reporteria123 (Rol: Reporteria - Solo reportes)');
        $this->command->info('3. Test - test@siafnin.com / test123 (Sin rol asignado)');
    }
}
