<div class="container">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="bento-card bg-white d-flex align-items-center justify-content-center border border-secondary">
                <div class="p-4 w-100 h-100">
                    <form action="/public/usuario/registrar" method="post" class="h-100 d-flex flex-column justify-content-between" enctype="multipart/form-data">
                        <div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= $usuario['nombre'] ?? '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="emailName" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="emailName" name="correo" required value="<?= $usuario['correo'] ?? '' ?>">
                                <div id="emailHelp" class="form-text">Ingresa tu correo.</div>
                            </div>

                            <div class="mb-3">
                                <label for="inputPassword" class="form-label">password</label>
                                <input type="password" class="form-control" id="inputPassword" name="password" required value="<?= $usuario['password'] ?? '' ?>">
                                <div id="passwordHelp" class="form-text">Nunca compartas tu password.</div>
                            </div>

                            <div class="mb-3">
                                <label for="rol_id" class="form-label">Rol</label>
                                <select name="rol_id" id="rol_id" class="form-select" required>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value=<?= $rol['id'] ?>><?= $rol['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3 d-flex align-items-center gap-3">
                                <img id="previewImage" src="<?= $usuario['imagen'] ?? '/../../../public/assets/image/default-profile.svg' ?>" alt="Previsualización" width="60" height="60" class="rounded-circle border" style="object-fit: cover;">
                                <div class="flex-fill">
                                    <label for="inputImage" class="form-label">Foto de perfil</label>
                                    <input type="file" class="form-control" id="inputImage" name="imagen" accept="image/*" <?= isset($usuario) ? '' : 'required' ?>>
                                    <div id="imageHelp" class="form-text">Añadir foto de perfil.</div>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2">


                            <button type="submit" class="btn btn-success flex-fill" name="registrarUsuario"><?= isset($usuario) ? 'Actualizar' : 'Registrar' ?></button>
                            <a href="/public/usuario/listar" class="btn btn-danger flex-fill">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>