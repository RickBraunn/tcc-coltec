<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Advogado Extends ControllerSeguro
{
    public function index(){


        //echo $this->template->twig->render('advogado/listagem.html.twig');
        echo $this->template->twig->render('advogado/menu.html.twig');
    }

    

    public function formEditar($id_adv)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        $sql = "SELECT * FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv",$_SESSION["id_adv"]);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('advogado/editar.html.twig', compact('linha', 'cidades'));
    }

   

    public function salvarEditar(){
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_adv'];
        $sql = "SELECT nome_usuario_adv FROM advogado WHERE nome_usuario_adv=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
        } else {
        $sql = "UPDATE advogado SET nome_adv=:nome_adv, sobrenome_adv=:sobrenome_adv, email_adv=:email_adv, cidade_adv=:cidade_adv, telefone_adv=:telefone_adv, nome_usuario_adv=:nome_usuario_adv, formacao=:formacao WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":cidade_adv", $_POST['cidade_adv']);
        $query->bindParam(":telefone_adv", $_POST['telefone_adv']);
        $query->bindParam(":nome_usuario_adv", $_POST['nome_usuario_adv']);
        $query->bindParam(":formacao", $_POST['formacao']);
        //$query->bindParam(":senha_adv", $_POST['senha_adv']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Advogado alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);}
    }

    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_POST['id']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Advogado excluído com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM advogado WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
