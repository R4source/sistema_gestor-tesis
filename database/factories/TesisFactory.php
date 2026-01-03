<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tesis;
use App\Models\Grupo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tesis>
 */
class TesisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'grupo_id' => Grupo::inRandomOrder()->first()->id,
        'titulo' => $this->faker->sentence,
        'resumen' => $this->faker->paragraph,
        'archivo' => null,
        'estado' => 'enviado',
        ];
    }
}
