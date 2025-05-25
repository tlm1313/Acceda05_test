<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Registros</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ storage_path('../public/images/logoAcceda.png') }}" style="height: 50px; margin-bottom: 10px;">
        <div class="title">Reporte de Registros</div>
        <div class="subtitle">{{ config('app.name') }}</div>
        <div>Generado el: {{ now()->format('d/m/Y H:i') }}</div>

        @if(!empty($filtros))
        <div class="filters" style="margin-top: 10px;">
            <strong>Filtros aplicados:</strong>
            @if(isset($filtros['mes']) && isset($filtros['anio']))
                | Mes: {{ $meses[$filtros['mes']] }} {{ $filtros['anio'] }}
            @endif
            @if(isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin']))
                | Desde: {{ $filtros['fecha_inicio'] }} hasta {{ $filtros['fecha_fin'] }}
            @endif
            @if(isset($filtros['dni']))
                | DNI: {{ $filtros['dni'] }}
            @endif
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>DNI</th>
                <th>Fecha/Hora</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $registro)
            <tr>
                <td>{{ $registro->user->name ?? 'N/A' }} {{ $registro->user->apellidos ?? '' }}</td>
                <td>{{ $registro->user->Dni ?? 'N/A' }}</td>
                <td>{{ $registro->fecha_hora->format('d/m/Y H:i:s') }}</td>
                <td>{{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página {PAGENO} de {nbpg}
    </div>
</body>
</html>
