<?php
class Proyecto extends ORM
{
    public function __construct(PDO $connection, $id = "id")
    {
        parent::__construct($id, 'projects', $connection);
    }

    public function getAllWithEstadoAndBoss()
    {
        $sql = "
            SELECT p.*, ps.name AS status, u.name AS manager 
            FROM projects p
            INNER JOIN project_statuses ps ON p.status_id = ps.id
            INNER JOIN users u ON p.manager_id = u.id
            ORDER BY p.id ASC
        ";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvalibleProjects()
    {
        $sql = "
            SELECT p.*, ps.name AS status, u.name AS manager 
            FROM projects p
            INNER JOIN project_statuses ps ON p.status_id = ps.id
            INNER JOIN users u ON p.manager_id = u.id
            WHERE ps.name != 'terminado'
            ORDER BY p.id ASC
        ";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getManagerProjects($manager_id)
    {
        $sql = "UPDATE projects SET p.status_id = (SELECT id FROM )";
        $sql = "
            SELECT p.*, ps.name AS status, u.name AS manager 
            FROM projects p
            INNER JOIN project_statuses ps ON p.status_id = ps.id
            INNER JOIN users u ON p.manager_id = u.id
            WHERE p.manager_id = :manager_id
            ORDER BY p.id ASC
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['manager_id' => $manager_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ PROYECTOS ASIGNADOS A UN EMPLEADO
    public function getEmployeeProjects($user_id)
    {
        $sql = "
            SELECT p.*, ps.name AS status, u.name AS manager
            FROM project_users pu
            INNER JOIN projects p ON pu.project_id = p.id
            INNER JOIN project_statuses ps ON p.status_id = ps.id
            INNER JOIN users u ON p.manager_id = u.id
            WHERE pu.user_id = :user_id
            ORDER BY p.id ASC
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuariosAsignados($project_id)
    {
        $sql = "
            SELECT u.* 
            FROM users u
            INNER JOIN project_users pu ON u.id = pu.user_id
            WHERE pu.project_id = :project_id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['project_id' => $project_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO projects (name, description, status_id, manager_id, start_date, end_date) VALUES (:name, :description, :status_id, :manager_id, :start_date, :end_date)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':name' => $data['nombre'],
            ':description' => $data['descripcion'],
            ':status_id' => $data['estado_id'],
            ':manager_id' => $data['jefe_id'],
            ':start_date' => $data['fecha_inicio'],
            ':end_date' => $data['fecha_fin'],
        ]);
        return (int)$this->connection->lastInsertId();
    }

    public function asignarUsuario(int $projectId, int $userId): void
    {
        $sql = "INSERT IGNORE INTO project_users (project_id, user_id) VALUES (:project_id, :user_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':project_id' => $projectId,
            ':user_id' => $userId,
        ]);
    }

    public function eliminarUsuariosAsignados(int $projectId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM project_users WHERE project_id = :project_id");
        $stmt->execute(['project_id' => $projectId]);
    }

    public function updateProyecto(array $data)
    {
        $sql = "
            UPDATE projects SET
                name = :name,
                description = :description,
                status_id = :status_id,
                manager_id = :manager_id,
                start_date = :start_date,
                end_date = :end_date
            WHERE id = :id
        ";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    public function getById($id)
    {
        $stmt = $this->connection->prepare("
            SELECT p.*, ps.name AS status, u.name AS manager 
            FROM projects p
            INNER JOIN project_statuses ps ON p.status_id = ps.id
            INNER JOIN users u ON p.manager_id = u.id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
