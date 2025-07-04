<?php
require_once(__DIR__ . '/helpers/common.php');
require_once(__DIR__ . '/core/Controller.php');
require_once(__DIR__ . '/core/ORM.php');
require_once(__DIR__ . '/services/Database.php');
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/Router.php');
require_once __DIR__ . '/dompdf/autoload.inc.php'; // o el path donde tengas el autoload
use Dompdf\Dompdf;

