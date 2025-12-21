<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalResponsable>
 */
class PersonalResponsableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'cargo_id' => \App\Models\Cargo::factory(),
            'numero_cedula' => $this->faker->unique()->numerify('#############'),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'activo' => true,
        ];
    }
}
