<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\ControllerSeguro;

class Index Extends ControllerSeguro
{
    public function index(){

//        include(ROOT . "/seguranca.php");

//        $db = Conexao::connect();
//        $resultado = $db->query("SHOW TABLES");
//        $tabela = $resultado->fetchAll();

        header('location: /advogado');



    }

}
