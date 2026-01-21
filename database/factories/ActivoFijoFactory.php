<?php

namespace Database\Factories;

use App\Models\ActivoFijo;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Ubicacion;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Color;
use App\Models\Fuente;
use App\Models\Proveedor;
use App\Models\PersonalResponsable;
use App\Models\EstadoActivo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivoFijoFactory extends Factory
{
    protected $model = ActivoFijo::class;

    public function definition(): array
    {
        $valorAdquisicion = $this->faker->randomFloat(2, 1000, 50000);
        $vidaUtil = $this->faker->randomElement([5, 10]);
        $valorResidual = $valorAdquisicion * 0.10;

        return [
            'nombre' => $this->faker->words(3, true),
            'categoria_id' => Categoria::inRandomOrder()->first()?->id ?? Categoria::factory(),
            'departamento_id' => Departamento::inRandomOrder()->first()?->id ?? Departamento::factory(),
            'ubicacion_id' => Ubicacion::inRandomOrder()->first()?->id ?? Ubicacion::factory(),
            'marca_id' => Marca::inRandomOrder()->first()?->id ?? Marca::factory(),
            'modelo_id' => Modelo::inRandomOrder()->first()?->id ?? Modelo::factory(),
            'color_id' => Color::inRandomOrder()->first()?->id ?? Color::factory(),
            'fuente_id' => Fuente::inRandomOrder()->first()?->id ?? Fuente::factory(),
            'proveedor_id' => Proveedor::inRandomOrder()->first()?->id ?? Proveedor::factory(),
            'responsable_id' => PersonalResponsable::inRandomOrder()->first()?->id ?? PersonalResponsable::factory(),
            'estado_id' => EstadoActivo::inRandomOrder()->first()?->id ?? EstadoActivo::factory(),
            'fecha_adquisicion' => $this->faker->date(),
            'valor_adquisicion' => $valorAdquisicion,
            'vida_util_anios' => $vidaUtil,
            'valor_residual' => $valorResidual,
            'depreciacion_acumulada' => 0,
            'user_creacion_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'user_modificacion_id' => null,
            'codigo_inventario' => 'ACT-' . $this->faker->unique()->numerify('#####'),
            'imagen_url' => null, // Or a placeholder URL if needed
            'valor_residual_automatico' => true,
            'numero_serie' => $this->faker->bothify('SN-####-????'),
        ];
    }
}
