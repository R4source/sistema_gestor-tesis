@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                <small class="text-muted fs-4">- Bienvenido, {{ auth()->user()->name }}</small>
            </h1>
            @if(auth()->user()->isAdmin())
            <div class="dropdown">
               <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                 <i class="fas fa-download me-1"></i> Generar Reporte
               </button>
                 <ul class="dropdown-menu">
                   <li><a class="dropdown-item" href="{{ route('reportes.general') }}"><i class="fas fa-file-pdf me-2"></i>Reporte General</a></li>
                   <li><a class="dropdown-item" href="{{ route('reportes.tesis') }}"><i class="fas fa-file-pdf me-2"></i>Reporte de Tesis</a></li>
                   <li><a class="dropdown-item" href="{{ route('reportes.usuarios') }}"><i class="fas fa-file-pdf me-2"></i>Reporte de Usuarios</a></li>
                 </ul>
            </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- ========== DASHBOARD ADMINISTRADOR ========== -->
<div class="row">
    <!-- Tarjetas de Métricas -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['totalUsuarios'] }}</h4>
                        <p class="mb-0">Usuarios</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['totalGrupos'] }}</h4>
                        <p class="mb-0">Grupos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['totalTesis'] }}</h4>
                        <p class="mb-0">Tesis</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['totalRevisiones'] }}</h4>
                        <p class="mb-0">Revisiones</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos Principales -->
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Tesis por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="tesisEstadoChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Tesis por Mes (Últimos 6 meses)</h5>
            </div>
            <div class="card-body">
                <canvas id="tesisMesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Segunda Fila de Gráficos -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Usuarios por Rol</h5>
            </div>
            <div class="card-body">
                <canvas id="usuariosRolChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Revisiones por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="revisionesEstadoChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Grupos y Profesores -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 5 Grupos con Más Tesis</h5>
            </div>
            <div class="card-body">
                @foreach($data['gruposTop'] as $grupo)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $grupo->nombre_grupo }}</h6>
                            <small class="text-muted">
                                Profesor: {{ $grupo->profesor->name }}
                            </small>
                        </div>
                        <span class="badge bg-primary">{{ $grupo->tesis_count }} tesis</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 5 Profesores Más Activos</h5>
            </div>
            <div class="card-body">
                @foreach($data['profesoresTop'] as $profesor)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $profesor->name }}</h6>
                            <small class="text-muted">{{ $profesor->email }}</small>
                        </div>
                        <span class="badge bg-success">{{ $profesor->revisiones_count }} revisiones</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Actividad Reciente -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Tesis Recientes</h5>
            </div>
            <div class="card-body">
                @foreach($data['tesisRecientes'] as $tesis)
                <div class="border-bottom pb-2 mb-2">
                    <h6>{{ $tesis->titulo }}</h6>
                    <small class="text-muted">
                        Grupo: {{ $tesis->grupo->nombre_grupo }} | 
                        Estado: 
                        <span class="badge 
                            @if($tesis->estado == 'aprobado') bg-success
                            @elseif($tesis->estado == 'rechazado') bg-danger
                            @elseif($tesis->estado == 'en revisión') bg-warning
                            @else bg-secondary @endif">
                            {{ ucfirst($tesis->estado) }}
                        </span> | 
                        {{ $tesis->created_at->diffForHumans() }}
                    </small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Usuarios Recientes</h5>
            </div>
            <div class="card-body">
                @foreach($data['usuariosRecientes'] as $usuario)
                <div class="border-bottom pb-2 mb-2">
                    <h6>{{ $usuario->name }}</h6>
                    <small class="text-muted">
                        {{ $usuario->email }} | 
                        <span class="badge 
                            @if($usuario->isAdmin()) bg-danger
                            @elseif($usuario->isProfesor()) bg-warning
                            @else bg-info @endif">
                            {{ ucfirst($usuario->role) }}
                        </span> | 
                        {{ $usuario->created_at->diffForHumans() }}
                    </small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@elseif(auth()->user()->isProfesor())
<!-- ========== DASHBOARD PROFESOR ========== -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['misGrupos']->count() }}</h4>
                        <p class="mb-0">Mis Grupos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['tesisPorRevisar'] }}</h4>
                        <p class="mb-0">Tesis por Revisar</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['revisionesPendientes'] }}</h4>
                        <p class="mb-0">Revisiones Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['revisionesCompletadas'] }}</h4>
                        <p class="mb-0">Revisiones Completadas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos Profesor -->
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Mis Tesis por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="misTesisEstadoChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Mis Revisiones por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="misRevisionesMesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Mis Grupos -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Mis Grupos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($data['misGrupos'] as $grupo)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $grupo->nombre_grupo }}</h5>
                                <p class="card-text">
                                    <i class="fas fa-user-graduate me-1"></i>
                                    {{ $grupo->estudiantes_count }} estudiantes<br>
                                    <i class="fas fa-file-alt me-1"></i>
                                    {{ $grupo->tesis_count }} tesis
                                </p>
                                <a href="{{ route('grupos.show', $grupo) }}" class="btn btn-primary btn-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@elseif(auth()->user()->isEstudiante())
<!-- ========== DASHBOARD ESTUDIANTE ========== -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['misGrupos']->count() }}</h4>
                        <p class="mb-0">Mis Grupos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['misTesis']->count() }}</h4>
                        <p class="mb-0">Mis Tesis</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $data['tesisAprobadas'] }}</h4>
                        <p class="mb-0">Tesis Aprobadas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos Estudiante -->
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Mis Tesis por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="misTesisEstadoChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Mis Tesis -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Mis Tesis</h5>
            </div>
            <div class="card-body">
                @if($data['misTesis']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Grupo</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['misTesis'] as $tesis)
                            <tr>
                                <td>{{ Str::limit($tesis->titulo, 50) }}</td>
                                <td>{{ $tesis->grupo->nombre_grupo }}</td>
                                <td>
                                    <span class="badge 
                                        @if($tesis->estado == 'aprobado') bg-success
                                        @elseif($tesis->estado == 'rechazado') bg-danger
                                        @elseif($tesis->estado == 'en revisión') bg-warning
                                        @else bg-secondary @endif">
                                        {{ ucfirst($tesis->estado) }}
                                    </span>
                                </td>
                                <td>{{ $tesis->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('tesis.show', $tesis) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($tesis->estado != 'aprobado')
                                    <a href="{{ route('tesis.edit', $tesis) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No tienes tesis registradas.
                    <a href="{{ route('tesis.create') }}" class="alert-link">Crear una tesis</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Configuración global de Chart.js
Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.color = '#6c757d';

@if(auth()->user()->isAdmin())
// Gráfico de Tesis por Estado
new Chart(document.getElementById('tesisEstadoChart'), {
    type: 'doughnut',
    data: {
        labels: ['Enviado', 'En Revisión', 'Aprobado', 'Rechazado'],
        datasets: [{
            data: [
                {{ $data['tesisPorEstado']['enviado'] }},
                {{ $data['tesisPorEstado']['en revision'] }},
                {{ $data['tesisPorEstado']['aprobado'] }},
                {{ $data['tesisPorEstado']['rechazado'] }}
            ],
            backgroundColor: [
                '#6c757d',
                '#ffc107',
                '#198754',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        let value = context.raw || 0;
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Gráfico de Tesis por Mes
new Chart(document.getElementById('tesisMesChart'), {
    type: 'line',
    data: {
        labels: @json($data['tesisPorMes']['months']),
        datasets: [{
            label: 'Tesis Creadas',
            data: @json($data['tesisPorMes']['counts']),
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderColor: '#0d6efd',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Gráfico de Usuarios por Rol
new Chart(document.getElementById('usuariosRolChart'), {
    type: 'pie',
    data: {
        labels: ['Administradores', 'Profesores', 'Estudiantes'],
        datasets: [{
            data: [
                {{ $data['usuariosPorRol']['admin'] }},
                {{ $data['usuariosPorRol']['profesor'] }},
                {{ $data['usuariosPorRol']['estudiante'] }}
            ],
            backgroundColor: [
                '#dc3545',
                '#ffc107',
                '#0dcaf0'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Revisiones por Estado
new Chart(document.getElementById('revisionesEstadoChart'), {
    type: 'bar',
    data: {
        labels: ['Pendientes', 'Corregidas', 'Aprobadas'],
        datasets: [{
            label: 'Cantidad',
            data: [
                {{ $data['revisionesPorEstado']['pendiente'] }},
                {{ $data['revisionesPorEstado']['corregido'] }},
                {{ $data['revisionesPorEstado']['aprobado'] }}
            ],
            backgroundColor: [
                '#6c757d',
                '#ffc107',
                '#198754'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

@elseif(auth()->user()->isProfesor() || auth()->user()->isEstudiante())
// Gráfico de Mis Tesis por Estado (compartido entre profesor y estudiante)
new Chart(document.getElementById('misTesisEstadoChart'), {
    type: 'doughnut',
    data: {
        labels: ['Enviado', 'En Revisión', 'Aprobado'],
        datasets: [{
            data: [
                {{ $data['misTesisPorEstado']['enviado'] }},
                {{ $data['misTesisPorEstado']['en revision'] }},
                {{ $data['misTesisPorEstado']['aprobado'] }}
            ],
            backgroundColor: [
                '#6c757d',
                '#ffc107',
                '#198754'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

@if(auth()->user()->isProfesor())
// Gráfico de Revisiones por Mes (solo para profesores)
new Chart(document.getElementById('misRevisionesMesChart'), {
    type: 'bar',
    data: {
        labels: @json($data['misRevisionesPorMes']['months']),
        datasets: [{
            label: 'Revisiones Realizadas',
            data: @json($data['misRevisionesPorMes']['counts']),
            backgroundColor: 'rgba(13, 110, 253, 0.8)',
            borderColor: '#0d6efd',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
@endif
@endif
</script>
@endpush
@endsection