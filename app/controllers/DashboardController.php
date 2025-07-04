<?php

class DashboardController extends Controller
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function index()
    {
        $userId = $_SESSION['id'];
        $role = $_SESSION['role']; // 'admin', 'project_manager', 'employee'

        $data = [];

        if ($role === 'admin') {
            $data['totalUsers'] = $this->getTotal('users');
            $data['activeProjects'] = $this->getCountSafe("
                SELECT COUNT(*) FROM projects 
                WHERE status_id = (SELECT id FROM project_status WHERE name = 'in progress')
            ");
            $data['pendingTickets'] = $this->getCountSafe("
                SELECT COUNT(*) FROM tickets 
                WHERE status_id = (SELECT id FROM ticket_status WHERE name = 'pending')
            ");
        } elseif ($role === 'project_manager') {
            $data['myProjects'] = $this->getCountSafe("
                SELECT COUNT(*) FROM projects 
                WHERE manager_id = :id
            ", ['id' => $userId]);

            $data['ticketsInMyProjects'] = $this->getCountSafe("
                SELECT COUNT(*) FROM tickets t
                JOIN projects p ON t.project_id = p.id
                WHERE p.manager_id = :id
            ", ['id' => $userId]);
        } elseif ($role === 'employee') {
            $data['assignedProjects'] = $this->getCountSafe("
                SELECT COUNT(*) FROM project_user 
                WHERE user_id = :id
            ", ['id' => $userId]);

            $data['assignedTickets'] = $this->getCountSafe("
                SELECT COUNT(*) FROM ticket_user 
                WHERE user_id = :id
            ", ['id' => $userId]);
        }

        $this->render('dashboard', 'dashboard', $data, 'site');
    }

    private function getTotal($table)
    {
        return $this->connection->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    }

    private function getCountSafe($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
