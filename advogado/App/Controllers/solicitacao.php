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
        echo $this->template->twig->render('solicitacao/listagem.html.twig');
    }

    public function formCadastrar($id_adv)
    {
        
        $db = Conexao::connect();
        $sql = "SELECT solicitacoes.id_solicitacoes, solicitacoes.data_hora, solicitacoes.status_solicitacoes, cliente.id_cli, concat(cliente.nome_cli, ' ', cliente.sobrenome_cli) as nome_cli, solicitacoes.descricao 
        From advogado Inner Join solicitacoes On solicitacoes.id_adv = advogado.id_adv Inner Join cliente On solicitacoes.id_cli = cliente.id_cli order by data_hora desc ";
        $resultados = $db->query($sql);
        $solicitacoes = $resultados->fetchAll();


        $sql = "SELECT * FROM solicitacoes WHERE id_adv=:id_adv ";
        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_SESSION["id_adv"]);

        $resultado = $query->execute();

        $linha = $query->fetch();


        echo $this->template->twig->render('solicitacao/cadastrar.html.twig', compact('linha', 'solicitacoes', 'data'));
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

        $sql = "UPDATE solicitacoes SET status_solicitacoes=:status_solicitacoes WHERE id_adv=:id_adv AND id_solicitacoes=:id_solicitacoes";

        $query = $db->prepare($sql);
        $query->bindParam(":status_solicitacoes", $_POST['aprovado']);
        $query->bindParam(":id_adv", $_SESSION['id_adv']);
        $query->bindParam(":id_solicitacoes", $_POST['id_solicitacoes']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $this->retornaOK('Enviado com sucesso!');
            $aprovado = $_POST['aprovado'];
            if ($aprovado == "Aceito") 
            {
                $db = Conexao::connect();

                $solicitacao = $_POST['id_solicitacoes'];
                $tipo_user = "cli";
                $texto = "Sua solicitação foi aceita pelo Advogado";

                $sql = "SELECT solicitacoes.id_cli FROM solicitacoes WHERE id_solicitacoes=$solicitacao";
                $query = $db->prepare($sql);
                $resultado = $query->execute();
                $resultados = $db->query($sql);
                $solicitacoes = $resultados->fetchObject();
                $id_cli = $solicitacoes->id_cli;

                $sql = "INSERT INTO notificacao (id_user, tipo_user, texto) values (:id_user, :tipo_user, :texto)";
                $query = $db->prepare($sql);
                $query->bindParam(":id_user", $id_cli);
                $query->bindParam(":tipo_user", $tipo_user);
                $query->bindParam(":texto", $texto);
                $query->execute();
            }

        } else {
            $this->retornaERRO('Erro ao enviar!');
        }


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


    public function bootgrid($id_solicitacoes)
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM documento WHERE id_solicitacoes=$id_solicitacoes ";

        if ($busca != '') {
            $sql .= " and (
                        nome_doc LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }
    public function aceitarpage($id_solicitacoes)
    {
        $db = Conexao::connect();

        $sql = "SELECT
    solicitacoes.*, concat(cliente.nome_cli, ' ', cliente.sobrenome_cli)as nome_cli,
    cliente.cidade_cli,
    cliente.email_cli
From
    solicitacoes Inner Join
    advogado On solicitacoes.id_adv = advogado.id_adv Inner Join
    cliente On solicitacoes.id_cli = cliente.id_cli Inner Join
    cidade On advogado.cidade_adv = cidade.id_cidade
            And cliente.cidade_cli = cidade.id_cidade WHERE id_solicitacoes=$id_solicitacoes";
        $query = $db->prepare($sql);
        $resultado = $query->execute();
        $resultados = $db->query($sql);

        $solicitacoes = $resultados->fetchObject();
        $data1 = $solicitacoes->data_hora;
        $data = date('d/m/Y', strtotime($data1));


        $sql = "SELECT * FROM documento WHERE Solicitacoes_idSolicitacoes=$id_solicitacoes";
        $resultados = $db->query($sql);
        $docs = $resultados->fetchAll();
    
        echo $this->template->twig->render('solicitacao/aceitar.html.twig', compact('solicitacoes', 'data','docs'));
    }
}
