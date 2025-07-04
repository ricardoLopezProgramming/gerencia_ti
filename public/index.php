<?php
require_once(__DIR__ . '/../app/autoload.php');
session_start();

$currentUri = $_SERVER['REQUEST_URI'];

// Si no está logueado y NO está en la página de login
if (!isset($_SESSION['email']) && !str_contains($currentUri, '/signin')) {
    header("Location: /public/signin/signin");
    exit;
}

$router = new Router();
$router->run();
