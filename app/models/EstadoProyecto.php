<?php
class EstadoProyecto extends ORM
{
    public function __construct($connection, $id = 'id')
    {
        parent::__construct('id', 'project_statuses', $connection);
    }
}
