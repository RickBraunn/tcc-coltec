<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Cliente Extends Controller
{
    public function index()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('cliente/listagem.html.twig');
    }

    public function formCadastrar()
    {
        $db = Conexao::connect();


        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();
       


        echo $this->template->twig->render('cliente/cadastrar.html.twig', compact("cidades"));
       
    }

    public function formEditar($id_cli)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        $sql = "SELECT * FROM cliente WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cli", $id_cli);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('cliente/editar.html.twig', compact('linha', "cidades"));
    }



    public function salvarCadastrar()
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
            $senha = $_POST['senha_cli'];
            $senha = sha1($senha);

        $sql = "INSERT INTO cliente (nome_cli, sobrenome_cli, email_cli, cidade_cli, telefone_cli, nome_usuario_cli, senha_cli  ) VALUES (:nome_cli, :sobrenome_cli, :email_cli,  :cidade_cli, :telefone_cli, :nome_usuario_cli, :senha_cli)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cli", $_POST['nome_cli']);
        $query->bindParam(":sobrenome_cli", $_POST['sobrenome_cli']);
        $query->bindParam(":email_cli", $_POST['email_cli']);
        $query->bindParam(":cidade_cli", $_POST['cidade_cli']);
        $query->bindParam(":telefone_cli", $_POST['telefone_cli']);
        $query->bindParam(":nome_usuario_cli", $_POST['nome_usuario_cli']);
        $query->bindParam(":senha_cli", $senha);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente cadastrado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);}
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


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT
        cliente.id_cli,
    cliente.nome_cli,
    cliente.sobrenome_cli,
    cliente.email_cli,
    cliente.cidade_cli,
    cliente.telefone_cli,
    cliente.nome_usuario_cli,
    cliente.senha_cli,
    cidade.Nome
From
    cidade Inner Join
    cliente On cliente.cidade_cli = cidade.id_cidade";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
