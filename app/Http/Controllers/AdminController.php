<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grupo;
use App\Models\Tesis;
use App\Models\Revision;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $data = [];

        if ($user->isAdmin()) {
            // Métricas básicas
            $data = [
                'totalUsuarios' => User::count(),
                'totalGrupos' => Grupo::count(),
                'totalTesis' => Tesis::count(),
                'totalRevisiones' => Revision::count(),
                
                // Estadísticas de tesis por estado
                'tesisPorEstado' => [
                    'enviado' => Tesis::where('estado', 'enviado')->count(),
                    'en revision' => Tesis::where('estado', 'en revisión')->count(),
                    'aprobado' => Tesis::where('estado', 'aprobado')->count(),
                    'rechazado' => Tesis::where('estado', 'rechazado')->count(),
                ],
                
                // Estadísticas de revisiones por estado
                'revisionesPorEstado' => [
                    'pendiente' => Revision::where('estado', 'pendiente')->count(),
                    'corregido' => Revision::where('estado', 'corregido')->count(),
                    'aprobado' => Revision::where('estado', 'aprobado')->count(),
                ],
                
                // Tesis por mes (últimos 6 meses)
                'tesisPorMes' => $this->getTesisPorMes(),
                
                // Usuarios por rol
                'usuariosPorRol' => [
                    'admin' => User::where('role', 'admin')->count(),
                    'profesor' => User::where('role', 'profesor')->count(),
                    'estudiante' => User::where('role', 'estudiante')->count(),
                ],
                
                // Grupos con más tesis (top 5)
                'gruposTop' => Grupo::withCount('tesis')
                    ->orderBy('tesis_count', 'desc')
                    ->take(5)
                    ->get(),
                
                // Profesores más activos (top 5)
                'profesoresTop' => User::where('role', 'profesor')
                    ->withCount('revisiones')
                    ->orderBy('revisiones_count', 'desc')
                    ->take(5)
                    ->get(),
                
                'usuariosRecientes' => User::latest()->take(5)->get(),
                'tesisRecientes' => Tesis::with('grupo')->latest()->take(5)->get(),
            ];
        } elseif ($user->isProfesor()) {
            $data = [
                'misGrupos' => $user->gruposComoProfesor()->withCount(['estudiantes', 'tesis'])->get(),
                'tesisPorRevisar' => Tesis::whereHas('grupo', function($query) use ($user) {
                    $query->where('profesor_id', $user->id);
                })->where('estado', 'enviado')->count(),
                
                'revisionesPendientes' => $user->revisiones()->where('estado', 'pendiente')->count(),
                'revisionesCompletadas' => $user->revisiones()->whereIn('estado', ['corregido', 'aprobado'])->count(),
                
                // Estadísticas de mis grupos
                'misTesisPorEstado' => [
                    'enviado' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->where('profesor_id', $user->id);
                    })->where('estado', 'enviado')->count(),
                    
                    'en revision' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->where('profesor_id', $user->id);
                    })->where('estado', 'en revisión')->count(),
                    
                    'aprobado' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->where('profesor_id', $user->id);
                    })->where('estado', 'aprobado')->count(),
                ],
                
                // Mis revisiones por mes
                'misRevisionesPorMes' => $this->getMisRevisionesPorMes($user),
            ];
        } elseif ($user->isEstudiante()) {
            $data = [
                'misGrupos' => $user->grupos,
                'misTesis' => Tesis::whereHas('grupo', function($query) use ($user) {
                    $query->whereHas('estudiantes', function($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
                })->get(),
                
                'tesisAprobadas' => Tesis::whereHas('grupo', function($query) use ($user) {
                    $query->whereHas('estudiantes', function($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
                })->where('estado', 'aprobado')->count(),
                
                'misTesisPorEstado' => [
                    'enviado' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->whereHas('estudiantes', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    })->where('estado', 'enviado')->count(),
                    
                    'en revision' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->whereHas('estudiantes', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    })->where('estado', 'en revisión')->count(),
                    
                    'aprobado' => Tesis::whereHas('grupo', function($query) use ($user) {
                        $query->whereHas('estudiantes', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    })->where('estado', 'aprobado')->count(),
                ],
            ];
        }

        return view('dashboard', compact('data', 'user'));
    }

    /**
     * Obtener estadísticas de tesis por mes (últimos 6 meses)
     */
    private function getTesisPorMes()
    {
        $months = [];
        $counts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $count = Tesis::whereYear('created_at', $month->year)
                         ->whereMonth('created_at', $month->month)
                         ->count();
            $counts[] = $count;
        }
        
        return [
            'months' => $months,
            'counts' => $counts
        ];
    }

    /**
     * Obtener revisiones del profesor por mes
     */
    private function getMisRevisionesPorMes($user)
    {
        $months = [];
        $counts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $count = Revision::where('profesor_id', $user->id)
                           ->whereYear('created_at', $month->year)
                           ->whereMonth('created_at', $month->month)
                           ->count();
            $counts[] = $count;
        }
        
        return [
            'months' => $months,
            'counts' => $counts
        ];
    }

    /**
     * Generar reporte PDF
     */
    public function generarReporte(Request $request)
    {
        $tipo = $request->get('tipo', 'general');
        
        // Aquí implementarías la generación de PDF
        // Por ahora retornamos una vista
        return view('reportes.' . $tipo);
    }
}