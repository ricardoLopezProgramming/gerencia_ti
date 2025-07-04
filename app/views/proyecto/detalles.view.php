<div class="container-fluid p-4">
    <!-- Información del proyecto -->
    <div class="row mb-4">
        <div class="col">
            <div class="bento-card shadow-sm border border-success p-3 text-secondary">
                <div class="card-body">
                    <h3 class="card-title text-success"><?= htmlspecialchars($project['name']) ?></h3>
                    <h6 class="card-subtitle mb-2 text-muted">Estado: <?= htmlspecialchars($project['status']) ?></h6>
                    <p class="card-text"><?= htmlspecialchars($project['description']) ?></p>
                    <p class="card-text">
                        <strong>Inicio:</strong> <?= htmlspecialchars($project['start_date']) ?> |
                        <strong>Fin:</strong> <?= htmlspecialchars($project['end_date']) ?>
                    </p>
                    <div class="text-end mt-3">
                        <?php if ($_SESSION['role'] != 'jefe de proyecto' || $_SESSION['role'] === 'administrador'): ?>
                            <a href="/public/proyecto/actualizacion?id=<?= $project['id'] ?>" class="btn btn-outline-warning">Actualizar</a>
                            <a href="/public/proyecto/eliminar?id=<?= $project['id'] ?>" class="btn btn-outline-danger">Eliminar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empleados y Tickets -->
    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="bento-card card shadow-sm border-primary d-flex flex-column">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    Empleados asignados
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $empleado): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($empleado['name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($empleado['email']) ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No hay empleados asignados.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Tickets del proyecto -->
        <div class="col-md-6 mb-4">
            <div class="bento-card card shadow-sm border-warning d-flex flex-column h-100">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <span>Tickets del proyecto</span>
                    <?php if ($_SESSION['role'] === 'jefe de proyecto' || $_SESSION['role'] === 'administrador'): ?>
                        <a href="/public/ticket/registro?project_id=<?= $project['id'] ?>" class="btn btn-light btn-sm">Registrar ticket</a>
                    <?php endif; ?>
                </div>
                <ul class="list-group list-group-flush flex-grow-1">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row table-hover">
                                <div>
                                    <strong><?= htmlspecialchars($ticket['name']) ?></strong>
                                    <p class="mb-1"><?= htmlspecialchars($ticket['description']) ?></p>
                                    <small class="text-muted">
                                        Estado:
                                        <?php
                                        $badgeClass = match ($ticket['status']) {
                                            'pendiente' => 'warning',
                                            'en proceso' => 'danger',
                                            'terminado' => 'success',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge text-bg-<?= $badgeClass ?>">
                                            <?= htmlspecialchars($ticket['status']) ?>
                                        </span>
                                    </small>
                                </div>
                                <div class="dropdown mt-2 mt-md-0">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="accionesTicket<?= $ticket['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accionesTicket<?= $ticket['id'] ?>">
                                        <li><a class="dropdown-item" href="/public/ticket/detalles?id=<?= $ticket['id'] ?>">Ver detalles</a></li>
                                        <?php if ($_SESSION['role'] != 'administrador'): ?>
                                            <li><a class="dropdown-item" href="/public/ticket/actualizacion?id=<?= $ticket['id'] ?>">Actualizar</a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['role'] != 'empleado'): ?>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminarTicket"
                                                    data-id="<?= $ticket['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($ticket['name']) ?>">
                                                    Eliminar
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No hay tickets registrados para este proyecto.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>