@extends('layouts.app')

@section('title', 'Lista de Tesis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-alt me-2"></i>Tesis Registradas</h1>
    <a href="{{ route('tesis.create') }}" class="btn btn-primary">
        <i class="fas fa-upload me-1"></i> Subir Tesis
    </a>
</div>

{{--  Filtro solo Admin / Profesor  --}}
@can('viewAny', App\Models\Tesis::class)
<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('tesis.index') }}" class="row g-2">
            <div class="col-12 col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Título, estudiante, grupo...">
            </div>
            <div class="col-12 col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">-- Todos los estados --</option>
                    <option value="enviado"     {{ request('estado')=='enviado'     ? 'selected' : '' }}>Enviado</option>
                    <option value="en revisión" {{ request('estado')=='en revisión' ? 'selected' : '' }}>En revisión</option>
                    <option value="aprobado"    {{ request('estado')=='aprobado'    ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado"   {{ request('estado')=='rechazado'   ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-primary btn-sm">Buscar</button>
            </div>
        </form>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Grupo</th>
                        <th>Estado</th>
                        <th>Última Actualización</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tesis as $tesi)
                    <tr>
                        <td>{{ $tesi->id }}</td>
                        <td>
                            <strong>{{ $tesi->titulo }}</strong>
                            @if($tesi->resumen)
                            <br><small class="text-muted">{{ Str::limit($tesi->resumen, 80) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $tesi->grupo->nombre_grupo }}</span>
                        </td>
                        <td>
                            @php
                                $badgeClass = [
                                    'enviado' => 'bg-secondary',
                                    'en revisión' => 'bg-warning',
                                    'aprobado' => 'bg-success',
                                    'rechazado' => 'bg-danger'
                                ][$tesi->estado] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($tesi->estado) }}</span>
                        </td>
                        <td>
                            <small>{{ $tesi->updated_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                           @if($tesi->archivo)
                              <a href="{{ route('tesis.descargar', $tesi) }}" class="btn btn-sm btn-outline-primary">
                                   <i class="fas fa-download"></i> Descargar
                              </a>
                                    @else
                                  <span class="text-muted">Sin archivo</span>
                                        @endif
                                </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('tesis.show', $tesi) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tesis.edit', $tesi) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tesis.destroy', $tesi) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta tesis?')"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-2x mb-3"></i><br>
                            No hay tesis registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection