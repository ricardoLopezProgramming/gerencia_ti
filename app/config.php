<?php
$folderPath = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  // Solo la ruta sin query string
$url = substr($urlPath, strlen($folderPath));
define('URL', $url);
define('URL_PATH', $folderPath);

