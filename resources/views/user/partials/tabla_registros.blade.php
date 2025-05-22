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
            <td>{{ $registro->fecha_hora->format('d/m/Y H:i:s') }}</td>
            {{-- <td>{{ $registro->created_at->format('d/m/Y H:i:s') }}</td> // solo para testear q funciona--}}
        </tr>
        @endforeach
    </tbody>
</table>

@if($registros->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            {{ $registros->withQueryString()->links() }}
        </div>

        <div class="text-muted">
            Mostrando {{ $registros->firstItem() }} - {{ $registros->lastItem() }} de {{ $registros->total() }} registros
        </div>

    </div>
@endif

@else
<div class="alert alert-info">No hay registros para este período</div>
@endif
