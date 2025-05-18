@extends('layouts.app')

@section('content')

<div class="container">
    <div class="alert alert-primary">
        <h1>Zona de Administrador</h1>
        <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p>
      
        
        

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark text-center align-baseline">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>DNI</th>
                        <th>Rol</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->apellidos }}</td>
                        <td>{{ $usuario->Dni }}</td>
                        <td>{{ $usuario->role->nombre_rol }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>&nbsp;</td>
                        <td>
                            @if($usuario->foto)
                                <img src="/fotos/{{ $usuario->foto->foto }}" width="100" class="img-thumbnail">
                            @else
                                <img src="/fotos/default.png" width="100" class="img-thumbnail">
                            @endif
                        </td>
                        <td class="d-flex gap-3">
                            <a href="{{ route('admin.edit', $usuario->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            
                            <form action="{{ route('admin.destroy', $usuario->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar este usuario permanentemente?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <tr>

                      <!-- Formulario de CREACIÓN separado -->
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 border rounded">
          @csrf
          
          <div class="row row g-3 align-items-center">
            <td>  <div class="col-">
                  <input type="text" class="form-control" name="name" placeholder="Nombre" required>
              </div></td>
            <td>  
              <div class="col-">
                  <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required>
              </div>
            </td>
            <td>
              <div class="col-">
                  <input type="text" class="form-control" name="Dni" placeholder="DNI" 
                         pattern="^[0-9]{8}[A-Z]$" 
                         title="8 dígitos y letra mayúscula" required>
              </div>
            </td>
            <td>
              <div class="col-">
                  <select class="form-select" name="role_id" required>
                      <option value="" selected disabled>Rol</option>
                      <option value="1">Administrador</option>
                      <option value="2">Usuario</option>
                  </select>
              </div>
            </td>
            <td>
              <div class="col-">
                  <div class="input-group">
                      <span class="input-group-text">@</span>
                      <input type="email" class="form-control" placeholder="Email" name="email" required>
                  </div>
              </div>
            </td>
            <td>
              <div class="col-">
                  <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
              </div>
            </td>
            <td>
              <div class="col-">
                  <input class="form-control form-control-sm" type="file" name="foto_id">
              </div>
            </td>
            <td>
              <div class="col-">
                  <button type="submit" class="btn btn-primary">Crear</button>
              </div>
            </td>
          </div>
        </form>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
 <style>
    .table td, .table th {
        vertical-align: middle; 
        text-align: center;
    }
    form {
        margin-bottom: 0;
    }
</style> 


@endsection

