<?php
class SignInController extends Controller
{
    private PDO $connection;

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
            $correo = trim($_POST['correo']);
            $password = $_POST['contraseña'];

            try {
                $stmt = $this->connection->prepare("SELECT * FROM usuario WHERE correo = ?");
                $stmt->execute([$correo]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario && $password === $usuario['password']) {
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['password'] = $usuario['password'];
                    $_SESSION['imagen'] = $usuario['imagen'];
                    $_SESSION['rol_id'] = $usuario['rol_id'];

                    header('Location: /public/dashboard/inicio');
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
