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


        //echo $this->template->twig->render('inicial.html.twig');
        header('location: /advogado');
    }


    public function edit($id){
        if ($id==''){
            $this->errorNotFound('objeto não encontrado');
        }

        $a = [ 'aaa' => '54654'];
        echo $this->jsonResponse($a);

    }
}
