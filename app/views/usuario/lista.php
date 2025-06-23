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
                <caption>Listado de usuarios</caption>
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (isset($_GET['search']) ? $usuarioModel->getUsuarioByID($_GET['search']) : $usuarioModel->getAllUsuarios() as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id']) ?></td>
                            <td class="mx-auto">
                                <img id="previewImage" src="<?= $usuario['imagen'] ?? '/../../../public/assets/image/default-profile.svg' ?>" alt="Previsualización" width="60" height="60" class="rounded-circle border" style="object-fit: cover;">
                            </td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['correo']) ?></td>
                            <td><?= htmlspecialchars($usuario['contraseña']) ?></td>
                            <td><?= htmlspecialchars($usuario['rol']) ?></td>
                            <td>
                                <select onchange="if(this.value) window.location.href=this.value" class="btn border-bottom">
                                    <option selected disabled>Selecciona una acción</option>
                                    <option value="/public/?page=ver_detalles&id=<?= $ticket['ticket_id'] ?>">Ver detalles</option>
                                    <option value="/public/usuario/?actualizarid=<?= urlencode($usuario['id']) ?>">Actualizar</option>
                                    <option value="/public/usuario/control/?eliminarid=<?= urlencode($usuario['id']) ?>">Eliminar</option>
                                </select>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>