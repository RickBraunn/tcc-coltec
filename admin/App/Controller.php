<?php


namespace App;

use Twig, App\Template;

class Controller
{
    protected $template;

    public function __construct()
    {
        $this->template = new Template();
    }


    public function errorNotFound($error='')
    {
        http_response_code(404);
        echo $this->template->twig->render('404.html.twig', compact("error"));
        exit;
    }

    static public function errorPermission(){
        http_response_code(403);
        $template = new Template();
        echo $template->twig->render('permission.html.twig');
        exit;
    }

    public function jsonResponse($json)
    {
        echo json_encode($json);
        exit;
    }


}
