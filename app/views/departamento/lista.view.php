<div class="container">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="shadow-sm bento-card text-white p-3 h-100px d-flex align-items-center justify-content-between border bg-white">
                <nav class="navbar navbar-expand-lg w-100">
                    <div class="container-fluid d-flex justify-content-start">
                        <form action="/public/departamento/search" id="searchForm" class="d-flex ms-3 my-auto" role="search" method="GET">
                            <input class="form-control me-2" type="search" name="search" id="searchInput" placeholder="Buscar..." aria-label="Buscar">
                            <select class="form-select me-2" name="categoriaSelect">
                                <option value="id" default selected>id</option>
                                <option value="nombre">nombre</option>
                            </select>
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                        <button class="btn btn-outline-secondary navbar-icon-btn" title="Limpiar filtros">
                            <i class="fas fa-eraser"></i>
                        </button>
                        <button class="btn btn-outline-secondary navbar-icon-btn" title="Recargar">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <a href="/public/usuario/formulario" class="btn btn-outline-primary navbar-icon-btn" title="Ver detalles">Registrar</a>
                    </div>
                </nav>
            </div>
        </div>
        <div class="col-md-12">
            <div class="shadow-sm bento-card text-dark p-4 h-500px overflow-auto border">
                <table class="table table-hover mt-3">
                    <caption>Listado de usuarios</caption>
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Imagen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($departamentos as $departamento): ?>
                            <tr>
                                <td><?= htmlspecialchars($departamento['id']) ?></td>
                                <td><?= htmlspecialchars($departamento['nombre']) ?></td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" style="--bs-dropdown-link-hover-bg: var(--bs-primary); --bs-dropdown-link-hover-color: white;">
                                            <li><a class="dropdown-item" href="/public/usuario/detalles?id=<?= $usuario['id'] ?>">Detalles</a></li>
                                            <li><a class="dropdown-item" href="/public/usuario/actualizacion?id=<?= $usuario['id'] ?>">Actualizar</a></li>
                                            <li>
                                                <a
                                                    class="dropdown-item" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar"
                                                    data-id="<?= $departamento['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($departamento['nombre']) ?>">
                                                    Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<nav aria-label="Paginación">

</nav>

<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar a <strong id="modalNombreUsuario" class="text-danger"></strong>?
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Sí, eliminar</a>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>