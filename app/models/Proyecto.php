<?php
class Proyecto extends Orm
{
    public function __construct(PDO $connection, $id = "id")
    {
        parent::__construct($id, 'proyecto', $connection);
    }

    public function getAllWithEstadoAndBoss()
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.fecha_inicio, p.fecha_fin, ep.nombre AS estado, u.nombre AS autor FROM proyecto p INNER JOIN estado_proyecto ep ON p.estado_id = ep.id INNER JOIN usuario u ON p.jefe_id = u.id ORDER BY p.id ASC";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En Usuario.php o Proyecto.php
    public function getUsuariosAsignados($proyectoId)
    {
        $stmt = $this->connection->prepare("
        SELECT u.* FROM usuario u
        INNER JOIN proyecto_usuario pu ON u.id = pu.usuario_id
        WHERE pu.proyecto_id = :proyecto_id
    ");
        $stmt->execute(['proyecto_id' => $proyectoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO proyecto (nombre, descripcion, estado_id, jefe_id, fecha_inicio, fecha_fin)
            VALUES (:nombre, :descripcion, :estado_id, :jefe_id, :fecha_inicio, :fecha_fin)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':estado_id' => $data['estado_id'],
            ':jefe_id' => $data['jefe_id'],
            ':fecha_inicio' => $data['fecha_inicio'],
            ':fecha_fin' => $data['fecha_fin'],
        ]);
        return (int)$this->connection->lastInsertId();
    }

    public function asignarUsuario(int $proyectoId, int $usuarioId): void
    {
        $sql = "INSERT IGNORE INTO proyecto_usuario (proyecto_id, usuario_id) VALUES (:proyecto_id, :usuario_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':proyecto_id' => $proyectoId,
            ':usuario_id' => $usuarioId,
        ]);
    }

    public function eliminarUsuariosAsignados(int $proyectoId): void
{
    $stmt = $this->connection->prepare("DELETE FROM proyecto_usuario WHERE proyecto_id = :proyecto_id");
    $stmt->execute([':proyecto_id' => $proyectoId]);
}

public function updateProyecto(array $data)
{
    $sql = "UPDATE proyecto SET
        nombre = :nombre,
        descripcion = :descripcion,
        estado_id = :estado_id,
        jefe_id = :jefe_id,
        fecha_inicio = :fecha_inicio,
        fecha_fin = :fecha_fin
        WHERE id = :id";

    $stmt = $this->connection->prepare($sql);
    return $stmt->execute($data);
}

}
