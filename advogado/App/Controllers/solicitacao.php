<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;
use App\Download;
use ZipArchive;

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
            $aprovado = $_POST['aprovado'];

            $solicitacao = $_POST['id_solicitacoes'];

            $sql = "SELECT solicitacoes.id_cli FROM solicitacoes WHERE id_solicitacoes=$solicitacao";
            $query = $db->prepare($sql);
            $resultado = $query->execute();
            $resultados = $db->query($sql);

            $solicitacoes = $resultados->fetchObject();
            $id_user = $solicitacoes->id_cli;

            $tipo_user = "cli";
            $url_noti = "/solicitacao/lista/";

            if ($aprovado == "Aceito")
            {
                $texto = "Sua solicitação foi aceita pelo Advogado";
                $icone = "fa-check-square";
                
            }else{
                $texto = "Sua solicitação foi recusada pelo Advogado";
                $icone = "fa-close";
            }
            
           $notificacao = new Notificacao();
           $notificacao->inserir($id_user, $tipo_user, $texto, $url_noti, $icone);


            $this->retornaOK('Enviado com sucesso!');

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

    public function aceitarpage($id_solicitacoes)
    {
        $db = Conexao::connect();

        $sql = "SELECT
    solicitacoes.*, concat(cliente.nome_cli, ' ', cliente.sobrenome_cli)as nome_cli,
    cliente.cidade_cli,
    cliente.email_cli,
    cliente.telefone_cli
From
    solicitacoes Inner Join
    advogado On solicitacoes.id_adv = advogado.id_adv Inner Join
    cliente On solicitacoes.id_cli = cliente.id_cli Inner Join
    cidade On cliente.cidade_cli = cidade.id_cidade
             WHERE id_solicitacoes=$id_solicitacoes";
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

    public function downloadArquivo($id_documento)
    {
        $db = Conexao::connect();
        $sql = "SELECT * FROM documento INNER JOIN solicitacoes ON Solicitacoes_idSolicitacoes=id_solicitacoes WHERE id_documento=:id_documento";
        $query = $db->prepare($sql);
        $query->bindParam(':id_documento', $id_documento);
        $query->execute();

        if ($query->rowCount()==0) $this->errorNotFound();

        $doc = $query->fetchObject();

        if (!$this->checarProprietarioSolicitacao($doc->id_adv)) $this->errorNotFound();

        $download = new Download();
        $download->solicitacao($doc->Solicitacoes_idSolicitacoes, $doc->nome_doc);
        $download->download();
    }

    public function downloadTodos($id_solicitacoes)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM solicitacoes WHERE id_solicitacoes=:id_solicitacoes";
        $query = $db->prepare($sql);
        $query->bindParam(':id_solicitacoes', $id_solicitacoes);
        $query->execute();
        $solicitacao = $query->fetchObject();

        if (!$this->checarProprietarioSolicitacao($solicitacao->id_adv)) $this->errorNotFound();

        $sql = "SELECT * FROM documento WHERE Solicitacoes_idSolicitacoes=:id_solicitacoes";
        $query = $db->prepare($sql);
        $query->bindParam(':id_solicitacoes', $id_solicitacoes);
        $query->execute();

        if ($query->rowCount()==0) $this->errorNotFound();

        $zip = new ZipArchive();
        $filename = DIR_SOLICITACAO . uniqid() . ".zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            $this->errorNotFound('Não foi possível compactar os arquivos');
        }

        while($doc = $query->fetchObject()){
            $zip->addFile($this->diretorioSolicitacao($id_solicitacoes) . $doc->nome_doc, $doc->nome_doc);
        }
        $zip->close();

        $download = new Download();
        $download->setArquivo($filename);
        $download->setNomeOriginal(sprintf('solicitacao-%05d.zip', $id_solicitacoes));
        if ($download->download()) unlink($filename);
    }

    private function checarProprietarioSolicitacao($id_adv)
    {
        return ($id_adv == $_SESSION['id_adv']);
    }

    private function diretorioSolicitacao($id_solicitacoes)
    {
        return DIR_SOLICITACAO . $id_solicitacoes . '/';
    }

}

