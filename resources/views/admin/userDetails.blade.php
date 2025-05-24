<div class="container">
    <!-- Información del Usuario -->
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            @if($user->foto)
                <img src="/fotos/{{ $user->foto->foto }}" class="img-thumbnail" width="120">
            @else
                <img src="/fotos/default.png" class="img-thumbnail" width="120">
            @endif
        </div>
        <div class="col-md-9">
            <h4>{{ $user->name }} {{ $user->apellidos }}</h4>
            <p><strong>DNI:</strong> {{ $user->Dni }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <!-- Tabla de Registros con Paginación -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                <tr>
                    <td>{{ $registro->fecha_hora->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $registro->tipo === 'entrada' ? 'bg-success' : 'bg-danger' }}">
                            {{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $registros->links() }}
        </div>
    </div>
</div>
