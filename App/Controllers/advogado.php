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

    public function formEditar($id)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM advogado WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":id", $id);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('advogado/editar.html.twig', compact('linha'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();

        $sql = "INSERT INTO advogado (nome_adv, sobrenome_adv, email_adv, estados_adv, Cidades_idCidades, telefone_adv, nome_usuario_adv, senha_adv, formacao  ) VALUES (:nome_adv, :sobrenome_adv, :email_adv, :estados_adv, :Cidades_idCidades, :telefone_adv, :nome_usuario_adv, :senha_adv, :formacao)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":estados_adv", $_POST['estados_adv']);
        $query->bindParam(":Cidades_idCidades", $_POST['Cidades_idCidades']);
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

        $sql = "UPDATE advogado SET nome=:nome WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":nome", $_POST['nome']);
        $query->bindParam(":id", $_POST['id']);
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

        $sql = "DELETE FROM advogado WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":id", $_POST['id']);
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
        $sql = "SELECT `id`, `nome` FROM advogado WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
