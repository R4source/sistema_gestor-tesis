<!-- resources/views/revisiones/create.blade.php -->
@extends('layouts.app')

@section('title', 'Crear Revisión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Crear Nueva Revisión</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('revisiones.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="tesis_id" class="form-label">Tesis a Revisar *</label>
                        <select class="form-select @error('tesis_id') is-invalid @enderror" 
                                id="tesis_id" name="tesis_id" required>
                            <option value="">Seleccionar tesis...</option>
                            @foreach($tesis as $tesi)
                                <option value="{{ $tesi->id }}" {{ old('tesis_id') == $tesi->id ? 'selected' : '' }}>
                                    {{ $tesi->titulo }} 
                                    (Grupo: {{ $tesi->grupo->nombre_grupo }})
                                    - Estado: {{ ucfirst($tesi->estado) }}
                                </option>
                            @endforeach
                        </select>
                        @error('tesis_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profesor_id" class="form-label">Profesor Revisor *</label>
                        <select class="form-select @error('profesor_id') is-invalid @enderror" 
                                id="profesor_id" name="profesor_id" required>
                            <option value="">Seleccionar profesor...</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->id }}" {{ old('profesor_id') == $profesor->id ? 'selected' : '' }}>
                                    {{ $profesor->name }} ({{ $profesor->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('profesor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentarios de Revisión *</label>
                        <textarea class="form-control @error('comentario') is-invalid @enderror" 
                                  id="comentario" name="comentario" rows="6" 
                                  placeholder="Escriba aquí sus observaciones, correcciones, comentarios sobre la tesis..." required>{{ old('comentario') }}</textarea>
                        @error('comentario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Sea específico y claro en sus comentarios para ayudar a los estudiantes a mejorar su trabajo.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado de la Revisión *</label>
                        <select class="form-select @error('estado') is-invalid @enderror" 
                                id="estado" name="estado" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="corregido" {{ old('estado') == 'corregido' ? 'selected' : '' }}>Corregido</option>
                            <option value="aprobado" {{ old('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rechazado" {{ old('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Pendiente:</strong> Revisión en proceso<br>
                            <strong>Corregido:</strong> Tesis necesita correcciones<br>
                            <strong>Aprobado:</strong> Tesis aprobada definitivamente<br>
                            <strong>Rechazado:</strong> Tesis No cumple con lo minimo 
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Al marcar una revisión como "Aprobado", la tesis cambiará automáticamente a estado "aprobado".
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('revisiones.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Crear Revisión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection