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
