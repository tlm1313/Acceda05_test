@extends('layouts.app')

@section('content')

<div class="container">
    <div class="alert alert-primary">
        <h1>Zona de Administrador</h1>


        <form name="form1" action="{{ route('admin.update', $usuario->id) }}" method="POST" enctype="multipart/form-data" id="form1">
            @csrf
            @method('PATCH')
            <div class="table-responsive">

                <div class="card mb-3" style="max-width: auto;">
                    <div class="row g-0">

                      <div class="col-md-12">
                        <div class="card-body">
                          <h5 class="card-title">Actualizar Usuarios</h5>
                          <div class="col-md-3 text-center">

                            @if ($usuario->foto)
                            <th><img src="/fotos/{{$usuario->foto->foto}}" alt="foto de perfil" class="img-fluid rounded-start "></th>
                            <th><input type="hidden" name="foto" id="foto" value="{{$usuario->foto->foto}}"></th>
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
                                    <th><input type="hidden" name="id" id="id" value="{{ $usuario->id }}"></th>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <th><input type="text" name="name" id="nom" value="{{ $usuario->name }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th>
                                </tr>
                                <tr>
                                    <th>Apellidos</th>
                                    <th><input type="text" name="apellidos" id="ape" value="{{ $usuario->apellidos }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th>
                                </tr>
                                <tr>
                                    <th>Dni</th>
                                    <th><input type="text" name="Dni" id="dni" class="form-control form-control-sm" value="{{ $usuario->Dni }}"></th>
                                </tr>
                                <tr>
                                    <th>Rol</th>
                                    <th><label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                                        <select class="form-select" id="inlineFormSelectPref" name="role_id" required>
                                          <option selected value="{{ $usuario->role->id }}">{{ $usuario->role->nombre_rol }}</option>
                                          <option value="1">Administrador</option>
                                          <option value="2">Usuario</option>

                                        </select>


                                       {{--  <input type="text" name="rol" id="rol" value="{{ $usuario->role->nombre_rol }}" class="required" title="Campo Obligatorio"></th> --}}
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <th><input type="email" name="email" id="email" value="{{ $usuario->email }}" class="form-control form-control-sm" requiered title="Campo Obligatorio"></th>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <th><input type="password" name="password" id="pass" placeholder="Dejar en Blanco si no se desea cambiar" class="form-control form-control-sm"></th>
                                </tr>

                                <tr>
                                <th>Cambiar Foto</th>
                                <th><input type="file" name="foto_id" id="nuFoto" class="form-control form-control-sm"></th></tr>

                        </div>
                      </div>
                    </div>
                  </div>


            <tr>
                <th colspan="2">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </form>
                    <form action="{{ route('admin.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                    </form>
                </th>
            </tr>

        </tbody>
    </table>

    </div>
</div>
@endsection


