<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Tipo Extends Controller
{
    public function index()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('tipo/listagem.html.twig');
    }

    public function formCadastrar()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('tipo/cadastrar.html.twig');
    }

    public function formEditar($id)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM tipo WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":id", $id);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('tipo/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO tipo (nome) VALUES (:nome)";

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

        $this->jsonResponse($retorno);
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE tipo SET nome=:nome WHERE id=:id";

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

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM tipo WHERE id=:id";

        $query = $db->prepare($sql);
        $query->bindParam(":id", $_POST['id']);
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
        $sql = "SELECT `id`, `nome` FROM tipo WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%' 
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
