<?php
class ProyectoController extends Controller
{
    public function control()
    {
        $this->render("proyecto", "index", [], 'layout');
    }
    public function listar()
    {
        //echo '<div>HOLAAAA</div>';
        require_once __DIR__ . '/../views/proyecto/listar.php';
    }
    public function actualizar()
    {
        $this->render("proyecto", "index", [], 'layout');
    }
    public function eliminar()
    {
        return 'home';
    }
    public function read()
    {
        $this->render("proyecto", "index", [], 'layout');
    }
}
