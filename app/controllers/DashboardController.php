<?php

class DashboardController extends Controller
{

    public function __construct(PDO $connection)
    {
    }

    public function inicio()
    {
        $this->render('dashboard', 'graficos', [], 'site');
    }

    public function formulario()
    {
        $this->render('ticket', 'graficos', [], 'site');
    }

}
