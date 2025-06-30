<?php
require_once(__DIR__ . '/../models/Rol.php');

class RolController extends Controller
{
    private $rolModel;
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->rolModel = new Rol($connection);
    }

    public function listar()
    {
        $roles = $this->rolModel->getAll();

        $this->render(
            'rol',
            'lista',
            ['roles' => $roles],
            'site'
        );
    }

    public function formulario()
    {
        $this->render('rol', 'formulario', [], 'site');
    }

    public function registrar()
    {
        $data = [
            'nombre' => $_POST['nombre']
        ];
        $this->rolModel->insert($data);
        header('Location: /public/rol/listar');
        exit;
    }

    public function eliminar()
    {
        $this->rolModel->deleteById($_GET['id']);
        header('Location: /public/rol/listar');
        exit;
    }

    public function search()
    {
        if ($_GET['search'] !== '') {
            $rolModel = new Rol($this->connection, $_GET['categoriaSelect']);
            $roles = $rolModel->getById($_GET['search']);
            $this->render(
                'rol',
                'lista',
                ['roles' => $roles],
                'site'
            );
        } else {
            header('Location: /public/rol/listar');
            exit;
        }
    }
}
