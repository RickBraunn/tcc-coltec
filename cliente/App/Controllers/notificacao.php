<?php


namespace App\Controllers;

use App\ControllerSeguro;

class Notificacao extends ControllerSeguro
{

   public function notifica()
   {
      $id_user = $_SESSION['id_cli'];
      $tipo_user = "cli";
      $query = $this->db->prepare('SELECT * FROM notificacao Where id_user=:id_user and tipo_user=:tipo_user and data_leitura is null ');
      $query->bindParam(":id_user", $id_user);
      $query->bindParam(":tipo_user", $tipo_user);
      $query->execute();
      $this->jsonResponse($query->fetchAll());
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
