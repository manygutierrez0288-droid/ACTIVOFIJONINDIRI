<?php

namespace Database\Factories;

use App\Models\Movimiento;
use App\Models\ActivoFijo;
use App\Models\Ubicacion;
use App\Models\PersonalResponsable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimientoFactory extends Factory
{
    protected $model = Movimiento::class;

    public function definition(): array
    {
        return [
            'activo_fijo_id' => ActivoFijo::factory(),
            'ubicacion_origen_id' => Ubicacion::factory(),
            'ubicacion_destino_id' => Ubicacion::factory(),
            'responsable_origen_id' => PersonalResponsable::factory(),
            'responsable_destino_id' => PersonalResponsable::factory(),
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'motivo' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['pendiente', 'autorizado', 'rechazado']),
            'user_id' => User::factory(),
        ];
    }
}
