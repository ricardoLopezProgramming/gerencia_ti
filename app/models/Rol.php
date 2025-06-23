<?php
class Rol extends ORM
{
    public function __construct($conn)
    {
        parent::__construct('id', 'rol', $conn);
    }
}
