<?php
require_once(__DIR__ . '/../models/Ticket.php');
require_once(__DIR__ . '/../models/TicketUsuario.php');
require_once(__DIR__ . '/../models/Proyecto.php');
require_once(__DIR__ . '/../models/EstadoTicket.php');

class TicketController extends Controller
{
    private PDO $connection;
    private $ticketModel;
    private $ticketUserModel;
    private $proyectoModel;
    private $estadoModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->ticketModel = new Ticket($connection);
        $this->ticketUserModel = new TicketUsuario($connection);
        $this->proyectoModel = new Proyecto($connection);
        $this->estadoModel = new EstadoTicket($connection);
    }

    public function listar()
    {
        $tickets = $this->ticketModel->getAllWithStatusAndProject();
        $this->render(
            'ticket',
            'lista',
            ['tickets' => $tickets],
            'site'
        );
    }


    public function registro()
    {

        $ticket = isset($_GET['id']) ? $this->ticketModel->getById($_GET['id'])[0] : null;
        $statuses = $this->estadoModel->getAll();
        $project = $this->proyectoModel->getById($_GET['project_id'])[0]; // o similar

        $projectId = $_GET['project_id'] ?? $ticket['project_id'] ?? null;
        $availableUsers = [];
        $availableUsers = $this->ticketUserModel->getAvailableUsersByProject($_GET['project_id']);

        $this->render(
            'ticket',
            'formulario',
            [
                'ticket' => $ticket,
                'statuses' => $statuses,
                'project' => $project,
                'availableUsers' => $availableUsers,
                'usuariosAsignadosIds' => [],
            ],
            'site'
        );
    }

    public function detalles()
    {
        $id = $_GET['id'];
        $ticket = $this->ticketModel->getProjectByTicket($id)[0];
        $users = $this->ticketUserModel->getAvailableUsersByProject($ticket['project_id']);
        // $project = $this->proyectoModel->getById($tickets[0]['project_id'])[0];
        $this->render(
            'ticket',
            'detalles',
            [
                'ticket' => $ticket,
                'users' => $users,
            ],
            'site'
        );
    }

    public function actualizacion()
    {
        if (!isset($_GET['id'])) {
            die("Error: ID del ticket no definido.");
        }

        // 1. Obtener el ticket a editar
        $ticket = $this->ticketModel->getById($_GET['id'])[0] ?? null;
        if (!$ticket) {
            die("Error: Ticket no encontrado.");
        }

        // 2. Obtener todos los estados del ticket
        $statuses = $this->estadoModel->getAll();

        // 3. Obtener el proyecto asociado
        $project = $this->proyectoModel->getById($ticket['project_id'])[0] ?? null;
        if (!$project) {
            die("Error: Proyecto asociado no encontrado.");
        }

        // 4. Usuarios asignados actualmente al ticket
        $usuariosAsignados = $this->ticketModel->getUsuariosAsignados($ticket['id']); // array de users

        // 5. Usuarios disponibles para asignar (según tu lógica actual)
        $usuariosDisponibles = $this->ticketUserModel->getAvailableUsersByProject($ticket['project_id']); // array de users

        // 6. Combinar ambos arrays sin duplicados (por id)
        $usuariosCombinados = [];
        foreach ($usuariosAsignados as $ua) {
            $usuariosCombinados[$ua['id']] = $ua;
        }
        foreach ($usuariosDisponibles as $ud) {
            $usuariosCombinados[$ud['id']] = $ud;
        }
        $usuariosCombinados = array_values($usuariosCombinados);

        // 7. Solo los IDs de los asignados (para marcar en el select multiple)
        $usuariosAsignadosIds = array_column($usuariosAsignados, 'id');

        // 8. Renderizar formulario de edición de ticket
        $this->render(
            'ticket',
            'formulario',
            [
                'ticket' => $ticket,
                'statuses' => $statuses,
                'project' => $project,
                'availableUsers' => $usuariosCombinados,
                'usuariosAsignadosIds' => $usuariosAsignadosIds
            ],
            'site'
        );
    }


    public function registrar()
    {
        $data = [
            'name'        => $_POST['name'],
            'description' => $_POST['description'],
            'status_id'   => $_POST['status_id'],
            'project_id'   => $_POST['project_id'],
        ];
        $ticketId = $this->ticketModel->insert($data);

        // Asignar usuarios al proyecto (si hay)
        if (!empty($_POST['users'])) {
            foreach ($_POST['users'] as $usuarioId) {
                $this->ticketUserModel->insert([
                    'ticket_id' => (int) $ticketId,
                    'user_id'   => (int) $usuarioId
                ]);
            }
            $proyectoModel = new Proyecto($this->connection);
            $proyectoModel->updateById(
                $data['project_id'],
                [
                    'status_id' => 2,
                ]
            );
        }

        header('Location: /public/ticket/listar');
        exit;
    }

    public function actualizar()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /public/ticket/listar');
            exit;
        }

        $data = [
            'name'        => $_POST['name'],
            'description' => $_POST['description'],
            'status_id'   => $_POST['status_id'],
            'project_id'  => $_POST['project_id'],
        ];

        $this->ticketModel->updateById($id, $data);

        if (!empty($_POST['users'])) {
            foreach ($_POST['users'] as $usuarioId) {
                $this->ticketUserModel->insert([
                    'ticket_id' => (int) $id,
                    'user_id'   => (int) $usuarioId
                ]);
            }
        }

        header('Location: /public/ticket/listar');
        exit;
    }


    public function eliminar()
    {
        $id = $_GET['id'];
        $this->ticketModel->deleteById($id);
        header('Location: /public/ticket/listar');
        exit;
    }

    public function search()
    {
        $field = $_GET['categoriaSelect'] ?? 'id';
        $term  = $_GET['search'] ?? '';
        if ($term === '') {
            header('Location: /public/ticket/listar');
            exit;
        }
        $tickets = $this->ticketModel->searchWithExtras($field, $term);
        $this->render(
            'ticket',
            'lista',
            ['tickets' => $tickets],
            'site'
        );
    }
}
