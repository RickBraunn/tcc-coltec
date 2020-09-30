<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Advogado Extends Controller
{
    public function index(){

        include(ROOT . "/seguranca.php");

        $db = Conexao::connect();


        echo $this->template->twig->render('advogado/listagem.html.twig');

    }

    public function formcadastrar()
    {

        $db = Conexao::connect();


        $sql = "SELECT     
     concat(advogado.nome_adv, ' ', advogado.sobrenome_adv) as nome_adv,
    advogado.formacao,
    advogado.foto,
    concat(oab.numero_oab,'/', estado.sigla_estado ) as numero_oab
     FROM advogado Inner Join
    oab On oab.id_adv = advogado.id_adv Inner Join
    estado On oab.estados_oab = estado.id_estado Inner Join
    area_adv On area_adv.id_adv = advogado.id_adv";
        $resultados = $db->query($sql);
        $adv = $resultados->fetchALl();

        $sql = "SELECT
    area.nome_area
From
    area Inner Join
    area_adv On area_adv.id_area = area.id_area Inner Join
    advogado On area_adv.id_adv = advogado.id_adv";
        $resultados = $db->query($sql);
        $area = $resultados->fetchALl();

        echo $this->template->twig->render('advogado/cadastrar.html.twig', compact("adv", "area"));
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

}
