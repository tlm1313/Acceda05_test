<div class="row">
    <div class="col-md-6">
        <h4>Información del Usuario</h4>
        <table class="table table-bordered">
            <tr>
                <th>Nombre:</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Apellidos:</th>
                <td>{{ $user->apellidos }}</td>
            </tr>
            <tr>
                <th>DNI:</th>
                <td>{{ $user->Dni }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Rol:</th>
                <td>{{ $user->role->nombre_rol }}</td>
            </tr>
            <tr>
                <th>Foto:</th>
                <td>
                    @if($user->foto)
                        <img src="/fotos/{{ $user->foto->foto }}" width="100" class="img-thumbnail">
                    @else
                        <img src="/fotos/default.png" width="100" class="img-thumbnail">
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

<h4 class="mt-4">Últimos 20 Registros</h4>
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
                <td colspan="3" class="text-center">No hay registros de acceso</td>
            </tr>
        @endforelse
    </tbody>
</table>
