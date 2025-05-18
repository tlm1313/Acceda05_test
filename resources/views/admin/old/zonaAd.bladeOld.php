@extends('layouts.app')

  @section('content')
    <div class="container">
      <div class="alert alert-primary">
        <h1>Zona de Administrador</h1>
        <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p>

       {{--  <form class="row row-cols-8 row-cols-sm-8 g-2 g-lg-3"> --}}
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" id="formUsuarios" name="formUsuarios" class="formUsuarios"  > 

          <div class="table-responsive-sm">  
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
                        <th>{{$usuario->rol_id}}</th>
                        <th>{{$usuario->email}}</th>
                        <th><span hidden>{{$usuario->password}}</span></th>
                        
                        <th><img src="fotos/{{$usuario->foto}}" width="100" height="100"></th>
                        <th><a href="php/eliminar.php?Id=<?php echo $usuario->Id ?>"><input type="button" name="del" id="del" value="Eliminar"></a><a href="php/editar.php?Id=<?php echo $usuario->Id ?>& Nom=<?php echo $usuario->Nombre ?>& Ape=<?php echo $usuario->Apellidos ?>& Dni=<?php echo $usuario->Dni ?>& Rol=<?php echo $usuario->Rol ?>& Usu=<?php echo $usuario->Usuario?>& Pass=<?php echo $usuario->Pass?>& Qr=<?php echo $usuario->Qr ?>& Foto=<?php echo $usuario->Foto ?>"><input type="button" name="up" id="up" value="Actualizar"></a></th>
                    </tr> 
    
      
                        @endforeach
    
                    @endif
    
                      <tr>
                        
                        <th><input type="text" name="nom" id="nom" size="10" class="required" title="Campo Obligatorio"></th>
                        <th><input type="text" name="ape" id="ape" size="10" class="required" title="Campo Obligatorio"></th>
                        <!--Comprobacion dni-->
                        <th><input type="text" name="dni" id="dni" size="8"></th>
                        <!--<th><input type="text" name="rol" id="rol" size="10" class="centrado"></th>-->
                        <th><select name="rol" id="rol" class="required" title="Campo Obligatorio">
                                <option value="Administrador">Administrador</option>
                                <option value="Usuario">Usuario</option>
                            </select>
                        </th>
                        <th><input type="text" name="usu" id="usu" size="10" class="required" title="Campo Obligatorio"></th>
                        <th><input type="text" name="pass" id="pass" size="10" class="required" title="Campo Obligatorio"></th>
                         {{-- <th><input type="text" name="qr" id="qr" size="10" class="centrado"></th> --}} 
                        <th><input type="file" name="foto" id="foto" size="10" class="required" title="Campo Obligatorio"></th>
                        <th><input type="submit" name="cr" id="cr" value="Crear"></th>
                    </tr> 
                    
              

            
                
             
                </tbody>
            </table>
        </div>
        </form>     


            
               








    </div>

  
@endsection

