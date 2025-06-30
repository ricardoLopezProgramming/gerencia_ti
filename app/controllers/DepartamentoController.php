<?php
require_once(__DIR__ . '/../models/Departamento.php');

class DepartamentoController extends Controller
{
    private PDO $connection;
    private $departamentoModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->departamentoModel = new Departamento($connection);
    }

    public function listar()
    {
        $departamentos = $this->departamentoModel->getAll();

        $this->render(
            'departamento',
            'lista',
            ['departamentos' => $departamentos],
            'site'
        );
    }

    public function formulario()
    {
        $this->render('departamento', 'formulario', [], 'site');
    }

    public function registrar()
    {
        $data = [
            'nombre' => $_POST['nombre']
        ];
        $this->departamentoModel->insert($data);
        header('Location: /public/departamento/listar');
        exit;
    }

    public function search()
    {
        if ($_GET['search'] !== '') {
            $departamentoModel = new Departamento($this->connection, $_GET['categoriaSelect']);
            $departamentos = $departamentoModel->getById($_GET['search']);
            $this->render(
                'departamento',
                'lista',
                ['departamentos' => $departamentos],
                'site'
            );
        } else {
            header('Location: /public/departamento/listar');
            exit;
        }
    }
    
}
