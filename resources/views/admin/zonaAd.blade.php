@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-primary">
        <div class="d-flex">
        <div class="p-2 flex-grow-1"><h2>Zona de Administrador</h2>
        <p>Bienvenido, {{ Auth::user()->name }}. Tienes acceso como administrador.</p></div>
        <div class="p-2"></div>
        <div class="p-2 text-end"><form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger" title="Cerrar sesión">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form></div>
        </div>
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
                            @if($usuario->foto)
                                <img src="/fotos/{{ $usuario->foto->foto }}" width="60" class="img-thumbnail">
                            @else
                                <img src="/fotos/default.png" width="60" class="img-thumbnail">
                            @endif
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

        <!-- Paginación principal -->
        @if($usuarios->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    {{ $usuarios->withQueryString()->links() }}
                </div>
                <div class="text-muted">
                    Mostrando {{ $usuarios->firstItem() }} - {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} registros
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .table th {
        white-space: nowrap;
    }
    .img-thumbnail {
        max-height: 60px;
        object-fit: cover;
    }
    .form-control-sm, .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .pagination {
        flex-wrap: wrap;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .page-link {
        color: #0d6efd;
    }
</style>

<!-- Modal para detalles -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clic en botones "Ver"
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-user-details');
        if (!btn) return;

        e.preventDefault();
        const userId = btn.dataset.userId;
        loadUserDetails(userId);
    });

    // Manejar los formularios de filtrado dentro del modal
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('#mesForm, #personalizadoForm');
        if (!form) return;

        e.preventDefault();
        applyFilters(form);
    });

    // Manejar eventos dentro del modal
    document.addEventListener('click', function(e) {
        // Paginación del modal
        const modalPageLink = e.target.closest('#userDetailsModal .pagination a');
        if (modalPageLink) {
            e.preventDefault();
            loadModalPage(modalPageLink.href);
            return;
        }

        // Pestañas de filtros
        const tabBtn = e.target.closest('#filterTabs .nav-link');
        if (tabBtn) {
            e.preventDefault();
            const tipoFiltro = tabBtn.dataset.tipo;
            const content = document.getElementById('userDetailsContent');
            const userId = content.dataset.userId;

            updateActiveTab(tipoFiltro);
            toggleFilterForms(tipoFiltro);
            loadUserDetails(userId, tipoFiltro);
            return;
        }
    });

    // ========== FUNCIONES PRINCIPALES ==========

    // Cargar detalles del usuario en el modal
    function loadUserDetails(userId, tipoFiltro = 'semana') {
        const modal = bootstrap.Modal.getOrCreateInstance('#userDetailsModal');
        const content = document.getElementById('userDetailsContent');
        content.dataset.userId = userId;

        showLoadingSpinner(content);
        modal.show();

        let url = `/admin/users/${userId}/details?tipo=${tipoFiltro}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
                updateActiveTab(tipoFiltro);
                toggleFilterForms(tipoFiltro);
            })
            .catch(error => {
                showError(content, error);
            });
    }

    // Cargar páginas del modal
    function loadModalPage(url) {
        const content = document.getElementById('userDetailsContent');
        const userId = content.dataset.userId;

        showLoadingSpinner(content);

        fetch(url)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
                content.dataset.userId = userId;

                // Restaurar estado de los filtros
                const activeTab = document.querySelector('#filterTabs .nav-link.active');
                if (activeTab) {
                    updateActiveTab(activeTab.dataset.tipo);
                    toggleFilterForms(activeTab.dataset.tipo);
                }
            })
            .catch(error => {
                showError(content, error);
            });
    }

    // Aplicar filtros en el modal
    function applyFilters(form) {
        const content = document.getElementById('userDetailsContent');
        const userId = content.dataset.userId;
        const formData = new FormData(form);

        // Añadir el tipo de filtro si no está presente
        if (!formData.has('tipo')) {
            const activeTab = document.querySelector('#filterTabs .nav-link.active');
            if (activeTab) {
                formData.append('tipo', activeTab.dataset.tipo);
            }
        }

        const urlParams = new URLSearchParams(formData).toString();

        showLoadingSpinner(content);

        fetch(`/admin/users/${userId}/details?${urlParams}`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
                content.dataset.userId = userId;
            })
            .catch(error => {
                showError(content, error);
            });
    }

    // ========== FUNCIONES AUXILIARES ==========

    // Actualizar pestaña activa
    function updateActiveTab(tipoFiltro) {
        document.querySelectorAll('#filterTabs .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.dataset.tipo === tipoFiltro) {
                link.classList.add('active');
            }
        });
    }

    // Mostrar/ocultar formularios de filtro
    function toggleFilterForms(tipoFiltro) {
        if (tipoFiltro === 'mes') {
            document.getElementById('mesForm')?.classList?.remove('d-none');
            document.getElementById('personalizadoForm')?.classList?.add('d-none');
        } else if (tipoFiltro === 'personalizado') {
            document.getElementById('personalizadoForm')?.classList?.remove('d-none');
            document.getElementById('mesForm')?.classList?.add('d-none');
        } else {
            document.getElementById('mesForm')?.classList?.add('d-none');
            document.getElementById('personalizadoForm')?.classList?.add('d-none');
        }
    }

    // Mostrar spinner de carga
    function showLoadingSpinner(container) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Cargando...</p>
            </div>
        `;
    }

    // Mostrar error
    function showError(container, error) {
        container.innerHTML = `
            <div class="alert alert-danger">
                <h5>Error</h5>
                <p>${error.message || 'Ocurrió un error al cargar los datos'}</p>
                <button onclick="location.reload()" class="btn btn-sm btn-warning">
                    Recargar página
                </button>
            </div>
        `;
    }
});
</script>
@endsection
