<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class oab Extends ControllerSeguro
{
    public function index(){

        //        include(ROOT . "/seguranca.php");

        //        $db = Conexao::connect();

    echo $this->template->twig->render('oab/listagem.html.twig');


    }

    public function formCadastrar($id_oab)
    {
        $db = Conexao::connect();


        $sql = "SELECT
                oab.id_oab,
                concat(numero_oab, '/', sigla_estado) as numero_oab,
    advogado.nome_adv,
    estado.sigla_estado,
     oab.status_oab
From
    oab Inner Join
    advogado On oab.id_adv = advogado.id_adv Inner Join  estado On oab.estados_oab = estado.id_estado
Where oab.id_oab = :id_oab";
        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $id_oab);

        $resultado = $query->execute();
        $linha = $query->fetch();

        echo $this->template->twig->render('oab/cadastrar.html.twig', compact('linha'));
    }

    public function formEditar($id_oab)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM estado ORDER BY nome_estado";
        $resultados = $db->query($sql);
        $estados = $resultados->fetchALl();

        $sql = "SELECT * FROM oab WHERE id_oab=:id_oab";
        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $id_oab);

       $resultado = $query->execute();
        /*
        if ($query->rowCount()==0){
            self::errorNotFound('Objeto não encontrado');
        }
        Problema na verificação sempre verificando 0 */
        $linha = $query->fetch();

        echo $this->template->twig->render('oab/editar.html.twig', compact('linha', 'estados'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();
        $sql = "UPDATE oab SET status_oab=:status_oab WHERE id_oab=:id_oab";

        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $_POST['id_oab']);
        $query->bindValue(":status_oab", $_POST['aprovado']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'OAB aprovada com sucesso.';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function salvarEditar(){
        $db = Conexao::connect();
        $sql = "UPDATE oab SET id_oab=:id_oab, id_adv=:id_adv, numero_oab=:numero_oab, estados_oab=:estados_oab, status_oab=:status_oab WHERE id_oab=:id_oab";

        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $_POST['id_oab']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->bindParam(":numero_oab", $_POST['numero_oab']);
        $query->bindParam(":estados_oab", $_POST['estados_oab']);
        $query->bindValue(":status_oab", 'Rejeitado');
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'OAB aprovada com sucesso.';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);
    }
    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM oab WHERE id_adv={$_SESSION['id_adv']}";

        $query = $db->prepare($sql);
        $query->bindParam(":id_oab", $_POST['id_oab']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
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
        $sql = "SELECT oab.*, estado.nome_estado, advogado.nome_adv From oab Inner Join estado On oab.estados_oab = estado.id_estado Inner Join advogado On oab.id_adv = advogado.id_adv";

        if ($busca!=''){
            $sql .= " and (
                        numero_oab LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
