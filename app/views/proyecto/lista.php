<?php
if (isset($_GET['eliminarid'])) {
    $usuarioModel->deleteByid($_GET['eliminarid']);
    header('Location: /public/usuario/control');
    exit;
}

$usuarioActualizar = null;
if (isset($_GET['actualizarid'])) {
    $usuarioActualizar = $usuarioModel->getByID($_GET['actualizarid']);
    if ($usuarioActualizar) {
        $usuarioActualizar = $usuarioActualizar[0]; // solo uno
    }
}
?>

<div class="row g-4">
    <div class="col-12">
        <div class="bento-card text-white p-3 h-100px d-flex align-items-center justify-content-between border border-secondary bg-white">
            <nav class="navbar navbar-expand-lg w-100">
                <div class="container-fluid d-flex justify-content-start">
                    <form id="searchForm" class="d-flex ms-3 my-auto" role="search" method="GET">
                        <input class="form-control me-2" type="search" name="search" id="searchInput" placeholder="Buscar..." aria-label="Buscar">
                        <select class="form-select me-2" id="categoriaSelect">
                            <option value="">Todas las categorías</option>
                            <option value="1">Administrador</option>
                            <option value="2">Cliente</option>
                            <option value="3">Invitado</option>
                        </select>
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Atrás">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Adelante">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Limpiar filtros">
                        <i class="fas fa-eraser"></i>
                    </button>
                    <button class="btn btn-outline-secondary navbar-icon-btn" title="Recargar">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </nav>
        </div>
    </div>
    <div class="col-12">
        <div class="bento-card bg-light text-dark p-4 h-500px overflow-auto border border-secondary">
            <table class="table table-bordered mt-3">
                <caption>Listado de proyectos</caption>
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (isset($_GET['search']) ? $proyectoModel->getProyectoByID($_GET['search']) : $proyectoModel->getAllProyectos() as $proyecto): ?>
                        <tr>
                        <td><?= htmlspecialchars($proyecto['id']) ?></td>
                        <td><?= htmlspecialchars($proyecto['nombre']) ?></td>
                        <td><?= htmlspecialchars($proyecto['descripcion']) ?></td>
                        <td><?= htmlspecialchars($proyecto['fecha_inicio']) ?></td>
                        <td><?= htmlspecialchars($proyecto['fecha_fin']) ?></td>
                        <td><?= htmlspecialchars($proyecto['estado']) ?></td>
                        <td>
                            <select onchange="if(this.value) window.location.href=this.value" class="form-select form-select-sm">
                                <option selected disabled>Selecciona acción</option>
                                <option value="/public/?page=detalles_proyecto&id=<?= $proyecto['id'] ?>">Ver detalles</option>
                                <option value="/public/?page=formulario_actualizar_proyecto&id=<?= $proyecto['id'] ?>"
                                    <?= ($_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 3) ? '' : 'disabled' ?>>
                                    Actualizar
                                </option>
                                <option value="../app/controllers/EliminarProyectoController.php?id=<?= $proyecto['id'] ?>"
                                    <?= ($_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 3) ? '' : 'disabled' ?>>
                                    Eliminar
                                </option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
