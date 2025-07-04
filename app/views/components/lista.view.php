<!-- Vista lista.view.php reutilizable -->
<div class="container">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="shadow-sm bento-card bg-white text-dark p-3 d-flex justify-content-between align-items-center border">
                <form action="<?= $action ?>" class="d-flex gap-2" method="GET" role="search">
                    <input class="form-control" type="search" name="search" placeholder="Buscar..." aria-label="Buscar">
                    <select class="form-select" name="categoriaSelect">
                        <?php if (!empty($dataset)): ?>
                            <?php foreach (array_keys($dataset[0]) as $key): ?>
                                <option value="<?= htmlspecialchars($key) ?>"><?= ucfirst(str_replace('_', ' ', $key)) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

                <div class="d-flex gap-2">
                    <a href="<?= $formularioUrl ?>" class="btn btn-outline-primary">Registrar</a>
                    <button class="btn btn-outline-secondary" onclick="location.reload()">Recargar</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="shadow-sm bento-card bg-white text-dark p-4 h-500px overflow-auto border">
                <table class="table table-hover">
                    <caption><?= $titulo ?? 'Listado' ?></caption>
                    <thead class="table-primary">
                        <tr>
                            <?php foreach (array_keys($dataset[0]) as $column): ?>
                                <th><?= ucfirst(str_replace('_', ' ', $column)) ?></th>
                            <?php endforeach ?>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataset as $row): ?>
                            <tr>
                                <?php foreach ($row as $key => $value): ?>
                                    <td>
                                        <?php
                                        // Detectar si es campo estado para badge
                                        if ($key === 'estado'):
                                            $estado = strtolower($value);
                                            $badgeClass = match ($estado) {
                                                'pendiente' => 'danger',
                                                'en proceso' => 'warning',
                                                'resuelto', 'terminado' => 'success',
                                                default => 'secondary'
                                            };
                                        ?>
                                            <span class="badge text-bg-<?= $badgeClass ?>">
                                                <?= htmlspecialchars($value) ?>
                                            </span>

                                        <?php elseif ($key === 'imagen' || preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $value)): ?>
                                            <img src="<?= htmlspecialchars($value ?: '/../../../public/assets/image/default-profile.svg') ?>"
                                                alt="Imagen" width="60" height="60" class="rounded-circle border" style="object-fit: cover;">

                                        <?php else: ?>
                                            <?= htmlspecialchars($value) ?>
                                        <?php endif; ?>
                                    </td>

                                <?php endforeach ?>
                                <!-- Acciones -->
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= $detalleUrl ?>?id=<?= $row['id'] ?>">Detalles</a></li>
                                            <li><a class="dropdown-item" href="/public/usuario/actualizacion?id=<?= $row['id'] ?>">Actualizar</a></li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($row['nombre'] ?? 'Registro') ?>">
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