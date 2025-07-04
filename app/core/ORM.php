<?php
class ORM
{
    protected $id;
    protected $table;
    protected PDO $connection;

    public function __construct($id, $table, PDO $connection)
    {
        $this->id = $id;
        $this->table = $table;
        $this->connection = $connection;
    }

    public function getAll()
    {
        $stm = $this->connection->prepare("SELECT * FROM {$this->table} ORDER BY id ASC");
        $stm->execute();
        return $stm->fetchAll();
    }
    public function getById($id)
    {
        $stm = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $stm->bindValue(":id", $id);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteById($id)
    {
        $stm = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    public function updateById($id, $data)
    {
        $sql = "UPDATE {$this->table} SET ";

        foreach ($data as $key => $value) {
            if ($key === 'id') continue;
            $sql .= "{$key} = :{$key},";
        }

        $sql = trim($sql, ','); 
        $sql .= " WHERE {$this->id} = :id";

        $stm = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            if ($key === 'id') continue; // 👈 evita bind duplicado
            $stm->bindValue(":{$key}", $value);
        }

        $stm->bindValue(":id", $id);
        return $stm->execute();
    }


    public function insert($data)
    {
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            $sql .= "{$key},";
        }
        $sql = trim($sql, ',');
        $sql .= ") VALUES (";

        foreach ($data as $key => $value) {
            $sql .= ":{$key},";
        }
        $sql = trim($sql, ',');
        $sql .= ")";

        $stmt = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    public function paginate($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $rows = $this->connection->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();

        $sql = "SELECT * FROM {$this->table} ORDER BY id ASC LIMIT {$offset},{$limit}";
        $stm = $this->connection->prepare($sql);
        $stm->execute();

        $pages = ceil($rows / $limit);
        $data = $stm->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'pages' => $pages,
            'rows' => $rows,
        ];
    }
}
