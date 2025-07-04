<?php
class Departamento extends ORM
{
    public function __construct($connection, $id = 'id')
    {
        parent::__construct($id, 'departments', $connection);
    }
}
