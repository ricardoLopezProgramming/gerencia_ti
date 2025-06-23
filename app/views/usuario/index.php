<style>
  

</style>

<?php
require_once __DIR__ . '/../../services/Database.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Rol.php';
$db = Database::getInstance();
$conn = $db->getConnection();
$usuarioModel = new Usuario($conn);
$rolModel = new Rol($conn);
?>



<div class="container">
    <div class="row">
        <h1>Usuarios</h1>
        <p>Esta es la página de gestión de usuarios.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <?php require_once __DIR__ . '/lista.php'; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <?php require_once __DIR__ . '/formulario.php'; ?>
        </div>
    </div>
</div>

<!-- Font Awesome (por si no lo tienes ya) -->
<!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->

<script>
    history.replaceState(null,null,location.pathname);
</script>