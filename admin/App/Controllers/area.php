<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Area Extends Controller
{
    //public function __construct()
    //{
    //    include(ROOT . "/seguranca.php");
    //}
// testar dps
    public function index()
    {
        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('area/listagem.html.twig');
    }

    public function formCadastrar()
    {
        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('area/cadastrar.html.twig');
    }

    public function formEditar($id_area)
    {
        
        $db = Conexao::connect();

        $sql = "SELECT * FROM area WHERE id_area=:id_area";

        $query = $db->prepare($sql);
        $query->bindParam(":id_area", $id_area);
        $resultado = $query->execute();

        $linha = $query->fetch();
        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('area/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        
        $db = Conexao::connect();

        $sql = "INSERT INTO area (nome_area, descricao) VALUES (:nome_area, :descricao)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_area", $_POST['nome_area']);
        $query->bindParam(":descricao", $_POST['descricao']);

        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Area cadastrada com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);
    }

    public function salvarEditar()
    {
      
        $db = Conexao::connect();

        $sql = "UPDATE area SET nome_area=:nome_area, descricao=:descricao WHERE id_area=:id_area";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_area", $_POST['nome_area']);
        $query->bindParam(":descricao", $_POST['descricao']);
        $query->bindParam(":id_area", $_POST['id_area']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Area alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

        echo $this->jsonResponse($retorno);
    }

    public function excluir()
    {
        
        $db = Conexao::connect();

        $sql = "DELETE FROM area WHERE id_area=:id_area";

        $query = $db->prepare($sql);
        $query->bindParam(":id_area", $_POST['id']);
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
        $sql = "SELECT * FROM area WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
