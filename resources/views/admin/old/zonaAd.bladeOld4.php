@extends('layouts.app')

  @section('content')
    <div class="container">
      <div class="alert alert-primary">
        <h1>Zona de Administrador</h1>
        <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p>

       {{--  <form class="row row-cols-8 row-cols-sm-8 g-2 g-lg-3"> --}}
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" > 

        @csrf

          <div class="table-responsive">  
            <table class="table table-striped table-bordered table-hover" id="tablaUsuarios">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Dni</th>
                        <th>Rol</th>
                        <th>Email</th>
                        <th>Password</th>
                 {{--        <th>Codigo QR</th> --}}
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Usuarios se cargarán desde la base de datos -->
    
                    @if($usuarios)
                        @foreach ($usuarios as $usuario )
                            
                                                
    
                     <tr>
                        
                        <th>{{$usuario->name}}</th>
                        <th>{{$usuario->apellidos}}</th>
                        <th>{{$usuario->Dni}}</th>
                        <th>{{$usuario->role->nombre_rol}}</th>
                        <th>{{$usuario->email}}</th>
                        <th><span hidden>{{$usuario->password}}</span></th>
                        @if ($usuario->foto)
                          
                        <th><img src="fotos/{{$usuario->foto->foto}}" width="100"></th>
                        @else
                        <th><img src="fotos/default.png" width="100"></th>
                        @endif
                        <th><a href="php/eliminar.php?Id=<?php echo $usuario->Id ?>"><input type="button" name="del" id="del" value="Eliminar"></a><a href="{{ route('admin.edit', $usuario->id) }}"><input type="button" name="up" id="up" value="Actualizar"></a></th>
                    </tr> 
    
      
                        @endforeach
    
                    @endif
    
                      <tr>
                        
                        <th> <div class="col-12">
                          <label class="visually-hidden" for="inlineFormInputGroupUsername">Nombre</label>
                          <div class="input-group">
                          
                            <input type="text" class="form-control" id="inlineFormInputGroupUsername" name="name" placeholder="Nombre">
                          </div>
                        </div></th>
                        <th><div class="col-12">
                          <label class="visually-hidden" for="inlineFormInputGroupUsername">Apellidos</label>
                          <div class="input-group">
                          
                            <input type="text" class="form-control" id="inlineFormInputGroupUsername" name="apellidos" placeholder="Apellidos">
                          </div>
                        </div></th>
                        <!--Comprobacion dni-->
                        <th><div class="col-12">
                          <label class="visually-hidden" for="inlineFormInputGroupUsername">Dni</label>
                          <div class="input-group">
                          
                            <input type="text" class="form-control" id="inlineFormInputGroupUsername" name="Dni" placeholder="Dni" pattern="^[0-9]{8}[A-Z]$" title="El DNI debe tener 8 dígitos seguidos de una letra mayúscula. Ejemplo: 12345678A" required>
                          </div>
                        </div></th>
                        <!--<th><input type="text" name="rol" id="rol" size="10" class="centrado"></th>-->
                        <th> <div class="col-12">
                          <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                          <select class="form-select" id="inlineFormSelectPref" name="role_id" required>
                            <option selected>Rol</option>
                            <option value="1">Administrador</option>
                            <option value="2">Usuario</option>
                            
                          </select>
                        </div>
                        </th>
                        <th><div class="col-12">
                          <label class="visually-hidden" for="inlineFormInputGroupUsername">E-mail</label>
                          <div class="input-group">
                            <div class="input-group-text">@</div>
                            <input type="email" class="form-control" id="inlineFormInputGroupUsername" placeholder="E-mail" name="email" required>
                          </div>
                        </div></th>
                        <th><div class="col-12">
                          <label class="visually-hidden" for="inlineFormInputGroupUsername">Contraseña</label>
                          <div class="input-group">
                            
                            <input type="password" class="form-control" id="inlineFormInputGroupUsername" placeholder="Contraseña" name="password" required>
                          </div>
                        </div></th>
                         {{-- <th><input type="text" name="qr" id="qr" size="10" class="centrado"></th> --}} 
                        <th><div class="mt-1">
                          <label for="formFileSm" class="visually-hidden"></label>
                          <input class="form-control form-control-sm" id="formFileSm" type="file" name="foto_id" >
                        </div>
                          
                        </th>
                        <th><div class="col-12">
                          <button type="submit" class="btn btn-primary" name="crear">Crear</button>
                        </div></th>
                    </tr> 
                    
                    
              

            
                
             
                </tbody>
            </table>
        </div>
        </form>     


            
               








    </div>

  
@endsection

