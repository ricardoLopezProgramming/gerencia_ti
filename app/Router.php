<?php
require_once __DIR__ . '/config.php';
class Router
{
    private $controller;
    private $method;
    private $params = [];

    public function __construct()
    {
        $this->matchRoute();
    }

    public function matchRoute()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $path = parse_url($requestUri, PHP_URL_PATH);
        $url = explode("/", trim($path, "/")); // elimina / inicial y final

        // Asigna controlador y método
        $this->controller = !empty($url[1]) ? ucfirst($url[1]) . 'Controller' : 'UsuarioController';
        $this->method = !empty($url[2]) ? $url[2] : 'read';

        // Parámetros adicionales (como el ID)
        $this->params = array_slice($url, 3);

        require_once __DIR__ . '/controllers/' . $this->controller . '.php';
    }

    public function run()
    {
        $controller = new $this->controller();
        $method = $this->method;

        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $this->params);
        } else {
            echo "Método {$method} no encontrado en {$this->controller}";
        }
    }
}
