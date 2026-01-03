@extends('layouts.app')

@section('title', 'Detalles de Tesis')

@section('content')
<div class="container px-2 px-sm-3">
    {{-- Cabecera --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h5 h-sm-4 mb-0"><i class="fas fa-file-alt me-2"></i>Detalles de Tesis</h1>
        <a href="{{ route('tesis.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Volver</span>
        </a>
    </div>

    {{-- Card principal --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white px-2 px-sm-3">
            <h4 class="mb-0 h6 h-sm-5"><i class="fas fa-file-alt me-2"></i>{{ Str::limit($tesis->titulo, 60, '...') }}</h4>
        </div>

        <div class="card-body px-2 px-sm-4">
            {{-- Info general --}}
            <div class="row g-2 g-md-3">
                <div class="col-12 col-md-6">
                    <h5 class="h6 mb-2">Información General</h5>
                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr><th class="w-25">ID</th><td class="text-break">{{ $tesis->id }}</td></tr>
                            <tr><th>Título</th><td class="text-break small">{{ Str::limit($tesis->titulo, 50, '...') }}</td></tr>
                            <tr>
                                <th>Grupo</th>
                                <td>
                                    @if($tesis->grupo)
                                        <span class="badge bg-info">{{ $tesis->grupo->nombre_grupo }}</span>
                                    @else
                                        <span class="badge bg-danger">Sin grupo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Estado</th>
                                <td>
                                    @php $badge = [
                                        'enviado'=>'bg-secondary','en revisión'=>'bg-warning',
                                        'aprobado'=>'bg-success','rechazado'=>'bg-danger'
                                    ][$tesis->estado] ?? 'bg-secondary'; @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($tesis->estado) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Archivo --}}
                <div class="col-12 col-md-6">
                    <h5 class="h6 mb-2">Archivo</h5>
                    @if($tesis->archivo)
                        <div class="d-grid gap-2">
                            <a href="{{ route('tesis.descargar', $tesis) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download me-2"></i>Descargar
                            </a>
                            <small class="text-muted text-center text-break">{{ basename($tesis->archivo) }}</small>
                        </div>
                    @else
                        <div class="alert alert-warning py-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>Sin archivo adjunto
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resumen --}}
            <div class="mt-3">
                <h5 class="h6 mb-2">Resumen</h5>
                <div class="border p-2 bg-light overflow-auto" style="max-height: 200px;">
                    @if($tesis->resumen)
                        <p class="mb-0 small text-break">{{ $tesis->resumen }}</p>
                    @else
                        <span class="text-muted small">No hay resumen disponible</span>
                    @endif
                </div>
            </div>

            {{-- Revisiones --}}
            @if($tesis->revisiones->count() > 0)
            <div class="mt-3">
                <h5 class="h6 mb-2">Revisiones</h5>
                <div class="list-group overflow-auto" style="max-height: 250px;">
                    @foreach($tesis->revisiones as $revision)
                    <div class="list-group-item px-2 py-2">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-1">
                            <div class="flex-grow-1 text-break">
                                <h6 class="mb-1 small">{{ $revision->profesor->name ?? 'Profesor no asignado' }}</h6>
                                <p class="mb-1 small text-muted">{{ Str::limit($revision->comentario, 80) }}</p>
                                <small class="text-muted">Estado: {{ $revision->estado }}</small>
                            </div>
                            <small class="text-muted text-nowrap">{{ $revision->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="card-footer px-2 px-sm-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Creado: {{ $tesis->created_at->format('d/m/Y H:i') }} |
                    Actualizado: {{ $tesis->updated_at->format('d/m/Y H:i') }}
                </small>
                @if(auth()->user()->canAccessTesis($tesis))
                <a href="{{ route('tesis.edit', $tesis) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i><span class="d-none d-sm-inline">Editar</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Historial de cambios --}}
    @can('viewAny', $tesis)
    <div class="card mt-3">
        <div class="card-header px-2 px-sm-3">
            <h5 class="mb-0 h6"><i class="fas fa-history me-2"></i>Historial de cambios</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="d-none d-md-table-cell">Fecha</th>
                            <th>Usuario</th>
                            <th>Campo</th>
                            <th class="d-none d-md-table-cell">Antes</th>
                            <th class="d-none d-md-table-cell">Después</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tesis->historial as $log)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="small text-break">{{ Str::limit($log->user->name, 15) }}</td>
                            <td><span class="badge bg-secondary small">{{ ucfirst($log->accion) }}</span></td>
                            <td class="d-none d-md-table-cell text-danger small text-break">{{ Str::limit($log->old_values, 25) }}</td>
                            <td class="d-none d-md-table-cell text-success small text-break">{{ Str::limit($log->new_values, 25) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-muted text-center small">Sin cambios registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endcan
</div>

@endsection

