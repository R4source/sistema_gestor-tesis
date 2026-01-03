<?php

namespace App\Policies;

use App\Models\Tesis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TesisPolicy
{
    /**
     * Determinar si el usuario puede ver cualquier tesis
     */
    public function viewAny(User $user): bool
    {
        // Admin y profesores pueden ver todas las tesis
        return $user->isAdmin() || $user->isProfesor();
    }

    /**
     * Determinar si el usuario puede ver una tesis específica
     */
    public function view(User $user, Tesis $tesis): bool
    {
        // Admin puede ver todo
        if ($user->isAdmin()) {
            return true;
        }

        // Profesor puede ver tesis de sus grupos
        if ($user->isProfesor()) {
            return $tesis->grupo->profesor_id === $user->id;
        }

        // Estudiante solo puede ver tesis de sus grupos
        if ($user->isEstudiante()) {
            return $user->grupos->contains($tesis->grupo_id);
        }

        return false;
    }

    /**
     * Determinar si el usuario puede crear tesis
     */
    public function create(User $user): bool
    {
        // Solo estudiantes pueden crear tesis
        return $user->isEstudiante();
    }

    /**
     * Determinar si el usuario puede actualizar una tesis
     */
    public function update(User $user, Tesis $tesis): bool
    {
        // Admin puede actualizar cualquier tesis
        if ($user->isAdmin()) {
            return true;
        }
        
        // Profesor puede actualizar tesis de sus grupos
        if ($user->isProfesor()) {
            return $tesis->grupo->profesor_id === $user->id;
        }

        // Estudiante solo puede actualizar tesis de sus grupos
        if ($user->isEstudiante()) {
            return $user->grupos->contains($tesis->grupo_id);
        }

        return false;
    }

    /**
     * Determinar si el usuario puede eliminar una tesis
     */
    public function delete(User $user, Tesis $tesis): bool
    {
        // Solo admin puede eliminar tesis
        return $user->isAdmin();
    }
}