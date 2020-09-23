<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Cliente Extends ControllerSeguro
{
    public function index()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('cliente/listagem.html.twig');
    }


    public function formEditar($id_cli)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        $sql = "SELECT * FROM cliente WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cli",$_SESSION["id_cli"]);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('cliente/editar.html.twig', compact('linha', "cidades"));
    }

    public function formSenha($id_cli)
    {
        $db = Conexao::connect();
        echo $this->template->twig->render('cliente/senha.html.twig');
    }
    public function salvarSenha()
    {
        $db = Conexao::connect();
        $senha_ant = $_POST['senha_ant'];
        $senha1 = $_POST['senha1'];
        $senha2 = $_POST['senha2'];
        $senha_ant = sha1($senha_ant);
        $sql = "SELECT senha_cli FROM cliente WHERE senha_cli=:senha_cli";
        $query = $db->prepare($sql);
        $query->bindParam(":senha_cli", $senha_ant);
        $query->execute();
        if ($query->rowCount() == 0) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Senha antiga errada!';
            echo $this->jsonResponse($retorno);
        } elseif ($senha1 == $senha2) {
            $senha_cli = $senha2;
            $senha_cli = sha1($senha_cli);
            $sql = "UPDATE cliente SET senha_cli=:senha_cli WHERE id_cli=:id_cli";
            $query = $db->prepare($sql);
            $query->bindParam(":senha_cli", $senha_cli);
            $query->bindParam(":id_cli", $_SESSION['id_cli']);
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


    public function salvarEditar()
    {
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_cli'];
        $sql = "SELECT nome_usuario_cli FROM cliente WHERE nome_usuario_cli=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
        } else {

        $sql = "UPDATE cliente SET nome_cli=:nome_cli, sobrenome_cli=:sobrenome_cli, email_cli=:email_cli, cidade_cli=:cidade_cli, telefone_cli=:telefone_cli, nome_usuario_cli=:nome_usuario_cli, senha_cli=:senha_cli WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cli", $_POST['nome_cli']);
        $query->bindParam(":sobrenome_cli", $_POST['sobrenome_cli']);
        $query->bindParam(":email_cli", $_POST['email_cli']);
        $query->bindParam(":cidade_cli", $_POST['cidade_cli']);
        $query->bindParam(":telefone_cli", $_POST['telefone_cli']);
        $query->bindParam(":nome_usuario_cli", $_POST['nome_usuario_cli']);
        $query->bindParam(":senha_cli", $_POST['senha_cli']);
        $query->bindParam(":id_cli", $_POST['id_cli']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);}
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM cliente WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cli", $_POST['id']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente excluído com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function arquivos()
    {
header("location: arquivos/");
    }
    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_cli`, `nome_cli`, `sobrenome_cli`, `email_cli`, `cidade_cli`, `telefone_cli`, `nome_usuario_cli`, `senha_cli` FROM cliente WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
