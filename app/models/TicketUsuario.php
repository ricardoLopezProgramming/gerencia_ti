<?php
class TicketUsuario extends ORM
{
    public function __construct(PDO $connection)
    {
        parent::__construct('id', 'ticket_users', $connection);
    }

    /**
     * Retorna los usuarios asignados al proyecto que:
     * - No estén ya en un ticket del mismo proyecto con estado pendiente o en proceso.
     * - Sean empleados.
     */
    public function getAvailableUsersByProject(int $projectId): array
    {
        $sql = "
            SELECT DISTINCT u.*
            FROM users u
            INNER JOIN project_users pu ON u.id = pu.user_id
            INNER JOIN roles r ON u.role_id = r.id
            WHERE pu.project_id = :project_id_1
              AND LOWER(r.name) = 'empleado'
              AND u.id NOT IN (
                  SELECT tu.user_id
                  FROM ticket_users tu
                  INNER JOIN tickets t ON tu.ticket_id = t.id
                  INNER JOIN ticket_statuses ts ON t.status_id = ts.id
                  WHERE t.project_id = :project_id_2
                    AND LOWER(ts.name) IN ('pendiente', 'en proceso')
              )
        ";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'project_id_1' => $projectId,
            'project_id_2' => $projectId
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
