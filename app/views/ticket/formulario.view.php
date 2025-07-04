<div class="container py-4">
    <div class="d-flex justify-content-center">
        <div class="bento-card bg-white border p-4" style="max-width: 720px; width: 100%;">
            <form method="POST" action="<?= isset($ticket) ? '/public/ticket/actualizar' : '/public/ticket/registrar' ?>" class="d-flex flex-column gap-3">
                <h4><?= isset($ticket) ? 'Update Ticket' : 'Register New Ticket' ?></h4>

                <?php if (isset($ticket)): ?>
                    <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                <?php endif; ?>

                <!-- Name -->
                <div>
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($ticket['name'] ?? '') ?>">
                </div>

                <!-- Description -->
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($ticket['description'] ?? '') ?></textarea>
                </div>

                <!-- Status -->
                <div>
                    <label class="form-label">Status</label>
                    <select <?= ($_SESSION['role'] != 'empleado') ? 'disabled' : '' ?> name="status_id" class="form-select" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['id'] ?>" <?= (isset($ticket) && $ticket['status_id'] == $status['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($_SESSION['role'] != 'empleado'): ?>
                        <!-- Campo oculto para enviar el status_id actual, ya que el select disabled no se envía -->
                        <input type="hidden" name="status_id" value="<?= isset($ticket) ? $ticket['status_id'] : $statuses[0]['id'] ?>">
                    <?php endif; ?>
                </div>

                <!-- Project -->

                <div>
                    <label for="project" class="form-label">Project</label>
                    <input type="text" id="project" class="form-control" value="<?= htmlspecialchars($project['name']) ?>" readonly>
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                </div>

                <!-- Assign User -->
                <div>
                    <label for="users" class="form-label">Assigned Employees</label>
                    <select name="users[]" id="users" class="form-select" multiple>
                        <?php foreach ($availableUsers as $user): ?>
                            <option value="<?= $user['id'] ?>"
                                <?= in_array($user['id'], $usuariosAsignadosIds) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name'] . ' (' . $user['email'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <small class="form-text text-muted">You can select multiple employees.</small>
                </div>
                <div class="alert alert-warning">
                    No available employees for this project or all are assigned to active tickets.
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= isset($ticket) ? 'warning' : 'success' ?> flex-fill">
                        <?= isset($ticket) ? 'Update' : 'Register' ?>
                    </button>
                    <a href="/public/proyecto/detalles?id=<?= $project['id'] ?>" class="btn btn-danger flex-fill">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>