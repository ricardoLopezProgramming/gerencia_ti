<?php
require_once __DIR__ . '/../app/services/Database.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$message = strtolower(trim($input['message'] ?? ''));

$connection = Database::getInstance()->getConnection();
$reply = "Lo siento, no entendí tu pregunta.";

// Detectar intención y responder
try {
    if (strpos($message, 'proyectos') !== false && strpos($message, 'en curso') !== false) {
        $stmt = $connection->query("SELECT nombre FROM proyecto WHERE estado_id = (SELECT id FROM estado_proyecto WHERE nombre = 'en proceso')");
        $proyectos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $reply = "Proyectos en curso:\n- " . implode("\n- ", $proyectos);

    } elseif (strpos($message, 'tickets pendientes') !== false) {
        $stmt = $connection->query("SELECT nombre FROM ticket WHERE estado_id = (SELECT id FROM estado_ticket WHERE nombre = 'pendiente')");
        $tickets = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $reply = "Tickets pendientes:\n- " . implode("\n- ", $tickets);

    } elseif (strpos($message, 'usuarios') !== false && strpos($message, 'ti') !== false) {
        $stmt = $connection->query("SELECT nombre FROM usuario WHERE departamento_id = (SELECT id FROM departamento WHERE nombre LIKE '%ti%')");
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $reply = "Usuarios del departamento de TI:\n- " . implode("\n- ", $usuarios);
    }

} catch (PDOException $e) {
    $reply = "⚠️ Error al consultar la base de datos.";
}

echo json_encode(['reply' => $reply]);
