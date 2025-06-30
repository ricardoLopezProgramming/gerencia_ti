<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="bento-card bg-white border border-secondary">
                <div class="p-4">
                    <form method="post" action="/public/proyecto/<?= isset($proyectoEditar) ? 'actualizar' : 'registrar' ?>" class="d-flex flex-column gap-3">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del proyecto</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required
                                value="<?= isset($proyectoEditar['nombre']) ? htmlspecialchars($proyectoEditar['nombre']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?= isset($proyectoEditar['descripcion']) ? htmlspecialchars($proyectoEditar['descripcion']) : '' ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required
                                    value="<?= isset($proyectoEditar['fecha_inicio']) ? htmlspecialchars($proyectoEditar['fecha_inicio']) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required
                                    value="<?= isset($proyectoEditar['fecha_fin']) ? htmlspecialchars($proyectoEditar['fecha_fin']) : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado_id" class="form-label">Estado del proyecto</label>
                            <select name="estado_id" id="estado_id" class="form-select" required>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>" <?= isset($proyectoEditar['estado_id']) && $proyectoEditar['estado_id'] == $estado['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($estado['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jefe_id" class="form-label">Jefe de proyecto</label>
                            <select name="jefe_id" id="jefe_id" class="form-select" required>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= $usuario['id'] ?>" <?= isset($proyectoEditar['jefe_id']) && $proyectoEditar['jefe_id'] == $usuario['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['correo']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">Empleados asignados</label>
                            <select name="usuarios[]" id="usuarios" class="form-select" multiple>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= $usuario['id'] ?>" <?= in_array($usuario['id'], $usuariosAsignadosIds) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($usuario['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (!empty($proyectoEditar)) : ?>
                            <input type="hidden" name="id" value="<?= $proyectoEditar['id'] ?>">
                        <?php endif; ?>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-<?= isset($proyectoEditar) ? 'warning' : 'success' ?> flex-fill" name="registrarProyecto">
                                <?= isset($proyectoEditar) ? 'Actualizar' : 'Registrar' ?>
                            </button>
                            <a href="/public/proyecto/listar" class="btn btn-danger flex-fill">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>