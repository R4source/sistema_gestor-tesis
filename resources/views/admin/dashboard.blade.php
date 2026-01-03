<!DOCTYPE html>
<html>
<head>
    <title>Panel Admin - Gestor de Tesis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Panel de Administración</h1>

    <h2>Usuarios</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Grupos</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Profesor</th></tr>
        </thead>
        <tbody>
            @foreach($grupos as $grupo)
                <tr>
                    <td>{{ $grupo->id }}</td>
                    <td>{{ $grupo->nombre_grupo }}</td>
                    <td>{{ $grupo->profesor->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Tesis</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Grupo</th><th>Título</th><th>Estado</th></tr>
        </thead>
        <tbody>
            @foreach($teses as $tesis)
                <tr>
                    <td>{{ $tesis->id }}</td>
                    <td>{{ $tesis->grupo->nombre_grupo ?? 'N/A' }}</td>
                    <td>{{ $tesis->titulo }}</td>
                    <td>{{ $tesis->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Revisiones</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Tesis</th><th>Profesor</th><th>Comentario</th><th>Estado</th></tr>
        </thead>
        <tbody>
            @foreach($revisiones as $revision)
                <tr>
                    <td>{{ $revision->id }}</td>
                    <td>{{ $revision->tesis->titulo ?? 'N/A' }}</td>
                    <td>{{ $revision->profesor->name ?? 'N/A' }}</td>
                    <td>{{ $revision->comentario }}</td>
                    <td>{{ $revision->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
