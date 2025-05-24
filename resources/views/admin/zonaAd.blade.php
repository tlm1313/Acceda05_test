@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-primary">
        <h2>Zona de Administrador</h2>
        <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p>

        <!-- Formulario de CREACIÓN separado -->
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 bg-light rounded">
            @csrf
            <h5 class="mb-3">Crear Nuevo Usuario</h5>
            <div class="row g-2 align-items-center">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Nombre" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="apellidos" placeholder="Apellidos" required>
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control form-control-sm" name="Dni" placeholder="DNI" required>
                </div>
                <div class="col-md-1">
                    <select class="form-select form-select-sm" name="role_id" required>
                        <option value="" selected disabled>Rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="email" class="form-control form-control-sm" placeholder="Email" name="email" required>
                </div>
                <div class="col-md-1">
                    <input type="password" class="form-control form-control-sm" placeholder="Contraseña" name="password" required>
                </div>
                <div class="col-md-2">
                    <input class="form-control form-control-sm" type="file" name="foto_id">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary">Crear</button>
                </div>
            </div>
        </form>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>DNI</th>
                        <th>Rol</th>
                        <th>Email</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr class="text-center">
                        <td class="align-middle">{{ $usuario->name }}</td>
                        <td class="align-middle">{{ $usuario->apellidos }}</td>
                        <td class="align-middle">{{ $usuario->Dni }}</td>
                        <td class="align-middle">{{ $usuario->role->nombre_rol }}</td>
                        <td class="align-middle">{{ $usuario->email }}</td>
                        <td class="align-middle">
                            <img src="/fotos/{{ $usuario->foto->foto }}" width="60" class="img-thumbnail">
                        </td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.edit', $usuario->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('admin.destroy', $usuario->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar este usuario?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                                 <!-- Botón para ver detalles del usuario / acceso a modal -->

                            @if($usuario->role->nombre_rol === 'Usuario')
                                <button class="btn btn-sm btn-info view-user-details"
                                        data-user-id="{{ $usuario->id }}">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                            @endif


                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table th {
        white-space: nowrap; /* Evita que los títulos se dividan en varias líneas */
    }
    .img-thumbnail {
        max-height: 60px;
        object-fit: cover;
    }
    .form-control-sm, .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
<!-- Modal para detalles (añade esto al final del archivo) -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- Contenido cargado via AJAX -->
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Añade este script al final -->
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', async (e) => {
        const btn = e.target.closest('.view-user-details');
        if (!btn) return;

        try {
            const response = await fetch(`/admin/users/${btn.dataset.userId}/details`);
            const html = await response.text();
            document.getElementById('userDetailsContent').innerHTML = html;
            new bootstrap.Modal('#userDetailsModal').show();
        } catch (error) {
            console.error("Error:", error);
        }
    });
});
</script>
@endsection
