<?php
require_once(__DIR__ . '/../models/Proyecto.php');
require_once(__DIR__ . '/../models/EstadoProyecto.php');
require_once(__DIR__ . '/../models/Usuario.php');
require_once(__DIR__ . '/../models/UsuarioProjecto.php');
require_once(__DIR__ . '/../models/Ticket.php');
use vendor\Dompdf\Dompdf;

class ProyectoController extends Controller
{
    private $proyectoModel;
    private $estadoProyectoModel;
    private $usuarioModel;
    private $usuarioProjectoModel;
    private $ticketModel;

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

        $this->estadoProyectoModel = new EstadoProyecto($connection);  // Asignar a propiedad
        $this->usuarioModel = new Usuario($connection);                // Asignar a propiedad
        $this->proyectoModel = new Proyecto($connection);
        $this->usuarioProjectoModel = new UsuarioProjecto($connection);
        $this->ticketModel = new Ticket($connection);
    }

    public function listar()
    {
        $role = $_SESSION['role'];
        $id = $_SESSION['id'];

        if ($role == 'administrador') {
            // Administrador: Ver todos los proyectos
            $projects = $this->proyectoModel->getAllWithEstadoAndBoss();
        } elseif ($role == 'jefe de proyecto') {
            // Jefe de proyecto: solo los que él ha creado
            $projects = $this->proyectoModel->getManagerProjects($id);
        } else {
            // Empleado: solo donde está asignado
            $projects = $this->proyectoModel->getEmployeeProjects($id);
        }

        $this->render(
            'proyecto',
            'lista',
            ['projects' => $projects],
            'site'
        );
    }


    public function detalles()
    {
        $id = $_GET['id'];
        $project = $this->proyectoModel->getById($id)[0];
        $ticketModel = new Ticket($this->connection, 'project_id');
        $tickets = $ticketModel->getAllWithStatusAndProjectById($id);
        $users = $this->usuarioProjectoModel->getUsersByProjectId($id);
        $this->render(
            'proyecto',
            'detalles',
            [
                'project' => $project,
                'tickets' => $tickets,
                'users' => $users,
            ],
            'site'
        );
    }

    public function registro()
    {
        $project_statuses = $this->estadoProyectoModel->getAll();
        $users = $this->usuarioModel->getAll();
        $manager = $this->usuarioModel->getById($_SESSION['id'])[0];
        $avalibleUsers = $this->usuarioProjectoModel->getAvailableUsers();

        $this->render(
            'proyecto',
            'formulario',
            [
                'proyectoEditar' => null,
                'project_statuses' => $project_statuses,
                'usuarios' => $avalibleUsers, // todos los usuarios disponibles para asignar
                'usuariosAsignadosIds' => [],
                'manager' => $manager,
            ],
            'site'
        );
    }


    public function actualizacion()
    {
        if (!isset($_GET['id'])) {
            die("Error: ID del proyecto no definido.");
        }

        $proyecto = $this->proyectoModel->getById($_GET['id'])[0] ?? null;
        $estados = $this->estadoProyectoModel->getAll();

        // Usuarios asignados al proyecto (actuales)
        $usuariosAsignados = $this->proyectoModel->getUsuariosAsignados($proyecto['id'] ?? 0);

        // Usuarios disponibles (no asignados y en proyectos terminados)
        $usuariosDisponibles = $this->usuarioProjectoModel->getAvailableUsers();

        // Combinar ambos arrays sin duplicados (en base a 'id')
        // Combinar asignados y disponibles sin duplicados
        $usuariosCombinados = [];

        foreach ($usuariosAsignados as $ua) {
            $usuariosCombinados[$ua['id']] = $ua;
        }
        foreach ($usuariosDisponibles as $ud) {
            $usuariosCombinados[$ud['id']] = $ud;
        }

        // Ahora $usuariosCombinados es un array asociativo con usuarios únicos
        // Para la vista, puedes pasarlo como array indexado así:
        $usuariosCombinados = array_values($usuariosCombinados);


        // Extrae sólo los IDs para marcar en el form
        $usuariosAsignadosIds = array_column($usuariosAsignados, 'id');

        $this->render(
            'proyecto',
            'formulario',
            [
                'proyectoEditar' => $proyecto,
                'project_statuses' => $estados,
                'usuarios' => $usuariosCombinados,
                'usuariosAsignadosIds' => $usuariosAsignadosIds,
                'manager' => $this->usuarioModel->getById($proyecto['manager_id'])[0],
            ],
            'site'
        );
    }


    public function actualizar()
    {
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            die("Error: ID del proyecto no definido.");
        }

        $data = [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'status_id' => $_POST['status_id'],
            'manager_id' => $_POST['manager_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
        ];

        // Actualiza datos del proyecto
        $this->proyectoModel->updateProyecto($data);

        // Actualiza usuarios asignados
        $this->proyectoModel->eliminarUsuariosAsignados($data['id']);
        if (!empty($_POST['users'])) {
            foreach ($_POST['users'] as $usuarioId) {
                $this->proyectoModel->asignarUsuario($data['id'], (int)$usuarioId);
            }
        }

        header('Location: /public/proyecto/listar');
        exit;
    }


    public function eliminar()
    {
        $this->proyectoModel->deleteById($_GET['id']);
        header('Location: /public/proyecto/listar');
        exit;
    }

    public function registrar()
    {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'status_id' => $_POST['status_id'],
            'manager_id' => $_POST['manager_id'],
        ];

        // Insertar proyecto y obtener el nuevo ID
        $newProjectId = $this->proyectoModel->insert($data);

        // Asignar usuarios al proyecto (si hay)
        if (!empty($_POST['users'])) {
            foreach ($_POST['users'] as $usuarioId) {
                $this->usuarioProjectoModel->insertProjectUser((int)$newProjectId, (int)$usuarioId);
            }
        }

        header('Location: /public/proyecto/listar');
        exit;
    }

    public function reporte()
    {
        // Solo jefe de proyecto puede acceder
        if ($_SESSION['role'] !== 'jefe de proyecto') {
            die('No tienes permisos para generar este reporte.');
        }

        $manager_id = $_SESSION['id'];
        $projects = $this->proyectoModel->getManagerProjects($manager_id);

        foreach ($projects as &$project) {
            $project['tickets'] = $this->ticketModel->getAllWithStatusAndProjectById($project['id']);
            $project['users'] = $this->usuarioProjectoModel->getUsersByProjectId($project['id']);
        }

        // Generar HTML para el reporte
        ob_start();
        include(__DIR__ . '/../views/proyecto/reporte_pdf.view.php');
        $html = ob_get_clean();

        // Generar PDF con Dompdf
        require_once(__DIR__ . '/../../vendor/autoload.php');
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte_proyectos.pdf");
        exit;
    }
}
