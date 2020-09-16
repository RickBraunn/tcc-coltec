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

        $nome_adm = $_POST['nome_adm'];
        $senha_adm = $_POST['senha_adm'];
        $senha_adm = sha1($senha_adm);

        $sql = "SELECT * FROM administrador WHERE nome_adm=:nome_adm AND senha_adm=:senha_adm";

        $resultados = $db ->prepare($sql);

        $resultados->bindParam(":nome_adm", $nome_adm);
        $resultados->bindParam(":senha_adm", $senha_adm);
        $resultados->execute();
		
        if($resultados->rowCount()==1){
            $linha = $resultados->fetchObject();

            $_SESSION['liberado'] = true;
            $_SESSION['nome_adm'] = $linha->nome_adm;
            $_SESSION['id_adm'] = $linha->id_adm; //Código da Pessoa que está logada
            //$_SESSION['nivel_acesso'] = $linha->nivel_acesso; //Nível
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
