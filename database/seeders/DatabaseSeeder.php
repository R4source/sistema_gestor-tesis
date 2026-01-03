<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// IMPORTAR LOS MODELOS
use App\Models\User;
use App\Models\Grupo;
use App\Models\Tesis;
use App\Models\Revision;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear profesores
        User::factory()->count(3)->create(['role' => 'profesor']);

        // Crear estudiantes
        User::factory()->count(10)->create(['role' => 'estudiante']);

        // Crear grupos
        Grupo::factory()->count(4)->create();

        // Crear tesis
        Tesis::factory()->count(4)->create();

        // Crear revisiones
        Revision::factory()->count(8)->create();
    
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
