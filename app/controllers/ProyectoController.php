<?php
require_once(__DIR__ . '/../models/Proyecto.php');
require_once(__DIR__ . '/../models/EstadoProyecto.php');
require_once(__DIR__ . '/../models/Usuario.php');

class ProyectoController extends Controller
{
    private $proyectoModel;
    private $estadoProyectoModel;
    private $usuarioModel;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

        $this->estadoProyectoModel = new EstadoProyecto($connection);  // Asignar a propiedad
        $this->usuarioModel = new Usuario($connection);                // Asignar a propiedad
        $this->proyectoModel = new Proyecto($connection);
    }

    public function listar()
    {
        $proyectos = $this->proyectoModel->getAllWithEstadoAndBoss();
        $this->render(
            'proyecto',
            'lista',
            ['proyectos' => $proyectos],
            'site'
        );
    }

    public function detalles()
    {
        $id = $_GET['id'];
        $proyecto = $this->proyectoModel->getById($id)[0];
        $this->render(
            'proyecto',
            'detalles',
            ['proyecto' => $proyecto],
            'site'
        );
    }

    public function formulario()
    {
        $estados = $this->estadoProyectoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
    
        $this->render(
            'proyecto',
            'formulario',
            [
                'proyectoEditar' => null,  // Al crear no hay proyecto previo
                'estados' => $estados,
                'usuarios' => $usuarios,
                'usuariosAsignadosIds' => [],  // Nada asignado en creación
            ],
            'site'
        );
    }
    

    public function actualizacion()
    {
        $proyecto = isset($_GET['id']) ? $this->proyectoModel->getById($_GET['id'])[0] : null;
        $estados = $this->estadoProyectoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
    
        $usuariosAsignados = $proyecto
            ? $this->proyectoModel->getUsuariosAsignados($proyecto['id'])
            : [];
    
        // Extrae sólo los ids para marcar en el formulario
        $idsAsignados = array_column($usuariosAsignados, 'id');
    
        $this->render(
            'proyecto',
            'formulario',
            [
                'proyectoEditar' => $proyecto,
                'estados' => $estados,
                'usuarios' => $usuarios,
                'usuariosAsignadosIds' => $idsAsignados,
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
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'estado_id' => $_POST['estado_id'],
            'jefe_id' => $_POST['jefe_id'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
        ];
    
        // Actualiza datos del proyecto
        $this->proyectoModel->updateProyecto($data);
    
        // Actualiza usuarios asignados (opcional pero recomendable)
        $this->proyectoModel->eliminarUsuariosAsignados($data['id']);
        if (!empty($_POST['usuarios'])) {
            foreach ($_POST['usuarios'] as $usuarioId) {
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
        // Validar que se reciban los datos mínimos necesarios
        if (
            empty($_POST['nombre']) ||
            empty($_POST['fecha_inicio']) ||
            empty($_POST['fecha_fin']) ||
            empty($_POST['estado_id']) ||
            empty($_POST['jefe_id'])
        ) {
            // Podrías redirigir con error o mostrar mensaje
            header('Location: /public/proyecto/formulario?error=Faltan+datos+obligatorios');
            exit;
        }
    
        // Preparar datos del proyecto
        $data = [
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'] ?? '',
            'estado_id' => $_POST['estado_id'],
            'jefe_id' => $_POST['jefe_id'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
        ];
    
        // Crear el proyecto y obtener el ID insertado
        $nuevoProyectoId = $this->proyectoModel->create($data);
    
        // Si hay usuarios asignados desde el formulario (select múltiple)
        if (!empty($_POST['usuarios']) && is_array($_POST['usuarios'])) {
            foreach ($_POST['usuarios'] as $usuarioId) {
                // Insertar relación proyecto-usuario
                $this->proyectoModel->asignarUsuario($nuevoProyectoId, $usuarioId);
            }
        }
    
        // Redirigir a la lista o detalles después de crear
        header('Location: /public/proyecto/listar');
        exit;
    }
    
}
