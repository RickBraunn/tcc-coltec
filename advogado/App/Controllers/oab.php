<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class oab Extends ControllerSeguro
{
    public function index(){

        //        include(ROOT . "/seguranca.php");

        //        $db = Conexao::connect();

    echo $this->template->twig->render('oab/listagem.html.twig');


    }

    public function formCadastrar()
    {
        
        echo $this->template->twig->render('oab/cadastrar.html.twig');
    }

    public function formEditar($id_oab)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM oab WHERE id_oab=:id_oab AND id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $id_oab);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);
        $resultado = $query->execute();

        if ($query->rowCount()==0){
            self::errorNotFound('Objeto não encontrado');
        }

        $linha = $query->fetch();

        echo $this->template->twig->render('oab/editar.html.twig', compact('linha'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();

        $sql = "INSERT INTO oab ( numero_oab, estados_oab, id_adv  ) VALUES ( :numero_oab, :estados_oab, :id_adv)";

        $query = $db->prepare($sql);
        $query->bindParam(":numero_oab", $_POST['numero_oab']);
        $query->bindParam(":estados_oab", $_POST['estados_oab']);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'OAB cadastrado com sucesso, aguardando aprovação.';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

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

    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM oab WHERE id_adv={$_SESSION['id_adv']}";

        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $_POST['id_oab']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Excluído com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM oab WHERE id_adv={$_SESSION['id_adv']} ";

        if ($busca!=''){
            $sql .= " and (
                        numero_oab LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
