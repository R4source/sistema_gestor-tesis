@extends('layouts.app')

@section('title', 'Editar Revisión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Revisión #{{ $revisione->id }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('revisiones.update', $revisione) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información de solo lectura -->
                    <div class="mb-3">
                        <label class="form-label">Tesis</label>
                        <div class="form-control bg-light">
                            <strong>{{ $revisione->tesis->titulo }}</strong>
                            <br>
                            <small class="text-muted">Grupo: {{ $revisione->tesis->grupo->nombre_grupo }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profesor Revisor</label>
                        <div class="form-control bg-light">
                            {{ $revisione->profesor->name }} ({{ $revisione->profesor->email }})
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentarios de Revisión *</label>
                        <textarea class="form-control @error('comentario') is-invalid @enderror" 
                                  id="comentario" name="comentario" rows="6" required>{{ old('comentario', $revisione->comentario) }}</textarea>
                        @error('comentario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado de la Revisión *</label>
                        <select class="form-select @error('estado') is-invalid @enderror" 
                                id="estado" name="estado" required>
                            <option value="pendiente" {{ old('estado', $revisione->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="corregido" {{ old('estado', $revisione->estado) == 'corregido' ? 'selected' : '' }}>Corregido</option>
                            <option value="aprobado" {{ old('estado', $revisione->estado) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rechazado" {{ old('estado', $revisione->estado) == 'rechazado' ? 'selected' : '' }}>rechazado</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Nota:</strong> Al cambiar a "Aprobado", la tesis se marcará como aprobada definitivamente.
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Advertencia:</strong> Los cambios en esta revisión afectarán el estado de la tesis asociada.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('revisiones.show', $revisione) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Revisión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection