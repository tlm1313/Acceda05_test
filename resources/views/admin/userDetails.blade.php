<div class="container">
    <!-- Información del Usuario -->
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            @if($user->foto)
                <img src="/fotos/{{ $user->foto->foto }}" class="img-fluid rounded-start" alt="Foto de perfil">
            @else
                <img src="/fotos/default.png" class="img-fluid rounded-start" >
            @endif
        </div>
        <div class="col-md-9">
            <h4>{{ $user->name }} {{ $user->apellidos }}</h4>
            <p><strong>DNI:</strong> {{ $user->Dni }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <!-- Pestañas de Filtros -->
    <ul class="nav nav-tabs mb-3" id="filterTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tipoFiltro === 'semana' ? 'active' : '' }}"
                    data-tipo="semana" type="button">
                Última Semana
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tipoFiltro === 'mes' ? 'active' : '' }}"
                    data-tipo="mes" type="button">
                Por Mes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tipoFiltro === 'personalizado' ? 'active' : '' }}"
                    data-tipo="personalizado" type="button">
                Personalizado
            </button>
        </li>
    </ul>

    <!-- Formularios de Filtros -->
    <div class="mb-4">
        <!-- Filtro por Mes (visible solo cuando está activo) -->
        <form id="mesForm" method="GET" class="row g-3 mb-3 {{ $tipoFiltro !== 'mes' ? 'd-none' : '' }}">
            <input type="hidden" name="tipo" value="mes">
            <div class="col-md-6">
                <select name="mes" class="form-select">
                    @foreach($meses as $key => $nombre)
                        <option value="{{ $key }}" {{ $mes == $key ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="anio" class="form-control"
                       value="{{ $anio }}" min="2020" max="{{ date('Y') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>

        <!-- Filtro Personalizado (visible solo cuando está activo) -->
        <form id="personalizadoForm" method="GET" class="row g-3 mb-3 {{ $tipoFiltro !== 'personalizado' ? 'd-none' : '' }}">
            <input type="hidden" name="tipo" value="personalizado">
            <div class="col-md-5">
                <input type="date" name="fecha_inicio" class="form-control"
                       value="{{ $fechaInicio }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-5">
                <input type="date" name="fecha_fin" class="form-control"
                       value="{{ $fechaFin }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Tabla de Registros -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros->paginate(8) as $registro)
                <tr>
                    <td>{{ $registro->fecha_hora->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $registro->tipo === 'entrada' ? 'bg-success' : 'bg-danger' }}">
                            {{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">No hay registros con estos filtros</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $registros->paginate()->links() }}
        </div>
    </div>
</div>
