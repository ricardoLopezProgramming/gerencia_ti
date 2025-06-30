<?php
    class Controller{
        protected function render($folder, $path, $parameters = [], $layout = '')
        {
            ob_start();
        
            // Añade esta línea para que las variables estén disponibles en la vista
            extract($parameters); 
        
            require_once(__DIR__ . '/../views/'.$folder.'/'.$path.'.view.php');
            $content = ob_get_clean();
        
            require_once(__DIR__ . '/../views/layout/'.$layout.'.layout.php');
        }
    }