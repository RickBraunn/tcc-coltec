<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Advogado Extends Controller
{
    public function index(){

//        include(ROOT . "/seguranca.php");

        $db = Conexao::connect();


        echo $this->template->twig->render('advogado/listagem.html.twig');

    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('advogado/cadastrar.html.twig');
    }

    public function formEditar($id_adv)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $id_adv);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('advogado/editar.html.twig', compact('linha'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();

        $sql = "INSERT INTO advogado (nome_adv, sobrenome_adv, email_adv, estado_adv, cidade_adv, telefone_adv, nome_usuario_adv, senha_adv, formacao  ) VALUES (:nome_adv, :sobrenome_adv, :email_adv, :estado_adv, :cidade_adv, :telefone_adv, :nome_usuario_adv, :senha_adv, :formacao)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":estado_adv", $_POST['estado_adv']);
        $query->bindParam(":cidade_adv", $_POST['cidade_adv']);
        $query->bindParam(":telefone_adv", $_POST['telefone_adv']);
        $query->bindParam(":nome_usuario_adv", $_POST['nome_usuario_adv']);
        $query->bindParam(":senha_adv", $_POST['senha_adv']);
        $query->bindParam(":formacao", $_POST['formacao']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Tipo cadastrado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function salvarEditar(){
        $db = Conexao::connect();

        $sql = "UPDATE advogado SET nome_adv=:nome_adv, sobrenome_adv=:sobrenome_adv, email_adv=:email_adv, estado_adv=:estado_adv, cidade_adv=:cidade_adv, telefone_adv=:telefone_adv, nome_usuario_adv=:nome_usuario_adv, senha_adv=:senha_adv WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":estado_adv", $_POST['estados_adv']);
        $query->bindParam(":cidade_adv", $_POST['Cidades_idCidades']);
        $query->bindParam(":telefone_adv", $_POST['telefone_adv']);
        $query->bindParam(":nome_usuario_adv", $_POST['nome_usuario_adv']);
        $query->bindParam(":senha_adv", $_POST['senha_adv']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Tipo alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);
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