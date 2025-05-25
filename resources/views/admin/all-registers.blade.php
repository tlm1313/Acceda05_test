@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ !request()->hasAny(['mes', 'fecha_inicio', 'dni']) ? 'active' : '' }}"
                       href="{{ route('admin.all.registers') }}">Todos los registros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->has('mes') ? 'active' : '' }}"
                       data-bs-toggle="collapse" href="#filtroMes">Por Mes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->has('fecha_inicio') && !request()->has('dni') ? 'active' : '' }}"
                       data-bs-toggle="collapse" href="#filtroPersonalizado">Personalizado</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->has('dni') ? 'active' : '' }}"
                       data-bs-toggle="collapse" href="#filtroDNI">Por DNI</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Filtro 2: Por Mes -->
            <div class="collapse {{ request()->has('mes') ? 'show' : '' }}" id="filtroMes">
                <form method="GET" action="{{ route('admin.all.registers') }}" class="row g-3">
                    <div class="col-md-5">
                        <select name="mes" class="form-select" required>
                            <option value="">Seleccione un mes</option>
                            @foreach($meses as $key => $nombre)
                                <option value="{{ $key }}" {{ request('mes') == $key ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="anio" class="form-control"
                               value="{{ request('anio') ?? date('Y') }}" min="2020" max="{{ date('Y') }}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>

            <!-- Filtro 3: Personalizado -->
            <div class="collapse {{ request()->has('fecha_inicio') && !request()->has('dni') ? 'show' : '' }}" id="filtroPersonalizado">
                <form method="GET" action="{{ route('admin.all.registers') }}" class="row g-3">
                    <div class="col-md-5">
                        <input type="date" name="fecha_inicio" class="form-control"
                               value="{{ request('fecha_inicio') }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="fecha_fin" class="form-control"
                               value="{{ request('fecha_fin') }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>

            <!-- Filtro 4: Por DNI y fecha -->
            <div class="collapse {{ request()->has('dni') ? 'show' : '' }}" id="filtroDNI">
                <form method="GET" action="{{ route('admin.all.registers') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="dni" class="form-control"
                               value="{{ request('dni') }}" placeholder="DNI del usuario" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="fecha_exacta" class="form-control"
                               value="{{ request('fecha_exacta') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.all.registers') }}" class="btn btn-secondary w-100">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Resultados</h5>
            <div>
                Mostrando {{ $registros->firstItem() }} - {{ $registros->lastItem() }} de {{ $registros->total() }} registros
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>DNI</th>
                            <th>Fecha/Hora</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                        <tr>
                            <td>{{ $registro->user->name ?? 'N/A' }} {{ $registro->user->apellidos ?? '' }}</td>
                            <td>{{ $registro->user->Dni ?? 'N/A' }}</td>
                            <td>{{ $registro->fecha_hora->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <span class="badge {{ $registro->tipo === 'entrada' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $registro->tipo === 'entrada' ? 'Entrada' : 'Salida' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No se encontraron registros</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $registros->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activar el tab correspondiente al filtro actual
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('dni')) {
        document.querySelector('#filtroDNI').classList.add('show');
    } else if (urlParams.has('fecha_inicio')) {
        document.querySelector('#filtroPersonalizado').classList.add('show');
    } else if (urlParams.has('mes')) {
        document.querySelector('#filtroMes').classList.add('show');
    }
});
</script>
@endsection
