<?php
class UsuarioController extends Controller
{
    public function control()
    {
        $this->render("usuario", "index", [], 'layout');
    }
    public function registrar()
    {
        require_once __DIR__ . '/../views/usuario/listar.php';
    }
    public function listar()
    {
        require_once __DIR__ . '/../views/usuario/listar.php';
    }
    public function actualizar()
    {
        return 'home';
    }
    public function eliminar()
    {
        return 'home';
    }
    public function read()
    {
        $this->render("usuario", "index", [], 'layout');
    }
}
