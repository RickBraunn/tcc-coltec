<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Admin Extends Controller
{
    public function index()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('admin/menu.html.twig');
    }

    public function formCadastrar()
    {
//        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('admin/cadastrar.html.twig');
    }

    public function formEditar($id_adm)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM administrador WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adm", $id_adm);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('admin/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO administrador (nome_adm, senha_adm) VALUES (:nome_adm, :senha_adm)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adm", $_POST['nome_adm']);
        $query->bindParam(":senha_adm", $_POST['senha_adm']);

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

        $sql = "UPDATE administrador SET nome_adm=:nome_adm, senha_adm=:senha_adm WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adm", $_POST['nome_adm']);
        $query->bindParam(":senha_adm", $_POST['senha_adm']);
        $query->bindParam(":id_adm", $_POST['id_adm']);
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

        $sql = "DELETE FROM administrador WHERE id_adm=:id_adm";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adm", $_POST['id']);
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
