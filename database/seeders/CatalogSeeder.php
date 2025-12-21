<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Categoria::factory(10)->create();
        \App\Models\Fuente::factory(5)->create();
        \App\Models\Proveedor::factory(10)->create();
        \App\Models\Cargo::factory(10)->create();
        \App\Models\PersonalResponsable::factory(15)->create();
        \App\Models\Marca::factory(10)->create();
        \App\Models\Modelo::factory(20)->create();
        \App\Models\EstadoActivo::factory(6)->create();
        \App\Models\Color::factory(10)->create();
        \App\Models\Tecnico::factory(5)->create();
        \App\Models\Departamento::factory(8)->create();
        \App\Models\Ubicacion::factory(12)->create();
    }
}
