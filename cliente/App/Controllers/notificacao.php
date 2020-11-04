<?php


namespace App\Controllers;

use App\ControllerSeguro;

class Notificacao extends ControllerSeguro
{

    public function notifica($id_user, $tipo_user){
        $query =$this->db->prepare('SELECT * FROM notifica Where id_user=:id_user and tipo_user=:tipo_user');
        $query->bindParam(":id_user", $id_user);
        $query->bindParam(":tipo_user", $tipo_user);
        $query->execute();
     }

     public function inserir($id_user, $tipo_user, $texto, $url_noti, $icone){
        $query = $this->db->prepare("INSERT INTO notificacao (id_user, tipo_user, texto, url_noti, icone) values (:id_user, :tipo_user, :texto, :url_noti, :icone)");
        $query->bindParam(":id_user", $id_user);
        $query->bindParam(":tipo_user", $tipo_user);
        $query->bindParam(":texto", $texto);
        $query->bindParam(":url_noti", $url_noti);
        $query->bindParam(":icone", $icone);
        $query->execute();
     }

}
