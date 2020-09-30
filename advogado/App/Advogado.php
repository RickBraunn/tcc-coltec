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

    }

}
