<?php
class Usuario extends ORM
{
    public PDO $connection;
    public $id;

    public function __construct($connection, $id = "id")
    {
        $this->id = $id;
        $this->connection = $connection;
        parent::__construct($id, 'users', $connection);
    }

    public function getAllWithRoleAndDepartment(): array
    {
        $sql = "SELECT u.*, r.name role, d.name department FROM users u INNER JOIN roles r ON u.role_id = r.id INNER JOIN departments d ON u.department_id = d.id ORDER BY u.id ASC";
        $stmt = $this->connection->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getByIdWithRoleAndDepartment($id): array
    {
        $sql = "SELECT u.*, r.name role, d.name department FROM users u INNER JOIN roles r ON u.role_id = r.id INNER JOIN departments d ON u.department_id = d.id WHERE {$this->id} = :id ORDER BY u.id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);
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


    public function insertUser($data)
    {
        $avatar = $data['avatar'];
        $avatarTmp = $avatar['tmp_name'];
        $avatarName = $avatar['name'];
        $avatarType = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));

        $data['avatar'] = ''; // campo temporal vacío
        $this->insert($data);

        $lastId = $this->connection->lastInsertId();
        $rutaDB = "/../../../public/assets/image/" . $lastId . '.' . $avatarType;
        $rutaSave = __DIR__ . '/../../public/assets/image/' . $lastId . '.' . $avatarType;

        if (move_uploaded_file($avatarTmp, $rutaSave)) {
            $this->updateById($lastId, ['avatar' => $rutaDB]);
            return true;
        }

        return false;
    }

    public function updateUser($data)
    {
        $avatar = $data['avatar'];
        unset($data['avatar']); // Evita pasar un array a SQL

        $nuevaRuta = null;

        if ($avatar && $avatar['tmp_name']) {
            $tipoImagen = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
            $nuevaRuta = "/../../../public/assets/image/" . $data['id'] . '.' . $tipoImagen;
            $rutaSave = __DIR__ . '/../../public/assets/image/' . $data['id'] . '.' . $tipoImagen;

            if (move_uploaded_file($avatar['tmp_name'], $rutaSave)) {
                $data['avatar'] = $nuevaRuta;
            }
        }

        $this->updateById($data['id'], $data);
        return $nuevaRuta;
    }
}
