<?php
class Usuario extends ORM
{
    public PDO $connection;
    public $id;

    public function __construct($connection, $id = "id")
    {
        $this->id = $id;
        $this->connection = $connection;
        parent::__construct($id, 'usuario', $connection);
    }

    public function getAllWithRoleAndDepartament(): array
    {
        $sql = "SELECT u.id, u.imagen, u.nombre, u.correo, u.password, r.nombre rol, d.nombre departamento FROM usuario u INNER JOIN rol r ON u.rol_id = r.id INNER JOIN departamento d ON u.departamento_id = d.id ORDER BY u.id ASC";
        $stmt = $this->connection->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getByIdWithRoleAndDepartament($idvalue): array
    {
        $sql = "SELECT u.*, r.nombre rol, d.nombre departamento 
                FROM usuario u 
                INNER JOIN rol r ON u.rol_id = r.id 
                INNER JOIN departamento d ON u.departamento_id = d.id 
                WHERE u.{$this->id} = :idvalue
                ORDER BY u.id ASC";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':idvalue', $idvalue);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // public function getAllUsuariosPaginacion($page, $limit): array
    // {
    //     $offset = ($page - 1) * $limit;

    //     $rows = $this->connection->query("SELECT COUNT(*) FROM usuario")->fetchColumn();

    //     $sql = "SELECT u.*, r.nombre rol, d.nombre departamento FROM usuario u INNER JOIN rol r ON u.rol_id = r.id INNER JOIN departamento d ON u.departamento_id = d.id ORDER BY u.id ASC LIMIT ?, ?";
    //     $stmt = $this->connection->prepare($sql);
    //     $stmt->execute([$offset, $limit]);
    //     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $pages = ceil($rows / $limit);
    //     return [
    //         'data' => $data,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'pages' => $pages,
    //         'rows' => $rows,
    //     ];
    // }

    // public function getUsuarioWithRoleAndDepartament($idValue, $page, $limit): array
    // {
    
    //     $offset = ($page - 1) * $limit;
    
    //     // Contar resultados totales filtrados
    //     $countSql = "SELECT COUNT(*) FROM usuario u 
    //                  WHERE u.{$this->id} = :idvalue";
    //     $countStmt = $this->connection->prepare($countSql);
    //     $countStmt->bindValue(':idvalue', $idValue);
    //     $countStmt->execute();
    //     $rows = $countStmt->fetchColumn();
    
    //     // Obtener resultados paginados
    //     $sql = "SELECT u.*, r.nombre rol, d.nombre departamento 
    //             FROM usuario u 
    //             INNER JOIN rol r ON u.rol_id = r.id 
    //             INNER JOIN departamento d ON u.departamento_id = d.id 
    //             WHERE u.{$this->id} = :idvalue 
    //             ORDER BY u.id ASC 
    //             LIMIT :offset, :limit";
    
    //     $stmt = $this->connection->prepare($sql);
    //     $stmt->bindValue(':idvalue', $idValue);
    //     $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    //     $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    //     $stmt->execute();
    
    //     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $pages = ceil($rows / $limit);
    
    //     return [
    //         'data' => $data,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'pages' => $pages,
    //         'rows' => $rows,
    //     ];
    // }
    

    public function insertUsuario($data)
    {
        $imagen = $data['imagen'];
        $imagenTmp = $imagen['tmp_name'];
        $imagenNombre = $imagen['name'];
        $tipoImagen = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
        $data['imagen'] = '';
        $this->insert($data);
        $lastId = $this->connection->lastInsertId();
        $rutaDB = "/../../../public/assets/image/" . $lastId . '.' . $tipoImagen;
        $rutaSave = __DIR__ . '/../../public/assets/image/' . $lastId . '.' . $tipoImagen;
        if (move_uploaded_file($imagenTmp, $rutaSave)) {
            $this->updateById($lastId, ['imagen' => $rutaDB]);
            return true;
        } else {
            return false;
        }
    }
}
