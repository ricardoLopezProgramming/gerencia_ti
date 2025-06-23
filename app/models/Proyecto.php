<?php
class Proyecto extends ORM
{
    public $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
        parent::__construct('id', 'proyecto', $conn);
    }

    // ProyectoModel.php

    public function crearProyecto($nombre, $descripcion, $fechaInicio, $fechaFin, $usuarios)
    {
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado_id' => 1 // estado inicial por defecto
        ];

        $this->insert($data); // Método del ORM
        $nuevoId = $this->lastId(); // Último ID insertado

        $this->asignarUsuarios($nuevoId, $usuarios);

        return $nuevoId;
    }

    public function asignarUsuarios($proyectoId, $usuarios)
    {
        // Elimina asignaciones previas si estás actualizando
        $sqlDelete = "DELETE FROM proyecto_usuario WHERE proyecto_id = ?";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->execute([$proyectoId]);

        // Inserta nuevas asignaciones
        $sqlInsert = "INSERT INTO proyecto_usuario (proyecto_id, usuario_id) VALUES (?, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        foreach ($usuarios as $userId) {
            $stmtInsert->execute([$proyectoId, $userId]);
        }
    }

    public function getUsuariosDeProyecto($proyectoId)
    {
        $sql = "SELECT usuario_id FROM proyecto_usuario WHERE proyecto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$proyectoId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'usuario_id');
    }
}
