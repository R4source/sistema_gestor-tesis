<!-- resources/views/revisiones/index.blade.php -->
@extends('layouts.app')

@section('title', 'Lista de Revisiones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-search me-2"></i>Revisiones de Tesis</h1>
    <a href="{{ route('revisiones.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nueva Revisión
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tesis</th>
                        <th>Grupo</th>
                        <th>Profesor</th>
                        <th>Comentario</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revisiones as $revision)
                    <tr>
                        <td>{{ $revision->id }}</td>
                        <td>
                            <strong>{{ $revision->tesis->titulo }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ Str::limit($revision->tesis->resumen, 60) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $revision->tesis->grupo->nombre_grupo }}</span>
                        </td>
                        <td>{{ $revision->profesor->name }}</td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                {{ $revision->comentario }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badgeClass = [
                                    'pendiente' => 'bg-secondary',
                                    'corregido' => 'bg-warning',
                                    'aprobado' => 'bg-success',
                                    'rechazado' => 'bg-danger'
                                ][$revision->estado] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($revision->estado) }}</span>
                        </td>
                        <td>
                            <small>{{ $revision->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('revisiones.show', $revision) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('revisiones.edit', $revision) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('revisiones.destroy', $revision) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta revisión?')"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-search fa-2x mb-3"></i><br>
                            No hay revisiones registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $revisiones->where('estado', 'pendiente')->count() }}</h4>
                        <p class="mb-0">Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $revisiones->where('estado', 'corregido')->count() }}</h4>
                        <p class="mb-0">Corregidas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $revisiones->where('estado', 'aprobado')->count() }}</h4>
                        <p class="mb-0">Aprobadas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $revisiones->count() }}</h4>
                        <p class="mb-0">Total</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection