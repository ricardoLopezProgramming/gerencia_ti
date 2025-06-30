<?php
    class Router{
        private $controller;
        private $method;

        public function __construct()
        {
            $this->matchRoute();
        }

        public function matchRoute(){

            $url = explode('/', URL);

            $this->controller = !empty($url[1]) ? $url[1] : 'usuario';
            $this->method = !empty($url[2]) ? explode('?', $url[2])[0] : 'lista';

            $this->controller = ucfirst($this->controller) . 'Controller';

            require_once(__DIR__ . '/controllers/'.$this->controller.'.php');
        }

        public function run(){
            $database = Database::getInstance();
            $connection = $database->getConnection();

            $controller = new $this->controller($connection);
            $method = $this->method;
            $controller->$method();
        }
    }