<style>
    h1 {
        text-align: center;
        color: #1d3557;
    }

    h2 {
        color: #222;
        margin-top: 32px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }

    th,
    td {
        border: 1px solid #888;
        padding: 6px 8px;
    }

    th {
        background-color: #e6f3fa;
    }

    .section {
        margin-bottom: 24px;
    }

    .usuarios {
        font-size: 11px;
        color: #444;
    }
</style>

<div class="shadow-sm bento-card text-dark p-4 overflow-auto border border-secondary" style="height: 85dvh;">
    <h1>Reporte de Proyectos</h1>

    <?php if (empty($projects)): ?>
        <p>No existen proyectos registrados.</p>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <div class="section">
                <h2><?= htmlspecialchars($project['name']) ?></h2>
                <p><strong>Estado:</strong> <?= htmlspecialchars($project['status'] ?? 'Sin estado') ?></p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($project['description']) ?></p>
                <p><strong>Usuarios asignados:</strong>
                    <span class="usuarios">
                        <?php
                        if (!empty($project['users'])) {
                            $nombres = [];
                            foreach ($project['users'] as $user) {
                                $nombres[] = htmlspecialchars($user['name']);
                            }
                            echo implode(', ', $nombres);
                        } else {
                            echo 'Ninguno';
                        }
                        ?>
                    </span>
                </p>

                <h3>Tickets</h3>
                <?php if (empty($project['tickets'])): ?>
                    <p>No hay tickets asignados a este proyecto.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Descripción</th>
                                <th>Usuarios asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($project['tickets'] as $ticket): ?>
                                <tr>
                                    <td><?= htmlspecialchars($ticket['name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($ticket['status'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($ticket['description'] ?? '') ?></td>
                                    <td>
                                        <?php
                                        if (!empty($ticket['user'])) {
                                            echo htmlspecialchars($ticket['user']['name']);
                                        } elseif (!empty($ticket['users'])) {
                                            $nombres = [];
                                            foreach ($ticket['users'] as $user) {
                                                $nombres[] = htmlspecialchars($user['name']);
                                            }
                                            echo implode(', ', $nombres);
                                        } else {
                                            echo 'Sin asignar';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>