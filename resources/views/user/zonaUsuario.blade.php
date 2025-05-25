@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <div class="d-flex">
            <div class="p-2 flex-grow-1">
                <h2>Zona de Usuario</h2>
                {{-- <p>Hola, {{ Auth::user()->name }}. Estás en tu área privada de usuario.</p> --}}
            </div>

            <div class="p-2 text-end"><form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger" title="Cerrar sesión">
                <i class="fas fa-sign-out-alt"></i>
            </button>
            </form>
            </div>
        </div>

        <div class="table-responsive">

                <div class="card mb-3" style="max-width: auto;">
                    <div class="row g-0">

                      <div class="col-md-3">
                        <div class="card-body">
                          <h5 class="card-title">Datos Usuario</h5>
                          <div class="col-md-3 text-center">

                            @if ($user->foto)
                            <th><img src="/fotos/{{$user->foto->foto}}" alt="foto de perfil" class="img-fluid rounded-start "></th>
                            <th><input type="hidden" name="foto" id="foto" value="{{$user->foto->foto}}"></th>
                            @else
                            <th><img src="/fotos/default.png" alt="sin foto" class="img-fluid rounded-start"></th>
                            @endif

                            {{-- <img src="..." class="img-fluid rounded-start" alt="..."> --}}
                          </div>
                          <table class="table table-striped table-bordered table-hover card-text">
                            <thead>

                            </thead>
                            <tbody>



                                <tr class="visually-hidden">
                                    <th>Id</th>
                                   {{--  <th><input type="hidden" name="id" id="id" value="{{ $usuario->id }}"></th> --}}
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                   {{--  <th><input type="text" name="name" id="nom" value="{{ $usuario->name }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th> --}}
                                    <th>{{ $user->name }}</th>
                                </tr>
                                <tr>
                                    <th>Apellidos</th>
                                   {{--  <th><input type="text" name="apellidos" id="ape" value="{{ $usuario->apellidos }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th> --}}
                                    <th>{{ $user->apellidos }}</th>
                                </tr>
                                <tr>
                                    <th>Dni</th>
                                   {{--  <th><input type="text" name="Dni" id="dni" value="{{ $usuario->Dni }}"></th> --}}
                                   <th>{{ $user->Dni }}</th>
                                </tr>
                                <tr>
                                    <th>Rol</th>
                                    {{-- <th><label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                                        <select class="form-select" id="inlineFormSelectPref" name="role_id" required>
                                          <option selected value="{{ $usuario->role->id }}">{{ $usuario->role->nombre_rol }}</option>
                                          <option value="1">Administrador</option>
                                          <option value="2">Usuario</option>

                                        </select> --}}
                                    <th>{{ $user->role->nombre_rol }}</th>

                                       {{--  <input type="text" name="rol" id="rol" value="{{ $usuario->role->nombre_rol }}" class="required" title="Campo Obligatorio"></th> --}}
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <th>{{ $user->email }}</th>
                                   {{--  <th><input type="email" name="email" id="email" value="{{ $usuario->email }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th> --}}
                                </tr>


                        </div>
                      </div>
                    </div>
                  </div>


            <tr>

            </tr>

        </tbody>
        </table>
        <!-- Control de Asistencia -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Control de Asistencia</h5>
                </div>
                <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <form action="{{ route('user.entrada', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                                <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-sign-in-alt"></i> Registrar Entrada
                                </button>
                            </form>
                        </div>
                        <div>
                            <form action="{{ route('user.salida', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Registrar Salida
                                </button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>

    </div>
</div>
<!-- Historial de registros -->
<div class="col-md-9">
<div class="card mb-3" style="max-width: auto;">
    <div class="card-header">
        <h5>Registros de Acceso</h5>
    </div>

<!-- resources/views/user/zonaUsuario.blade.php -->

<div class="card mt-4">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="registrosTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="semana-tab" data-bs-toggle="tab" href="#semana" type="button" role="tab">
                    Última Semana
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="mes-tab" data-bs-toggle="tab" href="#mes" type="button" role="tab">
                    Por Mes
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="personalizado-tab" data-bs-toggle="tab" href="#personalizado" type="button" role="tab">
                    Personalizado
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content" id="registrosTabsContent">
            <!-- Pestaña Semanal -->
            <div class="tab-pane fade show active" id="semana" >
                <h5>Registros de la última semana</h5>
                @include('user.partials.tabla_registros', ['registros' => $registrosSemanales])
            </div>

            <!-- Pestaña Mensual -->
            <div class="tab-pane fade" id="mes" role="tabpanel">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <select name="mes" class="form-select">
                            @foreach($meses as $key => $nombre)
                                <option value="{{ $key }}" {{ $mes == $key ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="anio" class="form-control"
                               value="{{ $anio }}" min="2020" max="{{ date('Y') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>

                @include('user.partials.tabla_registros', ['registros' => $registrosMensuales])
            </div>

            <!-- Pestaña Personalizada -->
            <div class="tab-pane fade" id="personalizado" role="tabpanel">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <input type="date" name="fecha_inicio" class="form-control"
                               value="{{ $fechaInicio }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="fecha_fin" class="form-control"
                               value="{{ $fechaFin }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>

                @include('user.partials.tabla_registros', ['registros' => $registrosPersonalizados])
            </div>

    </div>
</div>

<!-- boton descarga pdf. funciona para todas las pestañas -->

<div class="card mt-4">
  {{--   <div class="card-header">
        <h5>Exportar Registros</h5>
    </div> --}}
    <div class="card-body">
        <form id="pdfForm" action="{{ route('registros.pdf') }}" method="POST">
    @csrf
    <input type="hidden" name="tipo" id="pdfTipoFiltro" value="semana">
    <input type="hidden" name="mes" id="pdfMes" value="{{ $mes }}">
    <input type="hidden" name="anio" id="pdfAnio" value="{{ $anio }}">
    <input type="hidden" name="fecha_inicio" id="pdfFechaInicio" value="{{ $fechaInicio }}">
    <input type="hidden" name="fecha_fin" id="pdfFechaFin" value="{{ $fechaFin }}">

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-file-pdf"></i> Descargar PDF
    </button>
</form>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Guardar y recuperar pestaña activa
    const tabLinks = document.querySelectorAll('button[data-bs-toggle="tab"]');

    // Recuperar pestaña activa
    const activeTabId = localStorage.getItem('activeTab') || 'semana-tab';
    const activeTab = document.querySelector(`#${activeTabId}`);
    if (activeTab) {
        new bootstrap.Tab(activeTab).show();
    }

    // Guardar pestaña al cambiar
    tabLinks.forEach(tab => {
        tab.addEventListener('click', function() {
            localStorage.setItem('activeTab', this.id);
        });
    });

    // 2. Configurar formulario PDF antes de enviar
    document.getElementById('pdfForm').addEventListener('submit', function(e) {
        const activeTabId = localStorage.getItem('activeTab') || 'semana-tab';
        const tipo = activeTabId.replace('-tab', '');

        // Actualizar los valores del formulario PDF
        document.getElementById('pdfTipoFiltro').value = tipo;

        // Si es el filtro personalizado, asegurar que hay fechas válidas
        if (tipo === 'personalizado') {
            const fechaInicio = document.querySelector('[name="fecha_inicio"]').value;
            const fechaFin = document.querySelector('[name="fecha_fin"]').value;

            if (!fechaInicio || !fechaFin) {
                e.preventDefault();
                alert('Por favor, seleccione un rango de fechas válido para el filtro personalizado');
                return false;
            }

            document.getElementById('pdfFechaInicio').value = fechaInicio;
            document.getElementById('pdfFechaFin').value = fechaFin;
        }

        // Si es el filtro mensual, asegurar mes y año
        if (tipo === 'mes') {
            const mes = document.querySelector('[name="mes"]').value;
            const anio = document.querySelector('[name="anio"]').value;

            document.getElementById('pdfMes').value = mes;
            document.getElementById('pdfAnio').value = anio;
        }

        return true;
    });
});
</script>

@endsection

