<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Registro de horas laborales</h2>
        <form class="d-flex" method="GET" action="">
            <input type="date" name="fecha_inicio" class="form-control me-2" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
            <input type="date" name="fecha_fin" class="form-control me-2" value="<?= $_GET['fecha_fin'] ?? '' ?>">
            <button class="btn btn-outline-primary">Filtrar</button>
        </form>
    </div>

    <form method="POST" action="/public/horas/<?= $sesionActiva ? 'detener' : 'iniciar' ?>">
        <input type="hidden" name="log_id" value="<?= $logId ?>">

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Estado de la jornada</h5>
                <?php if ($sesionActiva): ?>
                    <p class="text-success">🟢 Sesión activa desde <strong><?= date('H:i:s', strtotime($sesionActiva['start_time'])) ?></strong></p>
                    <button type="submit" class="btn btn-danger">Finalizar sesión</button>
                <?php else: ?>
                    <p class="text-muted">🔴 No hay sesión activa</p>
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <form method="POST" action="/public/horas/guardar_observacion">
        <input type="hidden" name="log_id" value="<?= $logId ?>">
        <div class="card mb-4">
            <div class="card-body">
                <label for="notes" class="form-label">Observaciones del día</label>
                <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($notas ?? '') ?></textarea>
                <button type="submit" class="btn btn-secondary mt-2">Guardar observación</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            Historial de sesiones laborales
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Duración</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sesiones as $sesion): ?>
                        <?php
                        $inicio = new DateTime($sesion['start_time']);
                        $fin = $sesion['end_time'] ? new DateTime($sesion['end_time']) : null;
                        $duracion = $fin ? $inicio->diff($fin)->format('%H:%I:%S') : 'En curso';
                        ?>
                        <tr>
                            <td><?= date('Y-m-d', strtotime($sesion['start_time'])) ?></td>
                            <td><?= date('H:i:s', strtotime($sesion['start_time'])) ?></td>
                            <td><?= $sesion['end_time'] ? date('H:i:s', strtotime($sesion['end_time'])) : '---' ?></td>
                            <td><?= $duracion ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>