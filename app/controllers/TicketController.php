<?php
require_once(__DIR__ . '/../models/Ticket.php');

class TicketController extends Controller
{
    private $ticketModel;

    public function __construct(PDO $connection)
    {
        $this->ticketModel = new Ticket($connection);
    }

    public function listar()
    {
        $tickets = $this->ticketModel->getAllWithEstadoYProyecto();
        $this->render(
            'ticket',
            'lista',
            ['tickets' => $tickets],
            'site'
        );
    }

    public function formulario()
    {
        $this->render('ticket', 'formulario', [], 'site');
    }

    public function registrar()
    {
        $data = [
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'estado' => $_POST['estado']
        ];
        $this->ticketModel->insert($data);
        header('Location: /public/ticket/listar');
        exit;
    }

    public function eliminar()
    {
        $this->ticketModel->deleteById($_GET['id']);
        header('Location: /public/ticket/listar');
        exit;
    }
}
