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
        echo $this->template->twig->render('advogado/avaliar.html.twig');
    }


}
