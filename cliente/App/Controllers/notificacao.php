<?php


namespace App\Controllers;

use App\ControllerSeguro;

class Notificacao extends ControllerSeguro
{

    public function notifica(){
        $this->db->query('SELECT * FROM notifica');
     }

     public function inserir($id_user, $tipo_user, $texto){
        $query = $this->db->prepare("INSERT INTO notificacao (id_user, tipo_user, texto) values (:id_user, :tipo_user, :texto)");
        $query->bindParam(":id_user", $id_user);
        $query->bindParam(":tipo_user", $tipo_user);
        $query->bindParam(":texto", $texto);
        $query->execute();
     }

}
