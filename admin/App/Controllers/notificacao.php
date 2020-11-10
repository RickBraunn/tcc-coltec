<?php


namespace App\Controllers;

use App\ControllerSeguro;

class Notificacao extends ControllerSeguro
{

    public function notifica(){
         $id_user=$_SESSION['id_adv'];
         $tipo_user = "adv";

        $query =$this->db->prepare('SELECT idnotificacao, texto, icone, url_noti  FROM notificacao Where id_user=:id_user and tipo_user=:tipo_user and data_leitura is null ');
        $query->bindParam(":id_user", $id_user);
        $query->bindParam(":tipo_user", $tipo_user);
        $query->execute();
        $this->jsonResponse($query->fetchAll());
     }

   public function inserir($id_user, $tipo_user, $texto, $url_noti, $icone)
   {
      $query = $this->db->prepare("INSERT INTO notificacao (id_user, tipo_user, texto, url_noti, icone) values (:id_user, :tipo_user, :texto, :url_noti, :icone)");
      $query->bindParam(":id_user", $id_user);
      $query->bindParam(":tipo_user", $tipo_user);
      $query->bindParam(":texto", $texto);
      $query->bindParam(":url_noti", $url_noti);
      $query->bindParam(":icone", $icone);
      $query->execute();
   }
   public function leitura($idnotificacao)
   {
       $query =$this->db->prepare('SELECT ifnull(url_noti, "") as url_noti FROM notificacao Where idnotificacao=:idnotificacao');
       $query->bindParam(":idnotificacao", $idnotificacao);
       $query->execute();
       $linhaNotifica = $query->fetchObject();



      $query = $this->db->prepare("UPDATE notificacao SET data_leitura=now() WHERE idnotificacao=:idnotificacao");
      $query->bindParam(":idnotificacao", $idnotificacao);
      $query->execute();

       $this->retornaOK($linhaNotifica->url_noti);
   }
}
