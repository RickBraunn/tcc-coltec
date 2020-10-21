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

        echo $this->template->twig->render('login/index.html.twig');
    }

    public function verificar()
    {
        session_start();

        $db = Conexao::connect();

        $nome_usuario_adv = $_POST['nome_usuario_adv'];
        $senha_adv = $_POST['senha_adv'];
        $senha_adv =sha1($senha_adv);

        $sql = "SELECT
    advogado.nome_adv,
    advogado.nome_usuario_adv,
    advogado.sobrenome_adv,
    advogado.senha_adv,
    advogado.id_adv,
    oab.numero_oab,
    estado.sigla_estado
From
    advogado Inner Join
    oab On oab.id_adv = advogado.id_adv Inner Join
    estado On oab.estados_oab = estado.id_estado WHERE nome_usuario_adv=:nome_usuario_adv AND senha_adv=:senha_adv";

        $resultados = $db ->prepare($sql);

        $resultados->bindParam(":nome_usuario_adv", $nome_usuario_adv);
        $resultados->bindParam(":senha_adv", $senha_adv);
        $resultados->execute();
		
        if($resultados->rowCount()==1){
            $linha = $resultados->fetchObject();
//print_r ($linha);
            $_SESSION['liberado'] = true;
            $_SESSION['nome_usuario_adv'] = $linha->nome_usuario_adv;
            $_SESSION['nome_adv'] = $linha->nome_adv;
            $_SESSION['sobrenome_adv'] = $linha->sobrenome_adv;
            $_SESSION['numero_oab'] = $linha->numero_oab;
            $_SESSION['sigla_estado'] = $linha->sigla_estado;
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
