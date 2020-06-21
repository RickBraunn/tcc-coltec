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
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('cliente/cadastrar.html.twig');
    }

    public function formEditar($idCliente)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cliente WHERE idCliente=:idCliente";

        $query = $db->prepare($sql);
        $query->bindParam(":idCliente", $idCliente);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('cliente/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO cliente (nome_cli, sobrenome_cli, email_cli, estados_cli, Cidades_idCidades, telefone_cli, nome_usuario_cli, senha_cli  ) VALUES (:nome_cli, :sobrenome_cli, :email_cli, :estados_cli, :Cidades_idCidades, :telefone_cli, :nome_usuario_cli, :senha_cli)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cli", $_POST['nome_cli']);
        $query->bindParam(":sobrenome_cli", $_POST['sobrenome_cli']);
        $query->bindParam(":email_cli", $_POST['email_cli']);
        $query->bindParam(":estados_cli", $_POST['estados_cli']);
        $query->bindParam(":Cidades_idCidades", $_POST['Cidades_idCidades']);
        $query->bindParam(":telefone_cli", $_POST['telefone_cli']);
        $query->bindParam(":nome_usuario_cli", $_POST['nome_usuario_cli']);
        $query->bindParam(":senha_cli", $_POST['senha_cli']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Tipo cadastrado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE cliente SET idCliente=:idCliente nome_cli=:nome_cli, sobrenome_cli=:sobrenome_cli, email_cli=:email_cli, estados_cli=:estados_cli, Cidades_idCidades=:Cidades_idCidades, telefone_cli=:telefone_cli, nome_usuario_cli=:nome_usuario_cli, senha_cli=:senha_cli WHERE idCliente=:idCliente";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cli", $_POST['nome_cli']);
        $query->bindParam(":sobrenome_cli", $_POST['sobrenome_cli']);
        $query->bindParam(":email_cli", $_POST['email_cli']);
        $query->bindParam(":estados_cli", $_POST['estados_cli']);
        $query->bindParam(":Cidades_idCidades", $_POST['Cidades_idCidades']);
        $query->bindParam(":telefone_cli", $_POST['telefone_cli']);
        $query->bindParam(":nome_usuario_cli", $_POST['nome_usuario_cli']);
        $query->bindParam(":senha_cli", $_POST['senha_cli']);
        $query->bindParam(":idCliente", $_POST['idCliente']);
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

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM cliente WHERE idCliente=:idCliente";

        $query = $db->prepare($sql);
        $query->bindParam(":idCliente", $_POST['idCliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Excluído com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `idCliente`, `nome_cli`, `sobrenome_cli`, `email_cli`, `estados_cli`, `Cidades_idCidades`, `telefone_cli`, `nome_usuario_cli`, `senha_cli` FROM cliente WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
