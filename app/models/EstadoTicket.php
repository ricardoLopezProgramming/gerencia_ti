<?php 
class EstadoTicket extends ORM{
    
    public function __construct($connection, $id = 'id')
    {
        parent::__construct($id, 'estado_proyecto', $connection);
    }
}