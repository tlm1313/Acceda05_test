<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .footer { margin-top: 20px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Generado el: {{ $fechaGeneracion }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $index => $registro)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $registro->id }}</td>
                <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <span style="color: {{ $registro->tipo === 'entrada' ? 'green' : 'red' }}">
                        {{ ucfirst($registro->tipo) }}
                    </span>
                </td>
                <td>{{ $registro->user->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
    <p>Generado el: {{ $fechaGeneracion }}</p> <!-- Usa la variable aquí -->
    <p>Total de registros: {{ $total }}</p>
</div>
</body>
</html>
