<div class="container">
    <div class="row g-4">
        <!-- Barra de herramientas -->
        <div class="col-md-12">
            <div class="shadow-sm bento-card text-white p-3 h-100px d-flex align-items-center justify-content-between border bg-white">
                <div class="container-fluid d-flex justify-content-start">
                    <form action="/public/ticket/search" id="searchForm" class="d-flex column-gap-2" method="GET" role="search">
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
                        <a href="/public/ticket/registro" class="btn btn-outline-primary navbar-icon-btn" title="Registrar nuevo proyecto">Registrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de tickets -->
        <div class="col-12">
            <div class="shadow-sm bento-card p-4 bg-white border overflow-auto h-500px">
                <table class="table table-hover">
                    <caption>Ticket List</caption>
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Project</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?= htmlspecialchars($ticket['id']) ?></td>
                                <td><?= htmlspecialchars($ticket['name']) ?></td>
                                <td><?= htmlspecialchars($ticket['description']) ?></td>
                                <td>
                                    <?php
                                    $estado = strtolower($ticket['status']);
                                    $badgeClass = match ($estado) {
                                        'pendiente' => 'warning',
                                        'en proceso' => 'danger',
                                        'resuelto', 'terminado' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge text-bg-<?= $badgeClass ?>"><?= htmlspecialchars($ticket['status']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($ticket['project']) ?></td>
                                <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="/public/ticket/detalles?id=<?= $ticket['id'] ?>">Detalles</a></li>
                                            <li><a class="dropdown-item" href="/public/ticket/actualizacion?id=<?= $ticket['id'] ?>">Actualizar</a></li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                    data-id="<?= $ticket['id'] ?>"
                                                    data-name="<?= htmlspecialchars($ticket['name']) ?>">
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