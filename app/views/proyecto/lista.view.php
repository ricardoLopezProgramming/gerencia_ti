<div class="container">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="shadow-sm bento-card text-white p-3 h-100px d-flex align-items-center justify-content-between border bg-white">
                <div class="container-fluid d-flex justify-content-start">
                    <form action="/public/proyecto/search" id="searchForm" class="d-flex column-gap-2" method="GET" role="search">
                        <input class="form-control" type="search" name="search" id="searchInput" placeholder="Buscar proyecto..." aria-label="Buscar">
                        <select class="form-select" name="categoriaSelect">
                            <option value="id" default selected>ID</option>
                            <option value="name">Nombre</option>
                            <option value="status">Estado</option>
                        </select>
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Limpiar filtros">
                        <i class="fas fa-eraser"></i>
                    </button>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Recargar">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <?php if ($_SESSION['role'] === 'jefe de proyecto'): ?>
                        <a href="/public/proyecto/registro" class="btn btn-outline-primary navbar-icon-btn" title="Registrar nuevo proyecto">Registrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="shadow-sm bento-card text-dark p-4 h-500px overflow-auto border">
                <table class="table table-hover mt-3">
                    <caption>Listado de proyectos</caption>
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Jefe</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project['id']) ?></td>
                                <td><?= htmlspecialchars($project['name']) ?></td>
                                <td><?= htmlspecialchars($project['description']) ?></td>
                                <td><?= htmlspecialchars($project['start_date']) ?></td>
                                <td><?= htmlspecialchars($project['end_date']) ?></td>
                                <td>
                                    <?php
                                    $estado = strtolower($project['status']); // status_name debe venir del JOIN con project_statuses
                                    $badgeClass = match ($estado) {
                                        'pendiente' => 'warning',
                                        'en proceso' => 'danger',
                                        'terminado' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge text-bg-<?= $badgeClass ?>">
                                        <?= htmlspecialchars($project['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($project['manager']) ?></td> <!-- JOIN con users -->
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="/public/proyecto/detalles?id=<?= $project['id'] ?>">Detalles</a></li>
                                            <?php if ($_SESSION['role'] == 'jefe de proyecto'): ?>
                                                <li>
                                                    <a class="dropdown-item" href="/public/proyecto/actualizacion?id=<?= $project['id'] ?>">Actualizar</a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar"
                                                    data-id="<?= $project['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($project['name']) ?>">
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

<!-- Modal para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el proyecto <strong id="modalNombreProyecto" class="text-danger"></strong>?
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Sí, eliminar</a>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script>
document.addEventListener("DOMContentLoaded", () => {
    const modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');

        document.getElementById('modalNombreProyecto').textContent = nombre;
        document.getElementById('btnConfirmarEliminar').href = `/public/proyecto/eliminar?id=${id}`;
    });
});
</script> -->