<?php
class Ticket extends ORM
{
    public function __construct($connection)
    {
        parent::__construct('id', 'ticket', $connection);
    }

    public function getAllWithEstadoYProyecto()
    {
        $sql = "
            SELECT t.id, t.nombre, t.descripcion, t.fecha_creacion, et.nombre AS estado, p.nombre AS proyecto
            FROM ticket t
            INNER JOIN estado_ticket et ON t.estado_id = et.id
            INNER JOIN proyecto p ON t.proyecto_id = p.id
            ORDER BY t.id ASC
        ";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
