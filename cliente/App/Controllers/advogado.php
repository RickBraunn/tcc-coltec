<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Advogado Extends ControllerSeguro
{
    public function index(){



        //echo $this->template->twig->render('advogado/formcadastrar.html.twig');
        header('location: /advogado/formcadastrar');

    }

    public function formcadastrar()
    {

        $db = Conexao::connect();

        $sql = "SELECT  
    advogado.id_adv,   
     concat(advogado.nome_adv, ' ', advogado.sobrenome_adv) as nome_adv,
    advogado.formacao,
    advogado.foto,
    AVG(avaliacao.nota) as nota
     FROM  advogado FULL OUTER JOIN
    avaliacao On avaliacao.id_adv = advogado.id_adv 
     WHERE advogado.cadastro_completo='1'";
        $resultados = $db->query($sql);
        $advs = $resultados->fetchALl();

        foreach ($advs as &$adv){
            $sql = "SELECT  area.nome_area
                    From
                        area Inner Join
                        area_adv On area_adv.id_area = area.id_area  
                        WHERE area_adv.id_adv={$adv['id_adv']}";
            $resultados = $db->query($sql);
            $adv['areas'] = $resultados->fetchALl();

            $sql = "SELECT  concat(oab.numero_oab, '/', estado.sigla_estado) as numero_oab
                    From
                        oab Inner Join estado On oab.estados_oab = estado.id_estado   
                        WHERE oab.id_adv={$adv['id_adv']} AND status_oab='Aprovado'";
            $resultados = $db->query($sql);
            $adv['oabs'] = $resultados->fetchALl();

        }

        echo $this->template->twig->render('advogado/cadastrar.html.twig', compact("advs"));
    }

    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM advogado WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

    public function formavaliar($id_adv)
    {
        $db = Conexao::connect();

        $sql = "SELECT
        avaliacao.nota,
        avaliacao.titulo,
        avaliacao.descricao,
        avaliacao.data_avali,
        avaliacao.id_adv,
        avaliacao.id_cli,
        concat(advogado.nome_adv, ' ', advogado.sobrenome_adv) as nome_adv
    From
     avaliacao Inner Join
        advogado On avaliacao.id_adv = advogado.id_adv 
    WHERE id_cli=:id_cli";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cli", $_SESSION['id_cli']);

        $resultado = $query->execute();

        

        if ($query->rowCount()== 1){

            $linha = $query->fetchObject();
            $data1 = $linha->data_avali;
            $data = date('d/m/Y', strtotime($data1));

            echo $this->template->twig->render('advogado/avaliado.html.twig', compact('id_adv', 'linha', 'data'));
        }else{

        $sql = "SELECT *, concat(advogado.nome_adv, ' ', advogado.sobrenome_adv) as nome_adv FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $id_adv);
        $resultado = $query->execute();

        if ($query->rowCount() == 0) $this->errorNotFound('Advogado não encontrado');
        
        $linha = $query->fetchObject();

        echo $this->template->twig->render('advogado/avaliar.html.twig', compact('id_adv', 'linha'));
        }
    }

    public function salvaravaliar()
    {

        $db = Conexao::connect();

        $sql = "INSERT INTO avaliacao (nota, titulo, descricao, id_adv, id_cli) VALUES (:nota, :titulo, :descricao, :id_adv, :id_cli) ";
        //var_dump($_POST);
        $query = $db->prepare($sql);
        $query->bindParam(":nota", $_POST['nota']);
        $query->bindParam(":titulo", $_POST['titulo']);
        $query->bindParam(":descricao", $_POST['descricao']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->bindParam(":id_cli", $_POST['id_cli']);
        $resultado = $query->execute();

        if ($query->rowCount() == 0) {
            $this->retornaErro('Erro ao enviar Avaliação!');
        } else{
            $this->retornaOK('Avaliação Enviada com sucesso, Obrigado!');
        }

    }
}
