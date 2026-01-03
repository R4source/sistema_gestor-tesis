@extends('layouts.app')

@section('title', 'Detalles de Revisión')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-search me-2"></i>Revisión #{{ $revisione->id }}</h4>
                <div class="btn-group">
                    <a href="{{ route('revisiones.edit', $revisione) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <a href="{{ route('revisiones.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información de la Revisión -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5><i class="fas fa-file-alt me-2"></i>Información de la Tesis</h5>
                        <div class="border rounded p-3 bg-light">
                            <strong>Título:</strong> {{ $revisione->tesis->titulo }}<br>
                            <strong>Grupo:</strong> {{ $revisione->tesis->grupo->nombre_grupo }}<br>
                            <strong>Estado Tesis:</strong> 
                            <span class="badge 
                                @if($revisione->tesis->estado == 'aprobado') bg-success
                                @elseif($revisione->tesis->estado == 'rechazado') bg-danger
                                @elseif($revisione->tesis->estado == 'en revisión') bg-warning
                                @else bg-secondary @endif">
                                {{ ucfirst($revisione->tesis->estado) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-chalkboard-teacher me-2"></i>Información del Revisor</h5>
                        <div class="border rounded p-3 bg-light">
                            <strong>Profesor:</strong> {{ $revisione->profesor->name }}<br>
                            <strong>Email:</strong> {{ $revisione->profesor->email }}<br>
                            <strong>Estado Revisión:</strong>
                            <span class="badge 
                                @if($revisione->estado == 'aprobado') bg-success
                                @elseif($revisione->estado == 'corregido') bg-warning
                                @else bg-secondary @endif">
                                {{ ucfirst($revisione->estado) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="mb-4">
                    <h5><i class="fas fa-comments me-2"></i>Comentarios de Revisión</h5>
                    <div class="border rounded p-4 bg-white">
                        <div class="revision-comment">
                            {!! nl2br(e($revisione->comentario)) !!}
                        </div>
                    </div>
                </div>

                <!-- Información de Fechas -->
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-plus me-2"></i>Fecha de Creación:</strong>
                        <p class="mb-0">{{ $revisione->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-check me-2"></i>Última Actualización:</strong>
                        <p class="mb-0">{{ $revisione->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Grupo -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Estudiantes del Grupo</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($revisione->tesis->grupo->estudiantes as $estudiante)
                    <div class="col-md-6 mb-2">
                        <div class="border rounded p-2">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>
                            <strong>{{ $estudiante->name }}</strong>
                            <br>
                            <small class="text-muted ms-3">{{ $estudiante->email }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.revision-comment {
    line-height: 1.6;
    white-space: pre-wrap;
}
</style>
@endsection