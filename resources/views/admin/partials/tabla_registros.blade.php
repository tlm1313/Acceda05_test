<table class="table table-striped">
    <thead>
        <tr>
            <th>Fecha/Hora</th>
            <th>Tipo</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @forelse($registros as $registro)
            <tr>
                <td>{{ $registro->fecha_hora->format('d/m/Y H:i') }}</td>
                <td>{{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}</td>
                <td>
                    @if($registro->tipo === 'entrada')
                        <span class="badge bg-success">Entrada</span>
                    @else
                        <span class="badge bg-danger">Salida</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No hay registros</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Paginación -->
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
