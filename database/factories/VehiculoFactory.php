<?php

namespace Database\Factories;

use App\Models\Vehiculo;
use App\Models\ActivoFijo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    public function definition(): array
    {
        return [
            'placa' => strtoupper($this->faker->bothify('??-####')),
            'numero_motor' => strtoupper($this->faker->bothify('M-#######')),
            'chasis' => strtoupper($this->faker->bothify('CH-#######')),
            'numero_circulacion' => strtoupper($this->faker->bothify('CIRC-####')),
            'anio' => $this->faker->year(),
            'activo_fijo_id' => ActivoFijo::factory(),
            'kilometraje' => $this->faker->numberBetween(0, 100000),
            'capacidad_pasajeros' => $this->faker->numberBetween(2, 50),
            'tipo_combustible' => $this->faker->randomElement(['Gasolina', 'Diesel', 'Eléctrico', 'Híbrido']),
        ];
    }
}
