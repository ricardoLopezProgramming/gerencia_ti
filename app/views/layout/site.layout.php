<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/styles.css">
    <script>
        var URL_PATH = '<?= URL_PATH ?>';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid p-0 h-100">
        <div class="d-flex">
            <nav class="sidebar">
                <div class="p-4">
                    <h4 class="logo-text fw-bold mb-0">MyD TareoApp</h4>
                    <p class="text-muted small hide-on-collapse">Dashboard</p>
                </div>

                <div class="nav">
                    <!-- Visible para todos -->
                    <a href="/public/dashboard/index" class="sidebar-link active">
                        <i class="fas fa-home me-3"></i>
                        <span class="hide-on-collapse">Dashboard</span>
                    </a>

                        <a href="/public/horas/index" class="sidebar-link">
                            <i class="fa-solid fa-user-clock me-3"></i>
                            <span class="hide-on-collapse">Horas</span>
                        </a>

                    <!-- Visible para todos -->
                    <a href="/public/proyecto/listar" class="sidebar-link">
                        <i class="fa-solid fa-diagram-project me-3"></i>
                        <span class="hide-on-collapse">Proyectos</span>
                    </a>

                    <!-- Visible para todos -->
                    <a href="/public/ticket/listar" class="sidebar-link">
                        <i class="fa-solid fa-ticket-simple me-3"></i>
                        <span class="hide-on-collapse">Tickets</span>
                    </a>

                    <!-- Solo Administrador -->
                    <?php if ($_SESSION['role'] == 'administrador'): ?>
                        <a href="/public/usuario/listar" class="sidebar-link">
                            <i class="fas fa-users me-3"></i>
                            <span class="hide-on-collapse">Usuarios</span>
                        </a>

                        <a href="/public/rol/listar" class="sidebar-link">
                            <i class="fa-solid fa-layer-group me-3"></i>
                            <span class="hide-on-collapse">Roles</span>
                        </a>

                        <a href="/public/departamento/listar" class="sidebar-link">
                            <i class="fa-solid fa-building-user me-3"></i>
                            <span class="hide-on-collapse">Departamentos</span>
                        </a>
                    <?php endif; ?>

                    <!-- Jefe de Proyecto y Administrador -->
                    <?php if ($_SESSION['role'] == 'jefe de proyecto' || $_SESSION['role'] == 'administrador'): ?>
                        <a href="/public/reporte/listar" class="sidebar-link">
                            <i class="fas fa-chart-line me-3"></i>
                            <span class="hide-on-collapse">Reportes</span>
                        </a>
                    <?php endif; ?>

                    <!-- Solo Administrador -->
                    <?php if ($_SESSION['role'] == 'administrador'): ?>
                        <a href="/public/bitacora/listar" class="sidebar-link">
                            <i class="fas fa-clipboard-list me-3"></i>
                            <span class="hide-on-collapse">Bitácora</span>
                        </a>
                    <?php endif; ?>

                    <a href="/public/signin/signout" class="sidebar-link">
                        <i class="fas fa-sign-out-alt me-3"></i>
                        <span class="hide-on-collapse">Cerrar sesión</span>
                    </a>
                </div>
                <!-- imagenes random de perfil https://randomuser.me/api/portraits/women/70.jpg -->
                <div class="profile-section">
                    <div class="d-flex align-items-center">
                        <img src="<?= $_SESSION['avatar'] != '' ? $_SESSION['avatar'] : '/../../../public/assets/image/default-profile.svg' ?>" style="height:60px" class="rounded-circle" alt="Profile">
                        <div class="profile-info">
                            <h6 class="mb-0"><?= htmlspecialchars($_SESSION['name']) ?></h6>
                            <small><?= $_SESSION['role'] == 'empleado' ? 'Empleado' : ($_SESSION['role'] == 'jefe de proyecto' ? 'Jefe de proyecto' : 'Administrador') ?></small>
                        </div>
                    </div>
                </div>
            </nav>
            <main class="main-content">
                <!-- Encabezado del módulo -->
                <div class="bg-primary text-light d-flex justify-content-between align-items-center px-4 py-2">
                    <h5 class="mb-0">
                        <?= $module_title ?? 'Bienvenido a TareoApp' ?>
                    </h5>
                    <div class="display-5" id="reloj"></div>
                </div>

                <!-- Contenido principal -->
                <div class="bg-light p-4" style="font-size: .9rem;">
                    <?= $content ?>
                </div>
                    
                <!-- Footer corporativo -->
                <div class="bg-dark text-light text-center p-2">
                    © <?= date('Y') ?> MyD Consultores - Todos los derechos reservados
                </div>
            </main>

        </div>
    </div>

    <script type="module" src="<?= URL_PATH ?>/assets/js/index.mjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>