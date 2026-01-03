@extends('layouts.app')

@section('title', 'Detalles del Grupo')

@section('content')
<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0"><i class="fas fa-users me-2"></i>{{ $grupo->nombre_grupo }}</h4>
                <div class="btn-group btn-group-sm flex-wrap gap-1">
                    <a href="{{ route('grupos.edit', $grupo) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i><span class="d-none d-sm-inline">Editar</span>
                    </a>
                    <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Volver</span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Profesor y total estudiantes --}}
                <div class="row mb-3">
                    <div class="col-12 col-md-6">
                        <strong><i class="fas fa-chalkboard-teacher me-2"></i>Profesor:</strong>
                        <p class="mb-1 text-break">{{ $grupo->profesor->name }}</p>
                        <small class="text-muted text-break">{{ $grupo->profesor->email }}</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <strong><i class="fas fa-user-friends me-2"></i>Total Estudiantes:</strong>
                        <span class="badge bg-primary fs-6">{{ $grupo->cantidadEstudiantes() }}</span>
                    </div>
                </div>

                {{-- Estudiantes --}}
                <h5 class="mt-4 mb-3"><i class="fas fa-user-graduate me-2"></i>Estudiantes del Grupo</h5>
                <div class="list-group">
                    @foreach($grupo->estudiantes as $estudiante)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-break">
                                <h6 class="mb-1">{{ $estudiante->name }}</h6>
                                <small class="text-muted text-break">{{ $estudiante->email }}</small>
                            </div>
                            <span class="badge bg-light text-dark">Estudiante</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Tesis --}}
                <h5 class="mt-4 mb-3"><i class="fas fa-file-alt me-2"></i>Tesis del Grupo</h5>
                @if($grupo->tesis->count() > 0)
                {{-- Scroll vertical si hay muchas tesis --}}
                <div class="list-group overflow-auto" style="max-height: 400px;">
                    @foreach($grupo->tesis as $tesis)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1 text-break">
                                <h6 class="mb-1">{{ $tesis->titulo }}</h6>
                                <p class="mb-1 text-muted small text-break">{{ Str::limit($tesis->resumen, 150, '...') }}</p>
                                <small class="text-muted">
                                    Estado:
                                    <span class="badge
                                        @if($tesis->estado == 'aprobado') bg-success
                                        @elseif($tesis->estado == 'rechazado') bg-danger
                                        @elseif($tesis->estado == 'en revisión') bg-warning
                                        @else bg-secondary @endif">
                                        {{ ucfirst($tesis->estado) }}
                                    </span>
                                    • Creado: {{ $tesis->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="btn-group btn-group-sm flex-wrap gap-1">
                                <a href="{{ route('tesis.show', $tesis) }}" class="btn btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Este grupo no tiene tesis registradas.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection