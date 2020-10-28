<?php


namespace App;

use Twig;
use App\Template;
use App\Conexao;

class Controller
{
    protected $template;
    protected $db;

    public function __construct()
    {
        $this->db = Conexao::connect();
        $this->template = new Template();
    }


    static public function errorNotFound($error='')
    {
        http_response_code(404);
        $template = new Template();
        echo $template->twig->render('404.html.twig', compact("error"));
        exit;
    }

    static public function errorPermission(){
        http_response_code(403);
        $template = new Template();
        echo $template->twig->render('permission.html.twig');
        exit;
    }

    public function retornaErro($mensagem)
    {
        $retorno['status'] = 0;
        $retorno['mensagem'] = $mensagem;

        echo $this->jsonResponse($retorno);
        exit;
    }

    public function retornaOK($mensagem)
    {
        $retorno['status'] = 1;
        $retorno['mensagem'] = $mensagem;

        echo $this->jsonResponse($retorno);
        exit;
    }

    public function jsonResponse($json)
    {
        echo json_encode($json);
        exit;
    }


}
