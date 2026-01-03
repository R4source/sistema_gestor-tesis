@extends('layouts.app')

@section('title', 'Editar Tesis')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Tesis: {{ $tesis->titulo }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tesis.update', $tesis) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="grupo_id" class="form-label">Grupo *</label>
                        <select class="form-select @error('grupo_id') is-invalid @enderror" 
                                id="grupo_id" name="grupo_id" required>
                            <option value="">Seleccionar grupo...</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_id', $tesis->grupo_id) == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->nombre_grupo }} 
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título *</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo', $tesis->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="resumen" class="form-label">Resumen *</label>
                        <textarea class="form-control @error('resumen') is-invalid @enderror" 
                                  id="resumen" name="resumen" rows="5" required>{{ old('resumen', $tesis->resumen) }}</textarea>
                        @error('resumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" class="form-control @error('archivo') is-invalid @enderror" 
                               id="archivo" name="archivo" accept=".pdf,.doc,.docx">
                        @error('archivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($tesis->archivo)
                            <small class="form-text text-muted">
                                Archivo actual: {{ basename($tesis->archivo) }}
                            </small>
                        @endif
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('tesis.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection