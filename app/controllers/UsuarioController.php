<?php
require_once(__DIR__ . '/../models/Usuario.php');
require_once(__DIR__ . '/../models/Departamento.php');
require_once(__DIR__ . '/../models/Rol.php');
require_once(__DIR__ . '/../helpers/UsuarioHelper.php');

class UsuarioController extends Controller
{
    private $usuarioModel;
    private $departamentoModel;
    private $rolModel;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->usuarioModel = new Usuario($connection);
        $this->departamentoModel = new Departamento($connection);
        $this->rolModel = new Rol($connection);
    }

    // public function listar()
    // {
    //     $page = isset($_GET['page']) ? (int)($_GET['page']) : 1;
    //     $limit = 5;
    //     $result = $this->usuarioModel->getAllUsuariosPaginacion($page, $limit);
    //     $usuarios = $result['data'];
    //     $this->render(
    //         'usuario',
    //         'lista',
    //         [
    //             'usuarios' => $usuarios,
    //             'page' => $result['page'],
    //             'pages' => $result['pages']
    //         ],
    //         'site'
    //     );
    // }

    public function listar()
    {
        $usuarios = $this->usuarioModel->getAllWithRoleAndDepartament();
        $this->render(
            'usuario',
            'lista',
            [
                'usuarios' => $usuarios,
                'module_title' => 'Gestión de Usuarios'
            ],
            'site'
        );
    }

    public function formulario()
    {
        $departamentos = $this->departamentoModel->getAll();
        $roles = $this->rolModel->getAll();
        $this->render(
            'usuario',
            'formulario',
            [
                'departamentos' => $departamentos,
                'roles' => $roles,
            ],
            'site'
        );
    }

    public function actualizacion()
    {
        $roles = $this->rolModel->getAll();
        $departamentos = $this->departamentoModel->getAll();
        $usuario = isset($_GET['id']) ? $this->usuarioModel->getById($_GET['id'])[0] : null;
        $this->render(
            'usuario',
            'formulario',
            [
                'usuario' => $usuario,
                'departamentos' => $departamentos,
                'roles' => $roles,
            ],
            'site'
        );
    }

    public function detalles()
    {
        $id = $_GET['id'];
        $usuario = $this->usuarioModel->getById($id);
        $this->render(
            'usuario',
            'detalles',
            [
                'usuario' => $usuario[0],
            ],
            'site'
        );
    }

    public function registrar()
    {
        $data = [
            'nombre' => $_POST['nombre'],
            'correo' => $_POST['correo'],
            'password' => $_POST['password'],
            'rol_id' => $_POST['rol_id'],
            'departamento_id' => $_POST['departamento_id'],
            'imagen' => $_FILES['imagen'],
        ];

        if ($this->usuarioModel->insertUsuario($data)) {
            header('Location: /public/usuario/listar');
            exit;
        }
    }


    public function actualizar()
    {
        $roles = $this->rolModel->getAll();
        $this->render(
            'usuario',
            'formulario',
            [
                'roles' => $roles,
            ],
            'site'
        );
    }

    public function eliminar()
    {
        $this->usuarioModel->deleteById($_GET['id']);
        $this->listar();
    }

    public function search()
    {
        $roles = $this->rolModel->getAll();
        $rol_id = [];
        foreach ($roles as $rol) {
            $rol_id[$rol['nombre']] = $rol['id'];
        }
        if ($_GET['search'] !== '') {
            if ($_GET['categoriaSelect'] === 'rol') {
            }
            $this->usuarioModel = new Usuario($this->connection, $_GET['categoriaSelect']);
            $this->render(
                'usuario',
                'lista',
                [
                    'usuarios' => $this->usuarioModel->getByIdWithRoleAndDepartament($_GET['categoriaSelect'] === 'rol_id' ? $rol_id[$_GET['search']] : $_GET['search']),
                ],
                'site'
            );
        } else {
            header('Location: /public/usuario/listar');
            exit;
        }
    }
}
