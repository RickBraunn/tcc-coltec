<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;
use App\Advogado as AdvogadoVerifica;

class Advogado Extends ControllerSeguro
{
    public function index(){


        echo $this->template->twig->render('advogado/listagem.html.twig');

    }

    public function formCadastrar()
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        echo $this->template->twig->render('advogado/cadastrar.html.twig', compact('cidades'));
    }

    public function formEditar($id_adv)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM cidade ORDER BY nome";
        $resultados = $db->query($sql);
        $cidades = $resultados->fetchALl();

        $sql = "SELECT * FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv",$id_adv);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('advogado/editar.html.twig', compact('linha', 'cidades'));
    }

    public function salvarCadastrar(){
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_adv'];
        $sql = "SELECT nome_usuario_adv FROM advogado WHERE nome_usuario_adv=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
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

    public function salvarEditar(){
        $db = Conexao::connect();
        $nome = $_POST['nome_usuario_adv'];
        $sql = "SELECT nome_usuario_adv FROM advogado WHERE nome_usuario_adv=$nome";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 1) {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nome de Usuario ja Existente';
        } else {
        $sql = "UPDATE advogado SET nome_adv=:nome_adv, sobrenome_adv=:sobrenome_adv, email_adv=:email_adv, cidade_adv=:cidade_adv, telefone_adv=:telefone_adv, nome_usuario_adv=:nome_usuario_adv, senha_adv=:senha_adv WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_adv", $_POST['nome_adv']);
        $query->bindParam(":sobrenome_adv", $_POST['sobrenome_adv']);
        $query->bindParam(":email_adv", $_POST['email_adv']);
        $query->bindParam(":cidade_adv", $_POST['Cidades_idCidades']);
        $query->bindParam(":telefone_adv", $_POST['telefone_adv']);
        $query->bindParam(":nome_usuario_adv", $_POST['nome_usuario_adv']);
        $query->bindParam(":senha_adv", $_POST['senha_adv']);
        $query->bindParam(":id_adv", $_POST['id_adv']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Advogado alterado com sucesso';
        }else{
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Nenhum dado alterado';
        }

          echo $this->jsonResponse($retorno);}
    }

    public function excluir()
    {
        $db = Conexao::connect();

        $sql = "DELETE FROM advogado WHERE id_adv=:id_adv";

        $query = $db->prepare($sql);
        $query->bindParam(":id_adv", $_POST['id']);
        $query->execute();

        if ($query->rowCount() == 1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Advogado excluído com sucesso';
        } else {
            $retorno['status'] = 0;
            $retorno['mensagem'] = 'Erro ao excluir os dados';
        }

        echo $this->jsonResponse($retorno);
    }

    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT
    cidade.Nome,
    cidade.estado_idestados,
    advogado.id_adv,
    advogado.nome_adv,
    advogado.sobrenome_adv,
    advogado.email_adv,
    advogado.cidade_adv,
    advogado.telefone_adv,
    advogado.nome_usuario_adv,
    advogado.formacao,
    advogado.senha_adv,
    advogado.cadastro_completo
From
    advogado Inner Join
    cidade On advogado.cidade_adv = cidade.id_cidade";

        if ($busca!=''){
            $sql .= " and (
                        nome LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }


}
