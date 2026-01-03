<!-- resources/views/usuarios/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalles de Usuario')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-user me-2"></i>{{ $usuario->name }}</h4>
                <div class="btn-group">
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información Principal -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2"></i>Información Personal</h5>
                        <div class="border rounded p-3 bg-light">
                            <strong>Nombre:</strong> {{ $usuario->name }}<br>
                            <strong>Email:</strong> {{ $usuario->email }}<br>
                            <strong>Rol:</strong> 
                            @php
                                $badgeClass = [
                                    'admin' => 'bg-danger',
                                    'profesor' => 'bg-warning', 
                                    'estudiante' => 'bg-info'
                                ][$usuario->role] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($usuario->role) }}</span><br>
                            <strong>Registro:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}<br>
                            <strong>Última actualización:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                        <div class="border rounded p-3 bg-light">
                            @if($usuario->isEstudiante())
                                <strong>Grupos:</strong> {{ $usuario->grupos->count() }}<br>
                                <strong>Tesis enviadas:</strong> 
                                {{ $usuario->grupos->flatMap->tesis->count() }}<br>
                            @elseif($usuario->isProfesor())
                                <strong>Grupos asignados:</strong> {{ $usuario->gruposComoProfesor->count() }}<br>
                                <strong>Revisiones realizadas:</strong> {{ $usuario->revisiones->count() }}<br>
                            @else
                                <strong>Acceso:</strong> Administrador completo<br>
                                <strong>Permisos:</strong> Todas las funcionalidades
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información específica por rol -->
                @if($usuario->isEstudiante() && $usuario->grupos->count() > 0)
                <div class="mb-4">
                    <h5><i class="fas fa-users me-2"></i>Grupos del Estudiante</h5>
                    <div class="list-group">
                        @foreach($usuario->grupos as $grupo)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $grupo->nombre_grupo }}</h6>
                                    <small class="text-muted">
                                        Profesor: {{ $grupo->profesor->name }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">{{ $grupo->tesis->count() }} tesis</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($usuario->isProfesor() && $usuario->gruposComoProfesor->count() > 0)
                <div class="mb-4">
                    <h5><i class="fas fa-chalkboard me-2"></i>Grupos como Profesor</h5>
                    <div class="list-group">
                        @foreach($usuario->gruposComoProfesor as $grupo)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $grupo->nombre_grupo }}</h6>
                                    <small class="text-muted">
                                        {{ $grupo->cantidadEstudiantes() }} estudiantes
                                    </small>
                                </div>
                                <span class="badge bg-success">{{ $grupo->tesis->count() }} tesis</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($usuario->isProfesor() && $usuario->revisiones->count() > 0)
                <div class="mb-4">
                    <h5><i class="fas fa-search me-2"></i>Revisiones Realizadas</h5>
                    <div class="list-group">
                        @foreach($usuario->revisiones->take(5) as $revision)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $revision->tesis->titulo }}</h6>
                                    <p class="mb-1 small text-muted">
                                        {{ Str::limit($revision->comentario, 100) }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $revision->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="badge 
                                    @if($revision->estado == 'aprobado') bg-success
                                    @elseif($revision->estado == 'corregido') bg-warning
                                    @else bg-secondary @endif">
                                    {{ ucfirst($revision->estado) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($usuario->revisiones->count() > 5)
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Mostrando 5 de {{ $usuario->revisiones->count() }} revisiones
                        </small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection