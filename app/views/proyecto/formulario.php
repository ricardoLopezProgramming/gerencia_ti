<?php
// Suponiendo que vas a editar también el proyecto

if (isset($_POST['registrarProyecto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $usuarios = $_POST['usuarios'];

    if (!empty($_POST['proyecto_id'])) {
        // ✅ ACTUALIZACIÓN
        $id = $_POST['proyecto_id'];
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];
        $proyectoModel->updateById($id, $data);
        $proyectoModel->asignarUsuarios($id, $usuarios);

        echo "<script>alert('Proyecto actualizado correctamente');</script>";
        header('Location: /public/?page=proyectos');
        exit;
    } else {
        // ✅ REGISTRO NUEVO
        $nuevoId = $proyectoModel->crearProyecto($nombre, $descripcion, $fechaInicio, $fechaFin, $usuarios);
        if ($nuevoId) {
            echo "<script>alert('Proyecto registrado correctamente');</script>";
            header('Location: /public/?page=proyectos');
            exit;
        } else {
            echo "<script>alert('Error al registrar proyecto');</script>";
        }
    }
}
?>

<div class="bento-card bg-white d-flex align-items-center justify-content-center border border-secondary">
    <div class="p-4 w-100 h-100">
        <form method="post" class="h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del proyecto</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= $proyectoEditar['nombre'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?= $proyectoEditar['descripcion'] ?? '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?= $proyectoEditar['fecha_inicio'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required value="<?= $proyectoEditar['fecha_fin'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="usuarios" class="form-label">Asignar empleados</label>
                    <select name="usuarios[]" id="usuarios" class="form-select" multiple required>
                        <?php foreach ($usuarioModel->getAllUsuarios() as $usuario): ?>
                            <option value="<?= $usuario['id'] ?>"
                                <?= isset($proyectoEditar['usuarios']) && in_array($usuario['id'], $proyectoEditar['usuarios']) ? 'selected' : '' ?>>
                                <?= $usuario['nombre'] ?> (<?= $usuario['correo'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Usa Ctrl o Cmd para seleccionar múltiples empleados</small>
                </div>
            </div>

            <div class="d-flex gap-2">
                <?php if (isset($proyectoEditar)): ?>
                    <input type="hidden" name="proyecto_id" value="<?= $proyectoEditar['id'] ?>">
                    <button type="submit" class="btn btn-warning flex-fill" name="registrarProyecto">Actualizar</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-success flex-fill" name="registrarProyecto">Registrar</button>
                <?php endif; ?>
                <a href="/public/?page=proyectos" class="btn btn-danger flex-fill">Cancelar</a>
            </div>
        </form>
    </div>
</div>
