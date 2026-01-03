<?php

namespace App\Http\Controllers;

use App\Models\Tesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\TesisEnviadaNotification;
use App\Traits\LoggableTesis;

class TesisController extends Controller
{
    use AuthorizesRequests;

    /* ----------  INDEX  ---------- */
    public function index()
    {
        $user   = auth()->user();
        $q      = request('q');
        $estado = request('estado');

       // Consulta base
       $query = Tesis::with(['grupo.estudiantes', 'grupo.profesor', 'revisiones']);

       // Filtros de búsqueda (solo si hay algo)
      if ($q) {
        $query->where(function ($qry) use ($q) {
            $qry->where('titulo', 'like', "%$q%")
                ->orWhereHas('grupo.estudiantes', fn($sq) => $sq->where('name', 'like', "%$q%"))
                ->orWhereHas('grupo', fn($sq) => $sq->where('nombre_grupo', 'like', "%$q%"));
        });
    }
    if ($estado) {
        $query->where('estado', $estado);
    }

    // Permisos por rol (igual que antes)
    if ($user->isAdmin()) {
        // Admin ve todo (con filtros ya aplicados)
    } elseif ($user->isProfesor()) {
        $query->whereHas('grupo', fn($q) => $q->where('profesor_id', $user->id));
    } else {
        $query->whereHas('grupo.estudiantes', fn($q) => $q->where('users.id', $user->id));
    }

    $tesis = $query->latest()->get();

    return view('tesis.index', compact('tesis'));
   }

    /* ----------  CREATE  ---------- */
    public function create()
    {
        // Policy: solo estudiantes pueden crear
        $this->authorize('create', Tesis::class);

        $grupos = auth()->user()->grupos;

        if ($grupos->isEmpty()) {
            return redirect()->route('tesis.index')
                           ->with('error', 'No perteneces a ningún grupo. Contacta al administrador.');
        }

        return view('tesis.create', compact('grupos'));
    }

    /* ----------  STORE  ---------- */
    public function store(Request $request)
    {
        $this->authorize('create', Tesis::class); // mismo gate que create

        $request->validate([
            'titulo'    => 'required|string|max:255',
            'resumen'   => 'required|string|min:10',
            'archivo'   => 'required|file|mimes:pdf,doc,docx|max:10240',
            'grupo_id'  => 'required|exists:grupos,id'
        ]);

        $grupo = auth()->user()->grupos()->find($request->grupo_id);
        if (!$grupo) {
            abort(403, 'No perteneces a este grupo');
        }

        $archivoPath = $request->file('archivo')->store('tesis', 'public');

        $tesis = Tesis::create([
        'titulo'   => $request->titulo,
        'resumen'  => $request->resumen,
        'archivo'  => $archivoPath,
        'grupo_id' => $request->grupo_id,
        'estado'   => 'enviado'
        ]);

        // 2. Ahora sí existe $tesis → notificar
        $tesis->profesor->notify(new TesisEnviadaNotification($tesis));

        return redirect()->route('tesis.index')
                       ->with('success', 'Tesis creada exitosamente');
    }

    /* ----------  SHOW  ---------- */
    public function show(Tesis $tesis)
    {
        $this->authorize('view', $tesis); // Policy centralizado

        $tesis->load(['grupo.estudiantes', 'grupo.profesor', 'revisiones.profesor']);

        return view('tesis.show', compact('tesis'));
    }

    /* ----------  EDIT  ---------- */
    public function edit(Tesis $tesis)
    {
        $this->authorize('update', $tesis); // Policy: admin/profesor(estudiante del grupo)

        $grupos = auth()->user()->grupos;
        return view('tesis.edit', compact('tesis', 'grupos'));
    }

    /* ----------  UPDATE  ---------- */
    public function update(Request $request, Tesis $tesis)
    {
        $this->authorize('update', $tesis);

        $request->validate([
            'titulo'   => 'required|string|max:255',
            'resumen'  => 'required|string|min:10',
            'archivo'  => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'grupo_id' => 'required|exists:grupos,id'
        ]);

        $grupo = auth()->user()->grupos()->find($request->grupo_id);
        if (!$grupo) {
            abort(403, 'No perteneces a este grupo');
        }

        $data = $request->only(['titulo', 'resumen', 'grupo_id']);

        if ($request->hasFile('archivo')) {
            if ($tesis->archivo) {
                Storage::disk('public')->delete($tesis->archivo);
            }
            $data['archivo'] = $request->file('archivo')->store('tesis', 'public');
        }
        
        // Guardar valores antes de cambiar
        $antes = $tesis->only(['titulo', 'resumen', 'estado']);

        // Actualizar
        $tesis->update($request->only(['titulo', 'resumen', 'grupo_id', 'estado']));

        // Comparar y logar
        foreach ($antes as $campo => $valorAnterior) {
          $valorNuevo = $tesis->getAttribute($campo);
          if ($valorAnterior != $valorNuevo) {
           $tesis->logChange($campo, $valorAnterior, $valorNuevo);
            }
         }

        $tesis->update($data);

        return redirect()->route('tesis.index')
                       ->with('success', 'Tesis actualizada exitosamente');
    }

    /* ----------  DESTROY  ---------- */
    public function destroy(Tesis $tesis)
    {
        $this->authorize('delete', $tesis); // solo Admin según tu Policy

        if ($tesis->archivo) {
            Storage::disk('public')->delete($tesis->archivo);
        }

        $tesis->delete();

        return redirect()->route('tesis.index')
                       ->with('success', 'Tesis eliminada exitosamente');
    }

    /* ----------  DESCARGAR  ---------- */
    public function descargar(Tesis $tesis)
    {
        $this->authorize('view', $tesis); // mismo gate que show

        if (!$tesis->archivo || !Storage::disk('public')->exists($tesis->archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')
                     ->download($tesis->archivo,
                                $tesis->titulo . '.' . pathinfo($tesis->archivo, PATHINFO_EXTENSION));
    }
}