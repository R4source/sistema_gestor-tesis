<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte General - Sistema Gestor de Tesis</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .header .subtitle { color: #7f8c8d; font-size: 14px; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #34495e; border-bottom: 1px solid #bdc3c7; padding-bottom: 5px; font-size: 16px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; text-align: center; }
        .stat-number { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .stat-label { font-size: 12px; color: #7f8c8d; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background: #34495e; color: white; padding: 8px; text-align: left; }
        .table td { padding: 8px; border: 1px solid #ddd; }
        .table tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 10px; color: white; }
        .badge-success { background: #27ae60; }
        .badge-warning { background: #f39c12; }
        .badge-secondary { background: #7f8c8d; }
        .badge-danger { background: #e74c3c; }
        .badge-info { background: #3498db; }
        .footer { margin-top: 30px; text-align: center; color: #7f8c8d; font-size: 10px; border-top: 1px solid #bdc3c7; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte General del Sistema</h1>
        <div class="subtitle">Sistema Gestor de Tesis</div>
        <div>Generado el: {{ $fechaGeneracion->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="section">
        <h2>📊 Estadísticas Generales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['usuarios']['total'] }}</div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['grupos']['total'] }}</div>
                <div class="stat-label">Grupos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['tesis']['total'] }}</div>
                <div class="stat-label">Tesis</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['revisiones']['total'] }}</div>
                <div class="stat-label">Revisiones</div>
            </div>
        </div>
    </div>

    <!-- Distribución de Usuarios -->
    <div class="section">
        <h2>👥 Distribución de Usuarios</h2>
        <table class="table">
            <tr>
                <th>Rol</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
            </tr>
            <tr>
                <td>Administradores</td>
                <td>{{ $estadisticas['usuarios']['admins'] }}</td>
                <td>{{ number_format(($estadisticas['usuarios']['admins'] / $estadisticas['usuarios']['total']) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td>Profesores</td>
                <td>{{ $estadisticas['usuarios']['profesores'] }}</td>
                <td>{{ number_format(($estadisticas['usuarios']['profesores'] / $estadisticas['usuarios']['total']) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td>Estudiantes</td>
                <td>{{ $estadisticas['usuarios']['estudiantes'] }}</td>
                <td>{{ number_format(($estadisticas['usuarios']['estudiantes'] / $estadisticas['usuarios']['total']) * 100, 1) }}%</td>
            </tr>
        </table>
    </div>

    <!-- Estado de Tesis -->
    <div class="section">
        <h2>📄 Estado de las Tesis</h2>
        <table class="table">
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
            </tr>
            <tr>
                <td><span class="badge badge-secondary">Enviado</span></td>
                <td>{{ $estadisticas['tesis']['enviado'] }}</td>
                <td>{{ number_format(($estadisticas['tesis']['enviado'] / $estadisticas['tesis']['total']) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td><span class="badge badge-warning">En Revisión</span></td>
                <td>{{ $estadisticas['tesis']['en revision'] }}</td>
                <td>{{ number_format(($estadisticas['tesis']['en revision'] / $estadisticas['tesis']['total']) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td><span class="badge badge-success">Aprobado</span></td>
                <td>{{ $estadisticas['tesis']['aprobado'] }}</td>
                <td>{{ number_format(($estadisticas['tesis']['aprobado'] / $estadisticas['tesis']['total']) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td><span class="badge badge-danger">Rechazado</span></td>
                <td>{{ $estadisticas['tesis']['rechazado'] }}</td>
                <td>{{ number_format(($estadisticas['tesis']['rechazado'] / $estadisticas['tesis']['total']) * 100, 1) }}%</td>
            </tr>
        </table>
    </div>

    <!-- Top Grupos -->
    <div class="section">
        <h2>🏆 Top 5 Grupos con Más Tesis</h2>
        <table class="table">
            <tr>
                <th>Grupo</th>
                <th>Profesor</th>
                <th>Tesis</th>
            </tr>
            @foreach($gruposTop as $grupo)
            <tr>
                <td>{{ $grupo->nombre_grupo }}</td>
                <td>{{ $grupo->profesor->name }}</td>
                <td>{{ $grupo->tesis_count }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- Tesis Recientes -->
    <div class="section">
        <h2>🆕 Tesis Recientes</h2>
        <table class="table">
            <tr>
                <th>Título</th>
                <th>Grupo</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
            @foreach($tesisRecientes as $tesis)
            <tr>
                <td>{{ Str::limit($tesis->titulo, 40) }}</td>
                <td>{{ $tesis->grupo->nombre_grupo }}</td>
                <td>
                    @php
                        $badgeClass = [
                            'enviado' => 'badge-secondary',
                            'en revisión' => 'badge-warning',
                            'aprobado' => 'badge-success',
                            'rechazado' => 'badge-danger'
                        ][$tesis->estado] ?? 'badge-secondary';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($tesis->estado) }}</span>
                </td>
                <td>{{ $tesis->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        Reporte generado automáticamente por el Sistema Gestor de Tesis<br>
        {{ $fechaGeneracion->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>