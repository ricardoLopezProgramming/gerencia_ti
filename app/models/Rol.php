<?php
class Rol extends ORM
{
    public function __construct($connection, $id = "id")
    {
        parent::__construct($id, 'rol', $connection);
    }
}
