<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Solicitacao extends ControllerSeguro
{
    public function index()
    {
        //        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('cliente/listagem.html.twig');
    }

    public function formCadastrar($id_adv)
    {
        
        $db = Conexao::connect();
        $sql = "SELECT solicitacoes.id_solicitacoes, solicitacoes.status, cliente.id_cli, concat(cliente.nome_cli, ' ', cliente.sobrenome_cli) as nome_cli, solicitacoes.descricao 
        From advogado Inner Join solicitacoes On solicitacoes.id_adv = advogado.id_adv Inner Join cliente On solicitacoes.id_cli = cliente.id_cli ";
        $resultados = $db->query($sql);
        $solicitacoes = $resultados->fetchALl();

        $sql = "SELECT * FROM solicitacoes WHERE id_adv=:id_adv";
        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);

        $resultado = $query->execute();

        $linha = $query->fetch();


        echo $this->template->twig->render('solicitacao/cadastrar.html.twig', compact('linha', 'solicitacoes'));
    }

   /* public function formEditar($id_cli)
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
*/


    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO solicitacoes (descricao, id_cli, id_adv  ) VALUES (:descricao, :id_cli, :id_adv)";

        $query = $db->prepare($sql);
        $query->bindParam(":descricao", $_POST['descricao']);
        $query->bindParam(":id_cli", $_SESSION['id_cli']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Solicitação cadastrada com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);
    }
/*
    public function salvarEditar()
    {
        $db = Conexao::connect();

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

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente alterado com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);
    }
*/
    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM cliente WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cli", $_POST['id']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente excluído com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_cli`, `nome_cli`, `sobrenome_cli`, `email_cli`, `cidade_cli`, `telefone_cli`, `nome_usuario_cli`, `senha_cli` FROM cliente WHERE 1 ";

        if ($busca != '') {
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }
}
