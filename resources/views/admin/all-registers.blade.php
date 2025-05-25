@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-primary">
        <div class="d-flex">
            <div class="p-2 flex-grow-1"><h2>Zona de Administrador</h2>
            {{-- <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p> --}}
            </div>

            <div class="p-2">
                <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-secondary" title="Volver">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>

                        <div class="p-2">
                <form method="GET" action="{{ route('admin.export.pdf') }}" class="d-inline">
                @foreach(request()->all() as $key => $value)
                    @if($key !== '_token' && $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <button type="submit" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                </button>
            </form>
            </div>

            <div class="p-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>

            </div>
        </div>
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="filterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ !request()->hasAny(['mes', 'fecha_inicio', 'dni']) ? 'active' : '' }}"
                    href="{{ route('admin.all.registers') }}">Todos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request()->has('mes') ? 'active' : '' }}"
                            id="mes-tab" data-bs-toggle="tab" data-bs-target="#filtroMes" type="button">Por Mes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request()->has('fecha_inicio') && !request()->has('dni') ? 'active' : '' }}"
                            id="personalizado-tab" data-bs-toggle="tab" data-bs-target="#filtroPersonalizado" type="button">Personalizado</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request()->has('dni') ? 'active' : '' }}"
                            id="dni-tab" data-bs-toggle="tab" data-bs-target="#filtroDNI" type="button">Por DNI</button>
                </li>
            </ul>
        </div>

        <div class="card-body">
           <div class="tab-content" id="filterTabsContent">
                <!-- Filtro 2: Por Mes -->
                <div class="tab-pane fade {{ request()->has('mes') ? 'show active' : '' }}" id="filtroMes" role="tabpanel">
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
                <div class="tab-pane fade {{ request()->has('fecha_inicio') && !request()->has('dni') ? 'show active' : '' }}" id="filtroPersonalizado" role="tabpanel">
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


                <!-- Filtro 4: Por DNI con rango de fechas -->
                <div class="tab-pane fade {{ request()->has('dni') ? 'show active' : '' }}" id="filtroDNI" role="tabpanel">
                    <form method="GET" action="{{ route('admin.all.registers') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="dni" class="form-control"
                                value="{{ request('dni') }}" placeholder="DNI del usuario" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="fecha_inicio_dni" class="form-control"
                                value="{{ request('fecha_inicio_dni') }}" max="{{ date('Y-m-d') }}"
                                placeholder="Fecha inicio">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="fecha_fin_dni" class="form-control"
                                value="{{ request('fecha_fin_dni') }}" max="{{ date('Y-m-d') }}"
                                placeholder="Fecha fin">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('admin.all.registers') }}" class="btn btn-secondary w-100">Limpiar</a>
                        </div>
                    </form>
                </div>
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
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Fecha/Hora</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                        <tr>
                            <td>{{ $registro->user->name ?? 'N/A' }} {{ $registro->user->apellidos ?? '' }}</td>
                            <td>{{ $registro->user->apellidos ?? 'N/A' }}</td>
                            <td>{{ $registro->user->Dni ?? 'N/A' }}</td>
                            <td>{{ $registro->user->email ?? 'N/A' }}</td>
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
    // Ocultar todos los formularios excepto el activo
    function hideAllFilterForms() {
        document.querySelectorAll('#filtroMes, #filtroPersonalizado, #filtroDNI').forEach(form => {
            form.classList.remove('show');
        });
    }

    // Manejar clic en pestañas
    document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            // Remover clase active de todas las pestañas
            document.querySelectorAll('.nav-tabs .nav-link').forEach(t => {
                t.classList.remove('active');
            });

            // Agregar clase active a la pestaña clickeada
            this.classList.add('active');

            // Ocultar todos los formularios primero
            hideAllFilterForms();

            // Mostrar solo el formulario correspondiente
            const target = this.getAttribute('data-bs-target') ||
                          this.getAttribute('href');
            if (target) {
                const form = document.querySelector(target);
                if (form) {
                    form.classList.add('show');
                }
            }

            // Si es "Todos los registros", recargar sin filtros
            if (this.getAttribute('href') === "{{ route('admin.all.registers') }}") {
                window.location.href = "{{ route('admin.all.registers') }}";
            }
        });
    });

    // Mostrar el filtro correspondiente al cargar la página
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('dni')) {
        document.querySelector('.nav-link[href="#filtroDNI"]').classList.add('active');
        document.querySelector('#filtroDNI').classList.add('show');
    } else if (urlParams.has('fecha_inicio') && !urlParams.has('dni')) {
        document.querySelector('.nav-link[href="#filtroPersonalizado"]').classList.add('active');
        document.querySelector('#filtroPersonalizado').classList.add('show');
    } else if (urlParams.has('mes')) {
        document.querySelector('.nav-link[href="#filtroMes"]').classList.add('active');
        document.querySelector('#filtroMes').classList.add('show');
    } else {
        document.querySelector('.nav-link[href="{{ route('admin.all.registers') }}"]').classList.add('active');
    }

    // Validar que fecha inicio no sea mayor a fecha fin
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const fechaInicio = this.querySelector('[name*="fecha_inicio"]')?.value;
            const fechaFin = this.querySelector('[name*="fecha_fin"]')?.value;

            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                e.preventDefault();
                alert('La fecha de inicio no puede ser mayor a la fecha final');
                return false;
            }
        });
    });
});
</script>
@endsection
