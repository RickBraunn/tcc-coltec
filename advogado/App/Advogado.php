<?php


namespace App;

use App\Conexao;

class Advogado
{
    static public function verificaCadastro($id_adv)
    {
        /*
         * conecta no BD
         * consulta SELECT * FROM area_adv WHERE area_adv.id_adv=:id_adv;
         * consulta SELECT * FROM oab WHERE oab.id_adv=:id_adv AND oab.status_oab='Aprovado';
         *         if (OK os 2){
         *             $cadastro_completo = 1;
         *         }else{
         *              $cadastro_completo = 0;
         *          }
         *          UPDATE advogado SET cadastro_completo=:cadastro_completo WHERE id_adv=:id_adv
         */
        $db = Conexao::connect();
        $sql = "SELECT * FROM area_adv WHERE area_adv.id_adv=:id_adv";
        $query1 = $db->prepare($sql);
        $query1->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query1->execute();

        $sql = "SELECT * FROM oab WHERE oab.id_adv=:id_adv AND oab.status_oab='Aprovado'";
        $query2 = $db->prepare($sql);
        $query2->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query2->execute();
        
        if ($query1->rowCount() == 1 || $query2->rowCount() >= 1 ){
            $cadastro_completo = "1";
            $sql = "UPDATE advogado SET cadastro_completo=:cadastro_completo WHERE id_adv=:id_adv";
            $query = $db->prepare($sql);
            $query->bindParam(":id_adv", $_SESSION["id_adv"]);
            $query->bindParam(":cadastro_completo", $cadastro_completo);
            $query->execute();
        } 
    }

}
