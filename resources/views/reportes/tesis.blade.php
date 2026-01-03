<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Tesis - Sistema Gestor de Tesis</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 18px; }
        .filtro { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th { background: #34495e; color: white; padding: 6px; text-align: left; font-size: 10px; }
        .table td { padding: 6px; border: 1px solid #ddd; font-size: 9px; }
        .table tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 2px 6px; border-radius: 8px; font-size: 8px; color: white; }
        .badge-success { background: #27ae60; }
        .badge-warning { background: #f39c12; }
        .badge-secondary { background: #7f8c8d; }
        .badge-danger { background: #e74c3c; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px; }
        .stat { background: #ecf0f1; padding: 8px; border-radius: 5px; text-align: center; }
        .stat-number { font-size: 16px; font-weight: bold; color: #2c3e50; }
        .stat-label { font-size: 9px; color: #7f8c8d; }
        .footer { margin-top: 20px; text-align: center; color: #7f8c8d; font-size: 8px; border-top: 1px solid #bdc3c7; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Tesis</h1>
        <div>Generado el: {{ $fechaGeneracion->format('d/m/Y H:i') }}</div>
        @if($filtroEstado !== 'todos')
        <div class="filtro">
            <strong>Filtro aplicado:</strong> Estado = {{ ucfirst($filtroEstado) }}
        </div>
        @endif
    </div>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat">
            <div class="stat-number">{{ $totalTesis }}</div>
            <div class="stat-label">Total Tesis</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $tesisPorEstado['enviado'] }}</div>
            <div class="stat-label">Enviadas</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $tesisPorEstado['en revision'] }}</div>
            <div class="stat-label">En Revisión</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $tesisPorEstado['aprobado'] }}</div>
            <div class="stat-label">Aprobadas</div>
        </div>
    </div>

    <!-- Lista de Tesis -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Grupo</th>
                <th>Estado</th>
                <th>Fecha Envío</th>
                <th>Última Actualización</th>
                <th>Revisiones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tesis as $tesi)
            <tr>
                <td>{{ $tesi->id }}</td>
                <td>{{ Str::limit($tesi->titulo, 30) }}</td>
                <td>{{ $tesi->grupo->nombre_grupo }}</td>
                <td>
                    @php
                        $badgeClass = [
                            'enviado' => 'badge-secondary',
                            'en revisión' => 'badge-warning',
                            'aprobado' => 'badge-success',
                            'rechazado' => 'badge-danger'
                        ][$tesi->estado] ?? 'badge-secondary';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($tesi->estado) }}</span>
                </td>
                <td>{{ $tesi->created_at->format('d/m/Y') }}</td>
                <td>{{ $tesi->updated_at->format('d/m/Y') }}</td>
                <td>{{ $tesi->revisiones->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el Sistema Gestor de Tesis<br>
        Total de registros: {{ $tesis->count() }} | {{ $fechaGeneracion->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>