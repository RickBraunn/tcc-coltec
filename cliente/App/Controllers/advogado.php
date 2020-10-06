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
    advogado.foto
     FROM advogado 
     WHERE advogado.cadastro_completo=1";
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

        $sql = "SELECT *, concat(advogado.nome_adv, ' ', advogado.sobrenome_adv) as nome_adv FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $id_adv);
        $resultado = $query->execute();

        if ($query->rowCount() == 0) $this->errorNotFound('Advogado não encontrado');
        
        $linha = $query->fetchObject();

        echo $this->template->twig->render('advogado/avaliar.html.twig', compact('id_adv', 'linha'));
    }

    public function salvaravaliar()
    {

        $db = Conexao::connect();

        $sql = "INSERT INTO avaliacao (titulo, descricao, nota, id_cli, id_adv) VALUES :titulo, :descricao, :nota, :id_cli, :id_adv ";

        $query = $db->prepare($sql);
        $query->bindParam(":titulo", $_POST['titulo']);
        $query->bindParam(":descricao", $_POST['descricao']);
        $query->bindParam(":nota", $_POST['nota']);
        $query->bindParam(":id_cli", $_POST['id_cli']);
        $query->bindParam(":id_adv", $_POST['id_adv']);

        if ($query->rowCount() == 0) {
            $this->retornaErro('Erro ao enviar Avaliação!');
        } else{
            $this->retornaOK('Avaliação Enviada com sucesso, Obrigado!');
        }

    }
}
