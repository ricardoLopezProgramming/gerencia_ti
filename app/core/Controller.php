<?php 
    class Controller {
        protected function render($folder, $path, $parameters = [], $layout = '') {
            ob_start();
            require_once __DIR__ . "/../views/{$folder}/{$path}.php";
            $content = ob_get_clean();
            require_once __DIR__."/../views/layout/{$layout}.php";
        }
    }