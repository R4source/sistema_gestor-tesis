<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\Tesis;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TesisRevisadaNotification;

class RevisionController extends Controller
{
    public function index()
    {
        $revisiones = Revision::with(['tesis.grupo', 'profesor'])
                             ->latest()
                             ->get();
        
        return view('revisiones.index', compact('revisiones'));
    }

    public function create()
    {
        $tesis = Tesis::where('estado', '!=', 'aprobado')->get();
        $profesores = User::where('role', 'profesor')->get();
        
        return view('revisiones.create', compact('tesis', 'profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tesis_id' => 'required|exists:tesis,id',
            'profesor_id' => 'required|exists:users,id',
            'comentario' => 'required|string',
            'estado' => 'required|in:pendiente,corregido,aprobado,rechazado'
        ]);

        $revision = Revision::create($request->all());

        $tesis = Tesis::find($request->tesis_id);

        if ($request->estado == 'aprobado') {
            $tesis->update(['estado' => 'aprobado']);
        } elseif ($request->estado == 'rechazado') {
            $tesis->update(['estado' => 'rechazado']);
        } elseif ($request->estado == 'corregido') {
            $tesis->update(['estado' => 'en revisión']);
        }

        foreach ($tesis->grupo->estudiantes as $estudiante) {
            $estudiante->notify(new TesisRevisadaNotification($tesis, $revision));
        }

        foreach ($tesis->grupo->estudiantes as $estudiante) {
            \Log::info('Enviando a: ' . $estudiante->email);
            $estudiante->notify(new TesisRevisadaNotification($tesis, $revision));
            }

        return redirect()->route('revisiones.index')
                        ->with('success', 'Revisión creada exitosamente.');
    }

    /* ----------  cambio de nombre: $revision por $revisione  ---------- */
    public function show(Revision $revisione)
    {
        $revisione->load(['tesis.grupo.estudiantes', 'profesor']);
        return view('revisiones.show', compact('revisione'));
    }

    public function edit(Revision $revisione)
    {
        $tesis = Tesis::all();
        $profesores = User::where('role', 'profesor')->get();
        return view('revisiones.edit', compact('revisione', 'tesis', 'profesores'));
    }

    public function update(Request $request, Revision $revisione)
    {
        $request->validate([
            'comentario' => 'required|string',
            'estado' => 'required|in:pendiente,corregido,aprobado,rechazado'
        ]);

        $revisione->update($request->only(['comentario', 'estado']));

        if ($request->estado == 'aprobado') {
            $revisione->tesis->update(['estado' => 'aprobado']);
        } elseif ($request->estado == 'rechazado') {
            $revisione->tesis->update(['estado' => 'rechazado']);
        }

        return redirect()->route('revisiones.index')
                        ->with('success', 'Revisión actualizada exitosamente.');
    }

    public function destroy(Revision $revisione)
    {
        $revisione->delete();
        return redirect()->route('revisiones.index')
                        ->with('success', 'Revisión eliminada exitosamente.');
    }
}