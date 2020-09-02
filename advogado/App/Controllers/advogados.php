<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Advogados Extends Controller
{
public function formCadastrar()
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        echo $this->template->twig->render('advogado/cadastrar.html.twig', compact('cidades'));
    }
     public function salvarCadastrar(){
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_adv'];
        $sql = "SELECT nome_usuario_adv FROM advogado WHERE nome_usuario_adv=:nome_usuario_adv";
        $query = $db->prepare($sql);
        $query->bindParam(":nome_usuario_adv", $nome);
        $query->execute();
        
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
            echo $this->jsonResponse($retorno);
        } else {
        $senha = $_POST['senha_adv'];
        $senha = sha1($senha);
        $sql = "INSERT INTO advogado (nome_adv, sobrenome_adv, email_adv, cidade_adv, telefone_adv, nome_usuario_adv, senha_adv, formacao  ) VALUES (:nome_adv, :sobrenome_adv, :email_adv, :cidade_adv, :telefone_adv, :nome_usuario_adv, :senha_adv, :formacao)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":cidade_adv", $_POST['cidade_adv']);
        $query->bindParam(":telefone_adv", $_POST['telefone_adv']);
        $query->bindParam(":nome_usuario_adv", $_POST['nome_usuario_adv']);
        $query->bindParam(":senha_adv", $senha);
        $query->bindParam(":formacao", $_POST['formacao']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Advogado cadastrado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao inserir os dados';
        }

        echo $this->jsonResponse($retorno);}
    }
}