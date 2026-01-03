<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',
        'email_verified_at',
        'email_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function grupos()
{
    return $this->belongsToMany(Grupo::class, 'estudiantes_grupos', 'estudiante_id', 'grupo_id');
}

    public function revisiones()
    {
        return $this->hasMany(Revision::class, 'profesor_id');
    }

    public function gruposComoProfesor()
    {
        return $this->hasMany(Grupo::class, 'profesor_id');
    }

    // Scopes para roles
    public function scopeEstudiantes($query)
    {
        return $query->where('role', 'estudiante');
    }

    public function scopeProfesores($query)
    {
        return $query->where('role', 'profesor');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Helpers de roles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfesor()
    {
        return $this->role === 'profesor';
    }

    public function isEstudiante()
    {
        return $this->role === 'estudiante';
    }

    // Verificar permisos
    public function canAccessTesis($tesis)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isProfesor()) {
            return $tesis->grupo->profesor_id === $this->id;
        }

        if ($this->isEstudiante()) {
            return $this->grupos->contains('id', $tesis->grupo_id);
        }

        return false;
    }
}