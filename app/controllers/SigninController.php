<?php
require_once __DIR__ . '/../models/Usuario.php';

class SignInController extends Controller
{
    private PDO $connection;
    private $usuarioModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function signin()
    {
        // Mostrar vista de login
        $this->render('signin', 'signin', [], 'signin');
    }

    public function authentication()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            try {
                $usuarioModel = new Usuario($this->connection, "email");
                $usuario = $usuarioModel->getByIdWithRoleAndDepartment($email)[0];

                if ($usuario && $password === $usuario['password']) {
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['name'] = $usuario['name'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['avatar'] = $usuario['avatar'];
                    $_SESSION['role'] = $usuario['role'];
                    $_SESSION['department'] = $usuario['department'];
                    
                    header('Location: /public/dashboard/index');
                    exit;
                } else {
                    echo "❌ Credenciales incorrectas.";
                }
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
        }

        // Si no es POST o falla login, volver a vista login
        $this->render('signin', 'signin', [], 'site');
    }

    public function signout()
    {
        session_destroy();
        header("Location: /public/signin/signin");
        exit;
    }
}
