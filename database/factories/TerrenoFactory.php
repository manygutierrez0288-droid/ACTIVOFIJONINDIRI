<?php

namespace Database\Factories;

use App\Models\Terreno;
use App\Models\ActivoFijo;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerrenoFactory extends Factory
{
    protected $model = Terreno::class;

    public function definition(): array
    {
        $area = $this->faker->randomFloat(2, 50, 2000);
        $valorCatastral = $area * $this->faker->randomFloat(2, 500, 2000);

        return [
            'activo_fijo_id' => ActivoFijo::factory()->state(function (array $attributes) {
                // For terrains, the category should ideally be 'Terrenos' or similar
                $categoria = Categoria::where('nombre', 'like', '%terreno%')->first();
                return [
                    'categoria_id' => $categoria ? $categoria->id : Categoria::inRandomOrder()->first()->id,
                    'vida_util_anios' => 0, // Terrains don't depreciate
                    'valor_residual' => $attributes['valor_adquisicion'] ?? 0,
                    'valor_residual_automatico' => false,
                ];
            }),
            'dominio' => $this->faker->randomElement(['Alcaldía Municipal', 'Gobierno Central']),
            'numero_escritura' => $this->faker->unique()->bothify('###-####-??'),
            'area_metros_cuadrados' => $area,
            'frente_metros' => $this->faker->randomFloat(2, 10, 50),
            'fondo_metros' => $this->faker->randomFloat(2, 20, 100),
            'lindero_norte' => $this->faker->sentence(),
            'lindero_sur' => $this->faker->sentence(),
            'lindero_este' => $this->faker->sentence(),
            'lindero_oeste' => $this->faker->sentence(),
            'uso_suelo' => $this->faker->randomElement(['Habitacional', 'Comercial', 'Equipamiento Municipal']),
            'zonificacion' => $this->faker->bothify('ZONA-??'),
            'valor_catastral' => $valorCatastral,
            'coordenadas_gps' => $this->faker->latitude() . ', ' . $this->faker->longitude(),
        ];
    }
}
