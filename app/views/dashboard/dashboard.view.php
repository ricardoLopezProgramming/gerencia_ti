<div class="container mt-4">
    <h3 class="mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h3>

    <?php if ($_SESSION['role'] == 'administrador'): ?>
        <!-- Administrador -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Usuarios Registrados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $totalUsuarios ?? 'N/A' ?></h5>
                        <p class="card-text">Total de usuarios activos en el sistema.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Proyectos en curso</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $proyectosEnCurso ?? 'N/A' ?></h5>
                        <p class="card-text">Proyectos que se están ejecutando actualmente.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Tickets Pendientes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $ticketsPendientes ?? 'N/A' ?></h5>
                        <p class="card-text">Tickets sin resolver.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            Puedes gestionar usuarios, roles, departamentos, bitácora y generar reportes detallados desde el panel lateral.
        </div>

    <?php elseif ($_SESSION['role'] == 'jefe de proyecto'): ?>
        <!-- Jefe de Proyecto -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">Proyectos a tu cargo</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $misProyectos ?? 'N/A' ?></h5>
                        <p class="card-text">Proyectos en los que eres el jefe asignado.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-warning mb-3">
                    <div class="card-header bg-warning text-dark">Tickets de tu equipo</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $ticketsDeMisProyectos ?? 'N/A' ?></h5>
                        <p class="card-text">Tickets generados en tus proyectos.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-secondary">
            Desde aquí puedes revisar las horas del equipo, controlar el avance de proyectos y resolver tickets.
        </div>

    <?php else: ?>
        <!-- Empleado -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-success mb-3">
                    <div class="card-header bg-primary text-white">Mis Proyectos</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $proyectosAsignados ?? 'N/A' ?></h5>
                        <p class="card-text">Proyectos en los que estás participando.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-danger mb-3">
                    <div class="card-header bg-danger text-white">Mis Tickets</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $ticketsAsignados ?? 'N/A' ?></h5>
                        <p class="card-text">Tickets que se te han asignado para resolver.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-light">
            Recuerda registrar tu jornada laboral y revisar tus tickets diariamente.
        </div>
    <?php endif; ?>
</div>