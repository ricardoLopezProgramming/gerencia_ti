<?php
if (isset($_POST['registrarUsuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol_id'];
    $imagen = $_FILES['imagen']['tmp_name'];
    $typeImagen = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $directorio = __DIR__ . "/../../../public/assets/image/";
    $publicPath = "/../../../public/assets/image/";

    if (!empty($_POST['usuario_id'])) {
        // ✅ ACTUALIZACIÓN
        $id = $_POST['usuario_id'];

        // Verificar duplicado
        $stmt = $conn->prepare("SELECT id FROM usuario WHERE correo = ? AND id != ?");
        $stmt->execute([$correo, $id]);
        if ($stmt->fetch()) {
            echo "<script>alert('Ya existe otro usuario con este correo');</script>";
        } else {
            $data = [
                'nombre' => $nombre,
                'correo' => $correo,
                'contraseña' => $contraseña,
                'rol_id' => $rol
            ];

            if (!empty($_FILES['imagen']['name'])) {
                $rutaRelativa = $publicPath . $id . "." . $typeImagen;
                $rutaCompleta = $directorio . $id . "." . $typeImagen;
                $data['imagen'] = $rutaRelativa;
                move_uploaded_file($imagen, $rutaCompleta);
            }

            $usuarioModel->updateById($id, $data);
            echo "<script>alert('Usuario actualizado correctamente');</script>";
            header('Location: /public/usuario/control');
            exit;
        }
    } else {
        // ✅ REGISTRO NUEVO
        $stmt = $conn->prepare("SELECT id FROM usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            echo "<script>alert('Este correo ya está registrado');</script>";
        } else {
            $stmt = $conn->prepare('INSERT INTO usuario (nombre, correo, contraseña, rol_id, imagen) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nombre, $correo, $contraseña, $rol, '']);

            $lastId = $conn->lastInsertId();
            $rutaRelativa = $publicPath . $lastId . "." . $typeImagen;
            $rutaCompleta = $directorio . $lastId . "." . $typeImagen;

            $stmt = $conn->prepare('UPDATE usuario SET imagen = ? WHERE id = ?');
            $stmt->execute([$rutaRelativa, $lastId]);

            if (move_uploaded_file($imagen, $rutaCompleta)) {
                echo "<script>alert('Usuario registrado correctamente');</script>";
            } else {
                echo "<script>alert('Error al subir la imagen');</script>";
            }

            header('Location: /public/usuario/control');
            exit;
        }
    }
}
?>

<!-- FORMULARIO HTML -->
<div class="bento-card bg-white d-flex align-items-center justify-content-center border border-secondary">
    <div class="p-4 w-100 h-100">
        <form method="post" class="h-100 d-flex flex-column justify-content-between" enctype="multipart/form-data">
            <div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= $usuarioActualizar['nombre'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="emailName" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="emailName" name="correo" required value="<?= $usuarioActualizar['correo'] ?? '' ?>">
                    <div id="emailHelp" class="form-text">Ingresa tu correo.</div>
                </div>

                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="inputPassword" name="contraseña" required value="<?= $usuarioActualizar['contraseña'] ?? '' ?>">
                    <div id="passwordHelp" class="form-text">Nunca compartas tu contraseña.</div>
                </div>

                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol</label>
                    <select name="rol_id" id="rol_id" class="form-select" required>
                        <?php foreach ($rolModel->getAll() as $rol): ?>
                            <option value="<?= $rol['id'] ?>" <?= (isset($usuarioActualizar['rol_id']) && $usuarioActualizar['rol_id'] == $rol['id']) ? 'selected' : '' ?>>
                                <?= $rol['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 d-flex align-items-center gap-3">
                    <img id="previewImage" src="<?= $usuarioActualizar['imagen'] ?? '/../../../public/assets/image/default-profile.svg' ?>" alt="Previsualización" width="60" height="60" class="rounded-circle border" style="object-fit: cover;">
                    <div class="flex-fill">
                        <label for="inputImage" class="form-label">Foto de perfil</label>
                        <input type="file" class="form-control" id="inputImage" name="imagen" accept="image/*" <?= isset($usuarioActualizar) ? '' : 'required' ?>>
                        <div id="imageHelp" class="form-text">Añadir foto de perfil.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <?php if (isset($usuarioActualizar)): ?>
                    <input type="hidden" name="usuario_id" value="<?= $usuarioActualizar['id'] ?>">
                    <button type="submit" class="btn btn-warning flex-fill" name="registrarUsuario">Actualizar</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-success flex-fill" name="registrarUsuario">Registrar</button>
                <?php endif; ?>
                <a href="/public/usuario/control" class="btn btn-danger flex-fill">Cancelar</a>
            </div>
        </form>
    </div>
</div>