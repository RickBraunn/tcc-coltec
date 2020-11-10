<?php


namespace App;


class ControllerSeguro extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['liberado']) || $_SESSION['liberado'] != true) {
            //\App\Controller::errorPermission();
            header("Location: /login");
        }

		$this->template->twig->addGlobal('session', $_SESSION);

    }


}
