<!-- resources/views/tesis/create.blade.php -->
@extends('layouts.app')

@section('title', 'Subir Tesis')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-upload me-2"></i>Subir Nueva Tesis</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tesis.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="grupo_id" class="form-label">Grupo *</label>
                        <select class="form-select @error('grupo_id') is-invalid @enderror" 
                                id="grupo_id" name="grupo_id" required>
                            <option value="">Seleccionar grupo...</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->nombre_grupo }} 
                                    (Profesor: {{ $grupo->profesor->name }})
                                    - {{ $grupo->cantidadEstudiantes() }} estudiantes
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título de la Tesis *</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo') }}" 
                               placeholder="Ingrese el título completo de la tesis" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="resumen" class="form-label">Resumen *</label>
                        <textarea class="form-control @error('resumen') is-invalid @enderror" 
                                  id="resumen" name="resumen" rows="5" 
                                  placeholder="Escriba un resumen de la tesis..." required>{{ old('resumen') }}</textarea>
                        @error('resumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Máximo 255 caracteres. Describa brevemente el contenido de su tesis.</div>
                    </div>

                    <div class="mb-4">
                        <label for="archivo" class="form-label">Archivo de Tesis *</label>
                        <input type="file" class="form-control @error('archivo') is-invalid @enderror" 
                               id="archivo" name="archivo" accept=".pdf,.doc,.docx" required>
                        @error('archivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Formatos aceptados: PDF, DOC, DOCX. Tamaño máximo: 10MB.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante:</strong> Al enviar la tesis, su estado será marcado como "enviado" y estará lista para revisión por el profesor asignado.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('tesis.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i> Subir Tesis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection