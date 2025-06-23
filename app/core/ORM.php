<?php
class ORM
{
    private $id;
    private $table;
    private $conn;

    public function __construct($id, $table, PDO $conn)
    {
        $this->id = $id;
        $this->table = $table;
        $this->conn = $conn;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->query($sql); // no uses prepare con tabla dinámica
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getAllUsuarios()
    {
        $sql = "SELECT u.id, u.imagen, u.nombre, u.correo, u.contraseña, u.rol_id, r.nombre AS rol
                FROM usuario u
                INNER JOIN rol r ON u.rol_id = r.id";
        $stmt = $this->conn->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getAllProyectos()
    {
        $sql = "SELECT 
                    p.id, 
                    p.nombre, 
                    p.descripcion, 
                    p.fecha_inicio, 
                    p.fecha_fin, 
                    e.nombre AS estado 
                FROM proyecto p 
                INNER JOIN estado_proyecto e ON p.estado_id = e.id";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getByID($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->id} = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProyectoByID($id)
    {
        $sql = "SELECT 
                p.id, 
                p.nombre, 
                p.descripcion, 
                p.fecha_inicio, 
                p.fecha_fin, 
                e.nombre AS estado 
            FROM proyecto p 
            INNER JOIN estado_proyecto e ON p.estado_id = e.id
            WHERE p.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Solo 1 resultado
    }

    public function getUsuarioByID($id)
    {
        $sql = "SELECT u.id, u.imagen, u.nombre, u.correo, u.contraseña, u.rol_id, r.nombre AS rol
        FROM usuario u
        INNER JOIN rol r ON u.rol_id = r.id
        WHERE u.{$this->id} = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos por completar...
    public function deleteByid($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->id} = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }

    public function updateById($id, $data)
    {
        $sql = "UPDATE {$this->table} SET ";
        $setParts = [];

        foreach ($data as $key => $value) {
            // Escapar el nombre del parámetro de forma segura
            $safeKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
            $setParts[] = "{$key} = :{$safeKey}";
        }

        $sql .= implode(', ', $setParts);
        $sql .= " WHERE {$this->id} = :id";

        $stm = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $safeKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
            $stm->bindValue(":{$safeKey}", $value);
        }

        $stm->bindValue(":id", $id);
        $stm->execute();
    }


    public function insert($data)
    {
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            $sql .= "{$key}, ";
        }
        $sql = rtrim($sql, ", ") . ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= ":{$key}, ";
        }
        $sql = rtrim($sql, ", ") . ")"; // Cerramos VALUES correctamente

        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute(); // ejecutamos y retornamos true/false
    }

    public function paginacion($page, $limit)
    {
        $offset = ($page > 0)  ? ($page - 1) * $limit : 0;

        $sql = "SELECT * FROM {$this->table}
            ORDER BY {$this->id}
            OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function lastId()
    {
        return $this->conn->lastInsertId();
    }
}
