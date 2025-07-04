<?php

class Ticket extends ORM
{
    public function __construct($connection, $id = "id")
    {
        parent::__construct($id, 'tickets', $connection);
    }

    public function getAllWithStatusAndProject()
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.description,
                ts.name AS status,
                p.name AS project,
                t.created_at
            FROM tickets t
            INNER JOIN ticket_statuses ts ON t.status_id = ts.id
            INNER JOIN projects p ON t.project_id = p.id
            ORDER BY t.id ASC
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuariosAsignados($ticket_id)
    {
        $sql = "
            SELECT u.* 
            FROM users u
            INNER JOIN ticket_users tu ON u.id = tu.user_id
            WHERE tu.ticket_id = :ticket_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['ticket_id' => $ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithStatusAndProjectById($project_id)
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.description,
                ts.name AS status,
                p.name AS project,
                t.created_at
            FROM tickets t
            INNER JOIN ticket_statuses ts ON t.status_id = ts.id
            INNER JOIN projects p ON t.project_id = p.id
            WHERE {$this->id} = :project_id
            ORDER BY t.id ASC
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':project_id' => $project_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProjectByTicket($ticket_id)
    {
        $sql = "
            SELECT 
                t.*,
                ts.name AS status,
                p.name AS project
            FROM tickets t
            INNER JOIN ticket_statuses ts ON t.status_id = ts.id
            INNER JOIN projects p ON t.project_id = p.id
            WHERE t.id = :ticket_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':ticket_id' => $ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAndGetId(array $data): int
    {
        $sql = "
            INSERT INTO tickets (name, description, status_id, project_id)
            VALUES (:name, :description, :status_id, :project_id)
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':status_id' => $data['status_id'],
            ':project_id' => $data['project_id'],
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function getByIdWithExtras(int $id)
    {
        $sql = "
            SELECT 
                t.id,
                t.name,
                t.description,
                ts.id AS status_id,
                ts.name AS status,
                p.id AS project_id,
                p.name AS project,
                u.id AS user_id,
                u.name AS user,
                t.created_at
            FROM tickets t
            INNER JOIN ticket_statuses ts ON t.status_id = ts.id
            INNER JOIN projects p ON t.project_id = p.id
            LEFT JOIN ticket_users tu ON t.id = tu.ticket_id
            LEFT JOIN users u ON tu.user_id = u.id
            WHERE t.id = :id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchWithExtras(string $field, string $value)
    {
        // Evita SQL Injection en campos
        $allowedFields = ['id', 'name', 'description'];
        if (!in_array($field, $allowedFields)) $field = 'id';

        $sql = "
            SELECT 
                t.id,
                t.name,
                t.description,
                ts.name AS status,
                p.name AS project,
                t.created_at
            FROM tickets t
            INNER JOIN ticket_statuses ts ON t.status_id = ts.id
            INNER JOIN projects p ON t.project_id = p.id
            WHERE t.{$field} LIKE :value
            ORDER BY t.id ASC
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':value' => "%$value%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
