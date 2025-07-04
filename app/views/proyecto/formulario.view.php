<div class="container py-4">
    <div class="d-flex justify-content-center">
        <div class="bento-card bg-white border border-secondary p-4" style="max-width: 720px; width: 100%;">
            <form method="post" action="/public/proyecto/<?= isset($proyectoEditar) ? 'actualizar' : 'registrar' ?>" class="d-flex flex-column gap-3">

                <!-- Project Name -->
                <div>
                    <label for="name" class="form-label">Project Name</label>
                    <input type="text" name="name" id="name" class="form-control" required
                        value="<?= htmlspecialchars($proyectoEditar['name'] ?? '') ?>">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($proyectoEditar['description'] ?? '') ?></textarea>
                </div>

                <!-- Dates -->
                <div class="row">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required
                            value="<?= htmlspecialchars($proyectoEditar['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required
                            value="<?= htmlspecialchars($proyectoEditar['end_date'] ?? '') ?>">
                    </div>
                </div>

                <!-- Project Status -->
                <div>
                    <label for="status_id" class="form-label">Project Status</label>
                    <select name="status_id" id="status_id" class="form-select" required>
                        <?php foreach ($project_statuses as $status): ?>
                            <option value="<?= $status['id'] ?>"
                                <?= isset($proyectoEditar['status_id']) && $proyectoEditar['status_id'] == $status['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Project Manager -->
                <div>
                    <label for="manager" class="form-label">Project Manager</label>
                    <input type="text" id="manager" class="form-control" value="<?= htmlspecialchars($manager['name']) ?> (<?= htmlspecialchars($manager['email']) ?>)" readonly>
                    <input type="hidden" name="manager_id" value="<?= $manager['id'] ?>">
                </div>

                <!-- Assigned Employees -->
                <div>
                    <label for="users" class="form-label">Assigned Employees</label>
                    <select name="users[]" id="users" class="form-select" multiple>
                        <?php foreach ($usuarios as $user): ?>
                            <option value="<?= $user['id'] ?>"
                                <?= in_array($user['id'], $usuariosAsignadosIds ?? []) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name']) ?>
                                (<?= htmlspecialchars($user['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <small class="form-text text-muted">You can select multiple employees.</small>
                </div>

                <!-- Hidden ID -->
                <?php if (!empty($proyectoEditar)) : ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($proyectoEditar['id']) ?>">
                <?php endif; ?>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= isset($proyectoEditar) ? 'warning' : 'success' ?> flex-fill">
                        <?= isset($proyectoEditar) ? 'Update' : 'Register' ?>
                    </button>
                    <a href="/public/proyecto/listar" class="btn btn-danger flex-fill">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>