<?php
class Database
{
    private static $dbhost = '127.0.0.1';
    private static $dbport = '3306';
    private static $dbuser = 'root';
    private static $dbpassword = '';
    private static $dbdatabase = 'gerencia_ti';
    private static ?Database $instance = null;

    private function __construct()
    {
        // $dbhost = getenv('DB_HOST');
        // $dbport = getenv('DB_PORT');
        // $dbuser = getenv('DB_USER');
        // $dbpassword = getenv('DB_PASSWORD');
        // $dbdatabase = getenv('DB_DATABASE');
    }

    public static function getInstance(): Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        $conn = null;
        try {
            $dsn = 'mysql:host=' . self::$dbhost . ';port=' . self::$dbport . ';dbname=' . self::$dbdatabase;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            $conn = new PDO($dsn, self::$dbuser, self::$dbpassword, $options);
        } catch (PDOException $e) {
            throw new RuntimeException("Error de conexión a la base de datos: " . $e->getMessage());
        }
        return $conn;
    }
    
}

