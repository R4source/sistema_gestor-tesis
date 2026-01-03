<!-- resources/views/grupos/index.blade.php -->
@extends('layouts.app')

@section('title', 'Lista de Grupos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users me-2"></i>Grupos de Trabajo</h1>
    <a href="{{ route('grupos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nuevo Grupo
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Grupo</th>
                        <th>Profesor</th>
                        <th>Estudiantes</th>
                        <th>Tesis</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                    <tr>
                        <td>{{ $grupo->id }}</td>
                        <td>
                            <strong>{{ $grupo->nombre_grupo }}</strong>
                        </td>
                        <td>{{ $grupo->profesor->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $grupo->cantidadEstudiantes() }} estudiantes</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $grupo->tesis->count() }} tesis</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('grupos.show', $grupo) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('grupos.edit', $grupo) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('grupos.destroy', $grupo) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar este grupo?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-3"></i><br>
                            No hay grupos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection