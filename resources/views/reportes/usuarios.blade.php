<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios - Sistema Gestor de Tesis</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 18px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        .stat { background: #ecf0f1; padding: 8px; border-radius: 5px; text-align: center; }
        .stat-number { font-size: 16px; font-weight: bold; color: #2c3e50; }
        .stat-label { font-size: 9px; color: #7f8c8d; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th { background: #34495e; color: white; padding: 6px; text-align: left; font-size: 10px; }
        .table td { padding: 6px; border: 1px solid #ddd; font-size: 9px; }
        .table tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 2px 6px; border-radius: 8px; font-size: 8px; color: white; }
        .badge-danger { background: #e74c3c; }
        .badge-warning { background: #f39c12; }
        .badge-info { background: #3498db; }
        .footer { margin-top: 20px; text-align: center; color: #7f8c8d; font-size: 8px; border-top: 1px solid #bdc3c7; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Usuarios</h1>
        <div>Generado el: {{ $fechaGeneracion->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat">
            <div class="stat-number">{{ $estadisticas['total'] }}</div>
            <div class="stat-label">Total Usuarios</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $estadisticas['admins'] }}</div>
            <div class="stat-label">Administradores</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $estadisticas['profesores'] }}</div>
            <div class="stat-label">Profesores</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $estadisticas['estudiantes'] }}</div>
            <div class="stat-label">Estudiantes</div>
        </div>
    </div>

    <!-- Lista de Usuarios -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Grupos</th>
                <th>Tesis</th>
                <th>Revisiones</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @php
                        $badgeClass = [
                            'admin' => 'badge-danger',
                            'profesor' => 'badge-warning',
                            'estudiante' => 'badge-info'
                        ][$usuario->role] ?? 'badge-secondary';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($usuario->role) }}</span>
                </td>
                <td>
                    @if($usuario->isEstudiante())
                        {{ $usuario->grupos->count() }}
                    @elseif($usuario->isProfesor())
                        {{ $usuario->gruposComoProfesor->count() }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($usuario->isEstudiante())
                        {{ $usuario->grupos->flatMap->tesis->count() }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($usuario->isProfesor())
                        {{ $usuario->revisiones->count() }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el Sistema Gestor de Tesis<br>
        Total de registros: {{ $usuarios->count() }} | {{ $fechaGeneracion->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>