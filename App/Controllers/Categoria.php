<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Categoria Extends Controller
{
    public function index(){

//        include(ROOT . "/seguranca.php");

        $db = Conexao::connect();


        echo $this->template->twig->render('categoria/listagem.html.twig');

    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('categoria/cadastrar.html.twig');
    }

    public function formEditar($id)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM categoria WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":id", $id);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('categoria/editar.html.twig', compact('linha'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();

        $sql = "INSERT INTO categoria (nome) VALUES (:nome)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome", $_POST['nome']);
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

        $sql = "UPDATE categoria SET nome=:nome WHERE id=:id";

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

        $sql = "DELETE FROM categoria WHERE id=:id";

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
        $sql = "SELECT `id`, `nome` FROM categoria WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%' 
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
