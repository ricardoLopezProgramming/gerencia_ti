<?php
require_once __DIR__.'/../../app/services/Database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['message'])) {
    echo json_encode(['reply' => 'Mensaje no recibido']);
    exit;
}

$mensaje = strtolower(trim($data['message']));
$connection = Database::getInstance()->getConnection();

// Ejemplo simple de respuesta automática con MySQL
try {
    if (strpos($mensaje, 'cuántos proyectos') !== false) {
        $stmt = $connection->query("SELECT COUNT(*) as total FROM proyecto");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['reply' => "Hay un total de {$result['total']} proyectos registrados."]);
    } elseif (strpos($mensaje, 'usuarios') !== false) {
        $stmt = $connection->query("SELECT COUNT(*) as total FROM usuario");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['reply' => "Actualmente hay {$result['total']} usuarios registrados en el sistema."]);
    } else {
        echo json_encode(['reply' => "No estoy seguro cómo responder eso aún. Intenta con 'cuántos proyectos' o 'cuántos usuarios'."]);
    }
} catch (PDOException $e) {
    echo json_encode(['reply' => 'Error al consultar la base de datos.']);
}
