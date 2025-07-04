<?php
class HorasController extends Controller
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        date_default_timezone_set('America/Lima');
    }

    public function index()
    {
        $usuarioId = $_SESSION['id'];
        $hoy = date('Y-m-d');

        // Buscar o crear work_log del día
        $stmt = $this->connection->prepare("SELECT * FROM work_logs WHERE user_id = ? AND date = ?");
        $stmt->execute([$usuarioId, $hoy]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$log) {
            // Crear nuevo registro si no existe
            $stmt = $this->connection->prepare("INSERT INTO work_logs (user_id, date) VALUES (?, ?)");
            $stmt->execute([$usuarioId, $hoy]);
            $logId = $this->connection->lastInsertId();
        } else {
            $logId = $log['id'];
        }

        // Verificar si hay sesión abierta
        $stmt = $this->connection->prepare("SELECT * FROM work_sessions WHERE log_id = ? AND end_time IS NULL");
        $stmt->execute([$logId]);
        $sesionActiva = $stmt->fetch(PDO::FETCH_ASSOC);

        // Consulta todas las sesiones laborales del usuario para el historial
        $stmt = $this->connection->prepare("
            SELECT ws.start_time, ws.end_time
            FROM work_sessions ws
            JOIN work_logs wl ON ws.log_id = wl.id
            WHERE wl.user_id = ?
            ORDER BY ws.start_time DESC
        ");
        $stmt->execute([$usuarioId]);
        $sesiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener notas del día si existen
        $stmt = $this->connection->prepare("SELECT notes FROM work_logs WHERE id = ?");
        $stmt->execute([$logId]);
        $notas = $stmt->fetchColumn();

        $this->render('horas', 'horas', [
            'logId' => $logId,
            'sesionActiva' => $sesionActiva,
            'sesiones' => $sesiones,
            'notas' => $notas
        ], 'site');
    }

    public function iniciar()
    {
        $logId = $_POST['log_id'];

        // Verifica que no haya sesión activa
        $stmt = $this->connection->prepare("SELECT * FROM work_sessions WHERE log_id = ? AND end_time IS NULL");
        $stmt->execute([$logId]);
        if ($stmt->fetch()) {
            echo "⚠️ Ya tienes una sesión activa.";
            return;
        }

        // Crear nueva sesión
        $horaActual = date('Y-m-d H:i:s');
        $stmt = $this->connection->prepare("INSERT INTO work_sessions (log_id, start_time) VALUES (?, ?)");
        $stmt->execute([$logId, $horaActual]);

        header("Location: /public/horas/index");
        exit;
    }

    public function detener()
    {
        $logId = $_POST['log_id'];

        // Cerrar sesión activa
        $horaActual = date('Y-m-d H:i:s');
        $stmt = $this->connection->prepare("UPDATE work_sessions SET end_time = ? WHERE log_id = ? AND end_time IS NULL");
        $stmt->execute([$horaActual, $logId]);

        header("Location: /public/horas/index");
        exit;
    }
}
