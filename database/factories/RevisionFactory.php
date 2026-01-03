<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Revision;
use App\Models\Tesis;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revision>
 */
class RevisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'tesis_id' => Tesis::inRandomOrder()->first()->id,
        'profesor_id' => User::where('role', 'profesor')->inRandomOrder()->first()->id,
        'comentario' => $this->faker->paragraph,
        'estado' => 'pendiente',
        ];
    }
}
