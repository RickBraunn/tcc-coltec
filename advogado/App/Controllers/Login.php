<?php

namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Login Extends Controller
{
    public function index()
    {
//        include(ROOT . "/seguranca.php");

        //$db = Conexao::connect();

        echo $this->template->twig->render('login/login.html.twig');
    }

    public function verificar()
    {
        session_start();

        $db = Conexao::connect();

        $nome_usuario_adv = $_POST['nome_usuario_adv'];
        $senha_adv = $_POST['senha_adv'];
        $senha_adv =sha1($senha_adv);

        $sql = "SELECT * FROM advogado WHERE nome_usuario_adv=:nome_usuario_adv AND senha_adv=:senha_adv";

        $resultados = $db ->prepare($sql);

        $resultados->bindParam(":nome_usuario_adv", $nome_usuario_adv);
        $resultados->bindParam(":senha_adv", $senha_adv);
        $resultados->execute();
		
        if($resultados->rowCount()==1){
            $linha = $resultados->fetchObject();
//print_r ($linha);
            $_SESSION['liberado'] = true;
            $_SESSION['nome_usuario_adv'] = $linha->nome_usuario_adv;
            $_SESSION['id_adv'] = $linha->id_adv; //Código da Pessoa que está logada
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Acesso autorizado!';

        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Dados inválidos!';
            $_SESSION['liberado'] = false;
        }
        $this->jsonResponse($retorno);
    }

    public function sair()
    {
        session_start();
        //unset($_SESSION['liberado']);
        session_destroy();

        header("Location: /login");
    }
}
?>
