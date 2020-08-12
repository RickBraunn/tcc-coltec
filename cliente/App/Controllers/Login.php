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

        $nome_usuario_cli = $_POST['nome_usuario_cli'];
        $senha_cli = $_POST['senha_cli'];

        $sql = "SELECT * FROM cliente WHERE nome_usuario_cli=:nome_usuario_cli AND senha_cli=:senha_cli";

        $resultados = $db ->prepare($sql);

        $resultados->bindParam(":nome_usuario_cli", $nome_usuario_cli);
        $resultados->bindParam(":senha_cli", $senha_cli);
        $resultados->execute();
		
        if($resultados->rowCount()==1){
            $linha = $resultados->fetchObject();

            $_SESSION['liberado'] = true;
            $_SESSION['id_cli'] = $linha->id_cli; //Código da Pessoa que está logada
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
