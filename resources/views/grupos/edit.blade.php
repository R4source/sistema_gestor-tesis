@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Grupo: {{ $grupo->nombre_grupo }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('grupos.update', $grupo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre_grupo" class="form-label">Nombre del Grupo *</label>
                        <input type="text" class="form-control @error('nombre_grupo') is-invalid @enderror" 
                               id="nombre_grupo" name="nombre_grupo" 
                               value="{{ old('nombre_grupo', $grupo->nombre_grupo) }}" required>
                        @error('nombre_grupo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profesor_id" class="form-label">Profesor Asignado *</label>
                        <select class="form-select @error('profesor_id') is-invalid @enderror" 
                                id="profesor_id" name="profesor_id" required>
                            <option value="">Seleccionar profesor...</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->id }}" 
                                    {{ old('profesor_id', $grupo->profesor_id) == $profesor->id ? 'selected' : '' }}>
                                    {{ $profesor->name }} ({{ $profesor->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('profesor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estudiantes del Grupo *</label>
                        <div class="border rounded p-3 @error('estudiantes') border-danger @enderror" 
                             style="max-height: 200px; overflow-y: auto;">
                            @foreach($estudiantes as $estudiante)
                                @php
                                    // Verificar si el estudiante ya está en algún grupo
                                    $yaEnGrupo = $estudiante->grupos()->count() > 0;
                                    // En edición, permitir los que ya están en este grupo
                                    $enEsteGrupo = $grupo->estudiantes->contains($estudiante->id);
                                    $puedeSeleccionar = !$yaEnGrupo || $enEsteGrupo;
                                @endphp
                                
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="estudiantes[]" 
                                           value="{{ $estudiante->id }}" 
                                           id="estudiante_{{ $estudiante->id }}"
                                           {{ in_array($estudiante->id, old('estudiantes', $grupo->estudiantes->pluck('id')->toArray())) ? 'checked' : '' }}
                                           {{ !$puedeSeleccionar ? 'disabled' : '' }}>
                                    <label class="form-check-label {{ !$puedeSeleccionar ? 'text-muted' : '' }}" for="estudiante_{{ $estudiante->id }}">
                                        {{ $estudiante->name }} ({{ $estudiante->email }})
                                        @if($yaEnGrupo && !$enEsteGrupo)
                                            <small class="text-danger ms-2">
                                                <i class="fas fa-exclamation-triangle"></i> Ya está en otro grupo
                                            </small>
                                        @elseif($enEsteGrupo)
                                            <small class="text-success ms-2">
                                                <i class="fas fa-check-circle"></i> En este grupo
                                            </small>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('estudiantes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-info">
                            <i class="fas fa-info-circle"></i> Los estudiantes que ya pertenecen a otro grupo no pueden ser seleccionados.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('grupos.show', $grupo) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Grupo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection