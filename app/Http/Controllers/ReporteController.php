<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grupo;
use App\Models\Tesis;
use App\Models\Revision;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReporteController extends Controller
{
    /**
     * Generar reporte general del sistema
     */
    public function reporteGeneral()
    {
        $data = $this->obtenerDatosReporte();
        $pdf = Pdf::loadView('reportes.general', $data);
        
        return $pdf->download('reporte-general-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de tesis
     */
    public function reporteTesis(Request $request)
    {
        $filtroEstado = $request->get('estado', 'todos');
        
        $query = Tesis::with(['grupo', 'revisiones.profesor']);
        
        if ($filtroEstado !== 'todos') {
            $query->where('estado', $filtroEstado);
        }
        
        $tesis = $query->get();
        
        $data = [
            'tesis' => $tesis,
            'filtroEstado' => $filtroEstado,
            'fechaGeneracion' => Carbon::now(),
            'totalTesis' => $tesis->count(),
            'tesisPorEstado' => [
                'enviado' => Tesis::where('estado', 'enviado')->count(),
                'en revision' => Tesis::where('estado', 'en revisión')->count(),
                'aprobado' => Tesis::where('estado', 'aprobado')->count(),
                'rechazado' => Tesis::where('estado', 'rechazado')->count(),
            ]
        ];
        
        $pdf = Pdf::loadView('reportes.tesis', $data);
        
        return $pdf->download('reporte-tesis-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de usuarios
     */
    public function reporteUsuarios()
    {
        $usuarios = User::with(['grupos', 'revisiones', 'gruposComoProfesor'])->get();
        
        $data = [
            'usuarios' => $usuarios,
            'fechaGeneracion' => Carbon::now(),
            'estadisticas' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'profesores' => User::where('role', 'profesor')->count(),
                'estudiantes' => User::where('role', 'estudiante')->count(),
            ]
        ];
        
        $pdf = Pdf::loadView('reportes.usuarios', $data);
        
        return $pdf->download('reporte-usuarios-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Obtener datos para reporte general
     */
    private function obtenerDatosReporte()
    {
        return [
            'fechaGeneracion' => Carbon::now(),
            'estadisticas' => [
                'usuarios' => [
                    'total' => User::count(),
                    'admins' => User::where('role', 'admin')->count(),
                    'profesores' => User::where('role', 'profesor')->count(),
                    'estudiantes' => User::where('role', 'estudiante')->count(),
                ],
                'grupos' => [
                    'total' => Grupo::count(),
                    'conTesis' => Grupo::has('tesis')->count(),
                ],
                'tesis' => [
                    'total' => Tesis::count(),
                    'enviado' => Tesis::where('estado', 'enviado')->count(),
                    'en revision' => Tesis::where('estado', 'en revisión')->count(),
                    'aprobado' => Tesis::where('estado', 'aprobado')->count(),
                    'rechazado' => Tesis::where('estado', 'rechazado')->count(),
                ],
                'revisiones' => [
                    'total' => Revision::count(),
                    'pendiente' => Revision::where('estado', 'pendiente')->count(),
                    'corregido' => Revision::where('estado', 'corregido')->count(),
                    'aprobado' => Revision::where('estado', 'aprobado')->count(),
                ],
            ],
            'tesisRecientes' => Tesis::with('grupo')->latest()->take(10)->get(),
            'usuariosRecientes' => User::latest()->take(10)->get(),
            'gruposTop' => Grupo::withCount('tesis')->orderBy('tesis_count', 'desc')->take(5)->get(),
        ];
    }
}