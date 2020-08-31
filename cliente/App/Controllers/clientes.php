<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Clientes Extends Controller
{
    public function index()
    {
        //        include(ROOT . "/seguranca.php");
        echo $this->template->twig->render('cliente/cadastrar.html.twig');
    }

    public function formCadastrar()
    {
        $db = Conexao::connect();


        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();



        echo $this->template->twig->render('cliente/cadastrar.html.twig', compact("cidades"));
    }

    public function salvarCadastrar()
    {
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_cli'];
        $sql = "SELECT nome_usuario_cli FROM cliente WHERE nome_usuario_cli=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
        } else {
            $senha = $_POST['senha_cli'];
            $senha = sha1($senha);

        $sql = "INSERT INTO cliente (nome_cli, sobrenome_cli, email_cli, cidade_cli, telefone_cli, nome_usuario_cli, senha_cli  ) VALUES (:nome_cli, :sobrenome_cli, :email_cli,  :cidade_cli, :telefone_cli, :nome_usuario_cli, :senha_cli)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cli", $_POST['nome_cli']);
        $query->bindParam(":sobrenome_cli", $_POST['sobrenome_cli']);
        $query->bindParam(":email_cli", $_POST['email_cli']);
        $query->bindParam(":cidade_cli", $_POST['cidade_cli']);
        $query->bindParam(":telefone_cli", $_POST['telefone_cli']);
        $query->bindParam(":nome_usuario_cli", $_POST['nome_usuario_cli']);
        $query->bindParam(":senha_cli", $senha);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Cliente cadastrado com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        $this->jsonResponse($retorno);}
    }

}