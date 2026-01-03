<?php
// app/Models/Grupo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_grupo',
        'profesor_id'
    ];

    // Relaciones
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(User::class, 'estudiantes_grupos', 'grupo_id', 'estudiante_id');
    }

    public function tesis()
    {
        return $this->hasMany(Tesis::class);
    }

    // Helper para contar estudiantes
    public function cantidadEstudiantes()
    {
        return $this->estudiantes()->count();
    }
}