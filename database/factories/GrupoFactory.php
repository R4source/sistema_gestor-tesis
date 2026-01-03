<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Grupo;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grupo>
 */
class GrupoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_grupo' => 'Grupo ' . $this->faker->unique()->numberBetween(1, 100),
        'profesor_id' => User::where('role', 'profesor')->inRandomOrder()->first()->id,
        ];
    }
}
