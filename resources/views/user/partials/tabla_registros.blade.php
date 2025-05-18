<!-- resources/views/user/partials/tabla_registros.blade.php -->
@if($registros->count() > 0)
<table class="table table-striped">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Fecha/Hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $registro)
        <tr>
            <td>
                @if($registro->tipo == 'entrada')
                    <span class="badge bg-success">Entrada</span>
                @else
                    <span class="badge bg-danger">Salida</span>
                @endif
            </td>
            <td>{{ $registro->fecha_hora->translatedFormat('d/m/Y H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-info">No hay registros para este período</div>
@endif
