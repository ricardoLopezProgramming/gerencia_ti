<?php
class UsuarioProjecto extends ORM
{
    public function __construct($connection, $id = 'id')
    {
        parent::__construct('id', 'project_users', $connection);
    }

    public function getAvailableUsers()
    {
        $sql = "
            SELECT u.*
            FROM users u
            INNER JOIN roles r ON u.role_id = r.id
            WHERE r.name = 'empleado'
            AND u.id NOT IN (
                SELECT DISTINCT pu.user_id
                FROM project_users pu
                INNER JOIN projects p ON pu.project_id = p.id
                INNER JOIN project_statuses ps ON p.status_id = ps.id
                WHERE LOWER(ps.name) != 'terminado'
            )
        ";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

    public function getUsersByProjectId($project_id)
    {
        $sql = "
        SELECT u.*
        FROM project_users pu
        INNER JOIN users u ON pu.user_id = u.id
        WHERE pu.project_id = :project_id
    ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function insertProjectUser(int $projectId, int $userId): void
    {
        $sql = "INSERT INTO project_users (project_id, user_id) VALUES (:project_id, :user_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':project_id' => $projectId,
            ':user_id' => $userId,
        ]);
    }

}
