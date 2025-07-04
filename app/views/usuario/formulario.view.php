<div class="container py-4">
    <div class="d-flex justify-content-center">
        <div class="bento-card bg-white border border-secondary p-4" style="max-width: 600px; width: 100%;">
            <form action="/public/usuario/<?= isset($user['id']) ? 'actualizar' : 'registrar' ?>" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">

                <!-- Hidden ID for edit -->
                <?php if (!empty($user['id'])): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                <?php endif; ?>

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" required
                        value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" name="email" id="email" class="form-control" required
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                    <div class="form-text">Ingresa un correo electrónico válido.</div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required
                        value="<?= htmlspecialchars($user['password'] ?? '') ?>">
                    <div class="form-text">Nunca compartas tu contraseña con nadie.</div>
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="form-label">Departamento</label>
                    <select name="department_id" id="department_id" class="form-select" required>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['id'] ?>"
                                <?= (isset($user['department_id']) && $user['department_id'] == $department['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($department['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="form-label">Rol</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"
                                <?= (isset($user['role_id']) && $user['role_id'] == $role['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Avatar -->
                <div class="d-flex align-items-center gap-3">
                    <img id="previewImage" src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : '/../../../public/assets/image/default-profile.svg' ?>"
                        alt="Previsualización" width="60" height="60" class="rounded-circle border" style="object-fit: cover;">
                    <div class="flex-fill">
                        <label for="avatar" class="form-label">Foto de perfil</label>
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" <?= isset($user) ? '' : 'required' ?>>
                        <div class="form-text">Selecciona una imagen de perfil.</div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= isset($user['id']) ? 'warning' : 'success' ?> flex-fill">
                        <?= isset($user['id']) ? 'Actualizar' : 'Registrar' ?>
                    </button>
                    <a href="/public/usuario/listar" class="btn btn-danger flex-fill">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
</div>
