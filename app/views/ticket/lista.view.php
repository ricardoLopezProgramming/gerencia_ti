<div class="container">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="shadow-sm bento-card bg-white text-dark p-3 d-flex justify-content-between align-items-center border">
                <form action="/public/ticket/search" class="d-flex gap-2" method="GET" role="search">
                    <input class="form-control" type="search" name="search" placeholder="Buscar ticket..." aria-label="Buscar">
                    <select class="form-select" name="categoriaSelect">
                        <option value="id" selected>ID</option>
                        <?php if (!empty($tickets)): ?>
                            <?php foreach ($tickets[0] as $key => $value): ?>
                                <option value="<?= htmlspecialchars($key) ?>"><?= ucfirst(str_replace('_', ' ', $key)) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

                <div class="d-flex gap-2">
                    <a href="/public/ticket/formulario" class="btn btn-outline-primary">Registrar</a>
                    <button class="btn btn-outline-secondary" onclick="location.reload()">Recargar</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="shadow-sm bento-card bg-white text-dark p-4 h-500px overflow-auto border">
                <table class="table table-hover">
                    <caption>Listado de tickets</caption>
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Proyecto</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?= htmlspecialchars($ticket['id']) ?></td>
                                <td><?= htmlspecialchars($ticket['nombre']) ?></td>
                                <td><?= htmlspecialchars($ticket['descripcion']) ?></td>
                                <td>
                                    <?php
                                    $estado = strtolower($ticket['estado']);
                                    $badgeClass = match ($estado) {
                                        'pendiente' => 'warning',
                                        'en proceso' => 'danger',
                                        'resuelto', 'terminado' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge text-bg-<?= $badgeClass ?>">
                                        <?= htmlspecialchars($ticket['estado']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($ticket['proyecto']) ?></td>
                                <td><?= htmlspecialchars($ticket['fecha_creacion']) ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="/public/ticket/detalles?id=<?= $ticket['id'] ?>">Detalles</a></li>
                                            <li><a class="dropdown-item" href="/public/ticket/actualizar?id=<?= $ticket['id'] ?>">Actualizar</a></li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                    data-id="<?= $ticket['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($ticket['nombre']) ?>">
                                                    Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>