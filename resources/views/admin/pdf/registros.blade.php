<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registros de {{ $user->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f2f2f2; padding: 5px; border: 1px solid #ddd; }
        .table td { padding: 5px; border: 1px solid #ddd; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; }
        .entrada { background-color: #28a745; color: white; }
        .salida { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Registros de Acceso - {{ now()->format('d/m/Y') }}</h2>
        <h3>{{ $user->name }} {{ $user->apellidos }}</h3>
        <p>DNI: {{ $user->Dni }} | Total registros: {{ $registros->count() }}</p>
    </div>

    @if($registros->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Tipo</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $registro)
            <tr>
                <td>{{ $registro->fecha_hora->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($registro->tipo) }}</td>
                <td>
                    <span class="badge {{ $registro->tipo === 'entrada' ? 'entrada' : 'salida' }}">
                        {{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; margin-top: 20px;">
        <p>No hay registros de acceso para este usuario</p>
    </div>
    @endif
</body>
</html>
