<?php


namespace App\Controllers;

use App\ControllerSeguro;

class Notificacao extends ControllerSeguro
{

    public function notifica(){
        $this->db->query('SELECT * FROM notifica');
    }


}
