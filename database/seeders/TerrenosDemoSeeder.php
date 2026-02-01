<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Terreno;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TerrenosDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando creación de 5 registros de Terrenos...');

        // Authenticate a user to satisfy AuditoriaObserver
        $user = User::first() ?? User::factory()->create();
        Auth::login($user);

        $terrenos = Terreno::factory()->count(5)->create();

        foreach ($terrenos as $terreno) {
            $activo = $terreno->activoFijo;
            $this->command->info("Terreno creado: {$activo->nombre} (Escritura: {$terreno->numero_escritura}) - Dominio: {$terreno->dominio}");
        }

        $this->command->info('¡5 Terrenos creados exitosamente!');
    }
}
