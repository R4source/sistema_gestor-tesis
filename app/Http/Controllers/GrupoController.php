<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with(['profesor', 'estudiantes', 'tesis'])
                      ->latest()
                      ->get();
        
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $profesores = User::where('role', 'profesor')->get();
        // SOLO estudiantes que NO están en ningún grupo
        $estudiantes = User::where('role', 'estudiante')
                          ->whereDoesntHave('grupos')
                          ->get();
        
        return view('grupos.create', compact('profesores', 'estudiantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255|unique:grupos',
            'profesor_id' => 'required|exists:users,id',
            'estudiantes' => 'required|array|min:1',
            'estudiantes.*' => 'exists:users,id'
        ]);

        // VERIFICAR QUE LOS ESTUDIANTES NO PERTENEZCAN A OTRO GRUPO
        $estudiantesEnOtrosGrupos = [];
        foreach ($request->estudiantes as $estudianteId) {
            $estudiante = User::find($estudianteId);
            if ($estudiante && $estudiante->grupos()->count() > 0) {
                $estudiantesEnOtrosGrupos[] = $estudiante->name;
            }
        }

        if (!empty($estudiantesEnOtrosGrupos)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Los siguientes estudiantes ya pertenecen a otro grupo: ' . implode(', ', $estudiantesEnOtrosGrupos));
        }

        DB::transaction(function () use ($request) {
            $grupo = Grupo::create([
                'nombre_grupo' => $request->nombre_grupo,
                'profesor_id' => $request->profesor_id
            ]);

            $grupo->estudiantes()->attach($request->estudiantes);
        });

        return redirect()->route('grupos.index')
                        ->with('success', 'Grupo creado exitosamente.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load(['profesor', 'estudiantes', 'tesis.revisiones.profesor']);
        
        return view('grupos.show', compact('grupo'));
    }

    public function edit(Grupo $grupo)
    {
        $profesores = User::where('role', 'profesor')->get();
        // Estudiantes que NO están en ningún grupo + los que YA están en este grupo
        $estudiantes = User::where('role', 'estudiante')
                          ->where(function($query) use ($grupo) {
                              $query->whereDoesntHave('grupos')
                                    ->orWhereHas('grupos', function($q) use ($grupo) {
                                        $q->where('grupos.id', $grupo->id);
                                    });
                          })
                          ->get();
        
        $grupo->load('estudiantes');
        
        return view('grupos.edit', compact('grupo', 'profesores', 'estudiantes'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255|unique:grupos,nombre_grupo,' . $grupo->id,
            'profesor_id' => 'required|exists:users,id',
            'estudiantes' => 'required|array|min:1',
            'estudiantes.*' => 'exists:users,id'
        ]);

        // VERIFICAR QUE LOS NUEVOS ESTUDIANTES NO PERTENEZCAN A OTRO GRUPO
        $estudiantesEnOtrosGrupos = [];
        foreach ($request->estudiantes as $estudianteId) {
            $estudiante = User::find($estudianteId);
            if ($estudiante) {
                // Excluir al estudiante si ya está en este grupo
                $gruposDelEstudiante = $estudiante->grupos()->where('grupos.id', '!=', $grupo->id)->count();
                if ($gruposDelEstudiante > 0) {
                    $estudiantesEnOtrosGrupos[] = $estudiante->name;
                }
            }
        }

        if (!empty($estudiantesEnOtrosGrupos)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Los siguientes estudiantes ya pertenecen a otro grupo: ' . implode(', ', $estudiantesEnOtrosGrupos));
        }

        DB::transaction(function () use ($request, $grupo) {
            $grupo->update([
                'nombre_grupo' => $request->nombre_grupo,
                'profesor_id' => $request->profesor_id
            ]);

            $grupo->estudiantes()->sync($request->estudiantes);
        });

        return redirect()->route('grupos.index')
                        ->with('success', 'Grupo actualizado exitosamente.');
    }

    public function destroy(Grupo $grupo)
    {
        DB::transaction(function () use ($grupo) {
            $grupo->estudiantes()->detach();
            $grupo->delete();
        });

        return redirect()->route('grupos.index')
                        ->with('success', 'Grupo eliminado exitosamente.');
    }
}