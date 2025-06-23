<?php
class EstadoProyecto extends ORM
{
    public function __construct($conn)
    {
        parent::__construct('id', 'estado_proyecto', $conn);
    }
}
