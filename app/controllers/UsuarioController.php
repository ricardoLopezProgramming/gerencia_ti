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
        $users = $this->usuarioModel->getAllWithRoleAndDepartment();
        $this->render(
            'usuario',
            'lista',
            [
                'users' => $users,
                'module_title' => 'Gestión de Usuarios'
            ],
            'site'
        );
    }

    public function registro()
    {
        $departments = $this->departamentoModel->getAll();
        $roles = $this->rolModel->getAll();
        $this->render(
            'usuario',
            'formulario',
            [
                'departments' => $departments,
                'roles' => $roles,
            ],
            'site'
        );
    }

    public function actualizacion()
    {
        $roles = $this->rolModel->getAll();
        $departments = $this->departamentoModel->getAll();
        $user = isset($_GET['id']) ? $this->usuarioModel->getById($_GET['id'])[0] : null;
        $this->render(
            'usuario',
            'formulario',
            [
                'user' => $user,
                'departments' => $departments,
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
            'id' => $_POST['id'] ?? null,
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'avatar' => $_FILES['avatar'] ?? '',
            'role_id' => $_POST['role_id'],
            'department_id' => $_POST['department_id'],
        ];

        if ($this->usuarioModel->insertUser($data)) {
            header('Location: /public/usuario/listar');
            exit;
        }
    }



    public function actualizar()
    {
        $data = [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'avatar' => $_FILES['avatar'] ?? '',
            'role_id' => $_POST['role_id'],
            'department_id' => $_POST['department_id'],
        ];

        if ($this->usuarioModel->updateUser($data)) {
            header('Location: /public/usuario/listar');
            exit;
        }
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
                    'usuarios' => $this->usuarioModel->getByIdWithRoleAndDepartment($_GET['categoriaSelect'] === 'rol_id' ? $rol_id[$_GET['search']] : $_GET['search']),
                ],
                'site'
            );
        } else {
            header('Location: /public/usuario/listar');
            exit;
        }
    }
}
