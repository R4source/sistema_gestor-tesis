<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Si no hay usuario autenticado, redirigir al login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene alguno de los roles requeridos
        foreach ($roles as $role) {
            if ($request->user()->role === $role) {
                return $next($request);
            }
        }

        // Si no tiene permisos, mostrar error 403
        abort(403, 'No tienes permisos para acceder a esta página.');
    }
}