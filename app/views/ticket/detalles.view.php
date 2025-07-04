<div class="container-fluid p-4">
    <!-- Información del ticket -->
    <div class="row mb-4">
        <div class="col">
            <div class="bento-card shadow-sm border border-primary p-3 text-secondary">
                <div class="card-body">
                    <h3 class="card-title text-primary"><?= htmlspecialchars($ticket['name']) ?></h3>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Estado:
                        <?php
                        // Asume que $ticket['status'] trae el nombre del estado (ej. "pendiente").
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
                    </h6>
                    <p class="card-text"><?= htmlspecialchars($ticket['description']) ?></p>
                    <p class="card-text">
                        <strong>Proyecto:</strong> <?= htmlspecialchars($project['name']) ?>
                    </p>
                    <div class="text-end mt-3">
                        <?php if ($_SESSION['role'] === 'jefe de proyecto' || $_SESSION['role'] === 'administrador'): ?>
                            <a href="/public/ticket/actualizacion?id=<?= $ticket['id'] ?>" class="btn btn-outline-warning">Actualizar</a>
                            <a href="/public/ticket/eliminar?id=<?= $ticket['id'] ?>" class="btn btn-outline-danger">Eliminar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios asignados al ticket -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bento-card shadow-sm border border-info h-100">
                <div class="card-header bg-info text-white">
                    Usuarios asignados al ticket
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($user['name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">No hay usuarios asignados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>