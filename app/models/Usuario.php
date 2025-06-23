<?php
class Usuario extends ORM
{
    public function __construct($conn)
    {
        parent::__construct('id', 'usuario', $conn);
    }
    
}
