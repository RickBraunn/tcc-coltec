<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Admin Extends ControllerSeguro
{

    public function index()
    {
        echo $this->template->twig->render('admin/menu.html.twig');
        //echo $this->template->twig->render('admin/listagem.html.twig');
    }

    public function formCadastrar()
    {

        echo $this->template->twig->render('admin/cadastrar.html.twig');
    }

    public function formEditar()
    {
        
        $db = Conexao::connect();

        $sql = "SELECT * FROM administrador WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adm",$_SESSION["id_adm"]);
        $resultado = $query->execute();
        $linha = $query->fetch();
        echo $this->template->twig->render('admin/editar.html.twig', compact('linha'));
    }
    public function formSenha($id_adm)
    {
        $db = Conexao::connect();
        echo $this->template->twig->render('admin/senha.html.twig');
    }
    public function salvarSenha()
    {
        $db = Conexao::connect();
        $senha_ant = $_POST['senha_ant'];
        $senha1 = $_POST['senha1'];
        $senha2 = $_POST['senha2'];
        $senha_ant = sha1($senha_ant);
        $sql = "SELECT senha_adm FROM administrador WHERE senha_adm=:senha_adm";
        $query = $db->prepare($sql);
        $query->bindParam(":senha_adm", $senha_ant);
        $query->execute();
        if ($query->rowCount() == 0) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Senha antiga errada!';
            echo $this->jsonResponse($retorno);
        } elseif ($senha1 == $senha2) {
            $senha_adm = $senha2;
            $senha_adm = sha1($senha_adm);
            $sql = "UPDATE administrador SET senha_adm=:senha_adm WHERE id_adm=:id_adm";
            $query = $db->prepare($sql);
            $query->bindParam(":senha_adm", $senha_adm);
            $query->bindParam(":id_adm", $_SESSION['id_adm']);
            $query->execute();
            if ($query->rowCount() == 1) {
                $retorno['status'] = 1;
                $retorno['mensagem'] = 'Senha alterada com sucesso';
            } else {
                $retorno['status'] = 0;
                $retorno['mensagem'] = 'Nenhum dado alterado';
            }
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Informe senhas iguais';
        }
        echo $this->jsonResponse($retorno);
    }


    public function salvarCadastrar()
    {
        
        $db = Conexao::connect();
        $nome = $_POST['nome_adm'];
        $sql = "SELECT nome_adm FROM administrador WHERE nome_adm=:nome_adm";
        $query = $db->prepare($sql);
        $query->bindParam(":nome_adm", $nome);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome ja Existente';
            echo $this->jsonResponse($retorno);
        }else{
            $senha = $_POST['senha_adm'];
            $senha = sha1($senha);

        $sql = "INSERT INTO administrador (nome_adm, senha_adm) VALUES (:nome_adm, :senha_adm)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adm", $_POST['nome_adm']);
        $query->bindParam(":senha_adm", $senha);

        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Administrador cadastrado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);}
    }

    public function salvarEditar()
    {
      
        $db = Conexao::connect();
        $nome = $_POST['nome_adm'];
        $sql = "SELECT nome_adm FROM administrador WHERE nome_adm=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome ja Existente';
        } else {

        $sql = "UPDATE administrador SET nome_adm=:nome_adm, senha_adm=:senha_adm WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adm", $_POST['nome_adm']);
        $query->bindParam(":senha_adm", $_POST['senha_adm']);
        $query->bindParam(":id_adm", $_POST['id_adm']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Administrador alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);}
    }

    public function excluir()
    {
        
        $db = Conexao::connect();

        $sql = "DELETE FROM administrador WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adm", $_POST['id']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Administrador excluído com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }


    public function bootgrid()
    {
        
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM administrador WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
