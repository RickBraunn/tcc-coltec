<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class area Extends ControllerSeguro
{
    public function index(){

        //        include(ROOT . "/seguranca.php");

        //        $db = Conexao::connect();

    echo $this->template->twig->render('area/listagem.html.twig');


    }

    public function formCadastrar()
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM area ORDER BY nome_area";
        $resultados = $db->query($sql);
        $areas = $resultados->fetchALl();



        echo $this->template->twig->render('area/cadastrar.html.twig', compact("areas"));
    }

   /* public function formEditar($id_oab)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM estado ORDER BY nome_estado";
        $resultados = $db->query($sql);
        $estados = $resultados->fetchALl();

        $sql = "SELECT * FROM oab WHERE id_oab=:id_oab AND id_adv=:id_adv";
        $id_adv = $_SESSION["id_adv"];
        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $id_oab);
        $query->bindParam(":id_adv", $id_adv);

       $resultado = $query->execute();

        if ($query->rowCount()==0){
            self::errorNotFound('Objeto não encontrado');
        }
        Problema na verificação sempre verificando 0
        $linha = $query->fetch();

        echo $this->template->twig->render('oab/editar.html.twig', compact('linha', 'estados'));
    }*/

    public function salvarCadastrar(){
        $db = Conexao::connect();

        $sql = "INSERT INTO area_adv (id_area, id_adv  ) VALUES ( :id_area, :id_adv)";

        $query = $db->prepare($sql);
        $query->bindParam(":id_area", $_POST['id_area']);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Area Adicionada com sucesso.';
            \App\Advogado::verificaCadastro($_SESSION['id_adv']);
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);
    }
/*
    public function salvarEditar(){
        $db = Conexao::connect();

        $sql = "UPDATE oab SET id_adv=:id_adv, numero_oab=:numero_oab, estados_oab=:estados_oab WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query->bindParam(":numero_oab", $_POST['numero_oab']);
        $query->bindParam(":estados_oab", $_POST['estados_oab']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'OAB alterada com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);
    }
*/
    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM area_adv WHERE id_adv={$_SESSION['id_adv']} ";

        $query = $db->prepare($sql);
       // $query->bindParam(":id_area", $_POST['id_area']);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'area excluído com sucesso';
            \App\Advogado::verificaCadastro($_SESSION['id_adv']);
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM area_adv WHERE id_adv={$_SESSION['id_adv']} ";

        if ($busca!=''){
            $sql .= " and (
                        id_adv   LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
