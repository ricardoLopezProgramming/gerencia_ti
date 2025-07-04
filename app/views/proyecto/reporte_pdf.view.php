<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px;}
        th, td { border: 1px solid #aaa; padding: 5px; text-align: left;}
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Reporte de Proyectos y Tickets</h1>
    <?php foreach ($projects as $project): ?>
        <h2>Proyecto: <?= htmlspecialchars($project['name']) ?></h2>
        <p><strong>Estado:</strong> <?= htmlspecialchars($project['status']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($project['description']) ?></p>
        <p><strong>Usuarios asignados:</strong>
            <?php foreach ($project['users'] as $user): ?>
                <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>),
            <?php endforeach; ?>
        </p>
        <h3>Tickets</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Descripción</th>
            </tr>
            <?php foreach ($project['tickets'] as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['name']) ?></td>
                    <td><?= htmlspecialchars($ticket['status']) ?></td>
                    <td><?= htmlspecialchars($ticket['description']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>