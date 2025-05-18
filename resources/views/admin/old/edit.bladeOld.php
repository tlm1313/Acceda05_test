@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-primary">
        <h1>Zona de Administrador</h1>
        <h2>Crear Usuarios</h2>
    
        <form name="form1" action="{{ route('admin.update', $usuario->id) }}" method="POST" enctype="multipart/form-data" id="form1">
            @csrf
            @method('PATCH')

            <table class="table table-striped">
                <thead>
                    
                </thead>
                <tbody>
        
                    
    
                    <tr>
                        <th>Id</th>
                        <th><input type="hidden" name="id" id="id" value="{{ $usuario->id }}"></th>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <th><input type="text" name="nom" id="nom" value="{{ $usuario->name }}" class="required" title="Campo Obligatorio"></th>
                    </tr>
                    <tr>
                        <th>Apellidos</th>
                        <th><input type="text" name="ape" id="ape" value="{{ $usuario->apellidos }}" class="required" title="Campo Obligatorio"></th>
                    </tr>
                    <tr>
                        <th>Dni</th>
                        <th><input type="text" name="dni" id="dni" value="{{ $usuario->Dni }}"></th>
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
                        <th><input type="email" name="email" id="email" value="{{ $usuario->email }}" class="required" title="Campo Obligatorio"></th>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <th><input type="password" name="password" id="pass" placeholder="Dejar en Blanco si no se desea cambiar"></th>
                    </tr>
                   {{--  <tr>
                        <th>Qr</th>
                        <th><input type="text" name="qr" id="qr" value="<?php echo $Qr ?>"></th>
                    </tr> --}}
                    <tr>
                        <th>Foto</th>
                        @if ($usuario->foto)
                        <th><div><img src="/fotos/{{$usuario->foto->foto}}" alt="foto de perfil" width="100"></div></th>
                        <th><input type="hidden" name="foto" id="foto" value="{{$usuario->foto->foto}}"></th>
                        @else
                        <th><img src="/fotos/default.png" width="100"></th>
                        @endif
                    </tr>
                    <tr>
                    <th>Cambiar Foto</th>    
                    <th><input type="file" name="foto_id" id="nuFoto"></th></tr>
                    <tr>
                        <th><input type="submit" class="btn btn-warning" value="Actualizar" name="botActualizar" id="actualizar"></th>
                        
                    
    
                    
             
           {{--  </form>
            <form name="form2" action="{{ route('admin.destroy', $usuario->id) }}" method="POST" enctype="multipart/form-data" id="form2">
                @csrf
                @method('DELETE')

                
                    <th><input type="submit" class="btn btn-danger" value="Eliminar" name="botEliminar" id="eliminar"></th>
                    
                </tr>

            </form>    
--}}    
        </tr>  
        </tbody>
    </table>
       </form>
    </div>
</div>
@endsection


